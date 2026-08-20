<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\AssetRequest\StoreAssetRequestRequest;
use App\Http\Requests\AssetRequest\UpdateAssetRequestRequest;
use App\Models\AssetRequest;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;
use App\Http\Requests\AssetRequest\ApproveAssetRequestRequest;
use App\Models\RequestApproval;
use App\Http\Requests\AssetRequest\RejectAssetRequestRequest;
use Barryvdh\DomPDF\Facade\Pdf;

class AssetRequestController extends Controller
{
    /**
     * Menampilkan seluruh request.
     */
    public function index(): JsonResponse
    {
        $requests = AssetRequest::with([
            'requester',
            'department',
            'items',
        ])
        ->orderByDesc('created_at')
        ->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar pengajuan aset berhasil diambil.',
            'data' => $requests,
        ]);
    }

    /**
     * Menampilkan detail request.
     */
    public function show(AssetRequest $assetRequest): JsonResponse
    {
        $assetRequest->load([
            'requester',
            'department',
            'items',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Detail pengajuan aset berhasil diambil.',
            'data' => $assetRequest,
        ]);
    }

    /**
     * Membuat draft pengajuan baru.
     */
    public function store(StoreAssetRequestRequest $request): JsonResponse
{
    // Generate nomor memo terlebih dahulu
    $memoNumber = $this->generateMemoNumber();

    $assetRequest = AssetRequest::create([
        'memo_number'    => $memoNumber,
        'request_date'   => $request->request_date,
        'requester_id'   => $request->requester_id,
        'department_id'  => $request->department_id,
        'recipient_name' => $request->recipient_name,
        'sender_name'    => $request->sender_name,
        'subject'        => $request->subject,
        'notes'          => $request->notes,
        'status'         => 'draft',
    ]);

    $assetRequest->load([
        'requester',
        'department',
        'items',
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Draft pengajuan aset berhasil dibuat.',
        'data' => $assetRequest,
    ], 201);
}

    /**
 * Mengubah draft pengajuan aset.
 */
public function update(
    UpdateAssetRequestRequest $request,
    AssetRequest $assetRequest
): JsonResponse {

    if ($assetRequest->status !== 'draft') {
        return response()->json([
            'success' => false,
            'message' => 'Hanya draft yang dapat diubah.',
        ], 400);
    }

    $assetRequest->update([
    'request_date'   => $request->request_date,
    'department_id'  => $request->department_id,
    'recipient_name' => $request->recipient_name,
    'sender_name'    => $request->sender_name,
    'subject'        => $request->subject,
    'notes'          => $request->notes,
]);

    $assetRequest->refresh()->load([
        'requester',
        'department',
        'items',
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Draft pengajuan aset berhasil diperbarui.',
        'data' => $assetRequest,
    ]);
}

/**
 * Menghapus draft pengajuan aset.
 */
public function destroy(
    AssetRequest $assetRequest
): JsonResponse {

    if ($assetRequest->status !== 'draft') {
        return response()->json([
            'success' => false,
            'message' => 'Hanya draft yang dapat dihapus.',
        ], 400);
    }

    $assetRequest->delete();

    return response()->json([
        'success' => true,
        'message' => 'Draft pengajuan aset berhasil dihapus.',
    ]);
}

/**
 * Submit draft pengajuan aset.
 */
public function submit(AssetRequest $assetRequest): JsonResponse
{
    // Hanya draft yang boleh disubmit
    if ($assetRequest->status !== 'draft') {
        return response()->json([
            'success' => false,
            'message' => 'Pengajuan sudah disubmit atau diproses.',
        ], 400);
    }

    // Minimal memiliki 1 item
    if ($assetRequest->items()->count() === 0) {
        return response()->json([
            'success' => false,
            'message' => 'Pengajuan harus memiliki minimal satu item.',
        ], 400);
    }

    $assetRequest->update([
        'status' => 'pending',
        'submitted_at' => now(),
    ]);

    $assetRequest->refresh()->load([
        'requester',
        'department',
        'items',
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Pengajuan berhasil disubmit.',
        'data' => $assetRequest,
    ]);
}
    /**
     * Generate nomor memo otomatis.
     */
    private function generateMemoNumber(): string
    {
        $date = Carbon::now()->format('Ymd');

        $lastRequest = AssetRequest::whereDate(
                'created_at',
                Carbon::today()
            )
            ->latest('id')
            ->first();

        $sequence = 1;

        if ($lastRequest) {
            $lastSequence = (int) substr($lastRequest->memo_number, -4);
            $sequence = $lastSequence + 1;
        }

        return sprintf(
            'REQ-%s-%04d',
            $date,
            $sequence
        );
    }

    public function approve(
    ApproveAssetRequestRequest $request,
    AssetRequest $assetRequest
): JsonResponse
{
    // Hanya pengajuan yang masih pending yang dapat di-approve
    if ($assetRequest->status !== 'pending') {
        return response()->json([
            'success' => false,
            'message' => 'Pengajuan ini tidak dapat di-approve.',
        ], 400);
    }

    $assetRequest->update([
        'status' => 'approved',
        'approved_at' => now(),
        'director_note' => $request->note,
    ]);

    RequestApproval::create([
        'request_id' => $assetRequest->id,
        'approver_id' => 1, // nanti diganti auth()->id()
        'action' => 'approved',
        'note' => $request->note,
        'action_at' => now(),
    ]);

    $assetRequest->load([
        'requester',
        'department',
        'items',
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Pengajuan berhasil disetujui.',
        'data' => $assetRequest,
    ]);
}

public function reject(
    RejectAssetRequestRequest $request,
    AssetRequest $assetRequest
): JsonResponse
{
    // Hanya request yang masih pending yang boleh direject
    if ($assetRequest->status !== 'pending') {
        return response()->json([
            'success' => false,
            'message' => 'Pengajuan ini tidak dapat ditolak.',
        ], 400);
    }

    $assetRequest->update([
        'status' => 'rejected',
        'rejected_at' => now(),
        'director_note' => $request->note,
    ]);

    RequestApproval::create([
        'request_id' => $assetRequest->id,
        'approver_id' => 1, // nanti diganti auth()->id()
        'action' => 'rejected',
        'note' => $request->note,
        'action_at' => now(),
    ]);

    $assetRequest->load([
        'requester',
        'department',
        'items',
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Pengajuan berhasil ditolak.',
        'data' => $assetRequest,
    ]);
}

public function pdf(AssetRequest $assetRequest)
{
    $assetRequest->load([
        'requester',
        'department',
        'items',
    ]);

    $pdf = Pdf::loadView('pdf.internal_memo', [
        'assetRequest' => $assetRequest,
    ]);

    return $pdf->download($assetRequest->memo_number . '.pdf');
}
}