<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<style>
@page{margin:30px 35px;}
body{font-family:"Times New Roman",serif;font-size:12pt;line-height:1.35;}
.title{text-align:center;font-size:18pt;font-weight:bold;text-decoration:underline;margin-bottom:15px;}
.header{width:100%;border-collapse:collapse;margin-bottom:8px;}
.header td{padding:2px 0;vertical-align:top;}
.label{width:110px;font-weight:bold;}
.colon{width:12px;}
.line{border-top:3px solid #000;margin:4px 0 12px;}
.items{width:100%;border-collapse:collapse;margin-top:5px;}
.items th,.items td{border:1px solid #000;padding:8px 6px;font-size:11pt;}
.items th{text-align:center;font-weight:bold;}
.text-center{text-align:center;}
.text-right{text-align:right;}
p{margin:0;text-align:justify;}
</style>
</head>
<body>

<div class="title">INTERNAL MEMO</div>

<table class="header">
<tr><td class="label">Nomor</td><td class="colon">:</td><td>{{ $assetRequest->memo_number }}</td></tr>
<tr><td class="label">Tanggal</td><td class="colon">:</td><td>{{ $assetRequest->request_date->translatedFormat('j F Y') }}</td></tr>
<tr><td class="label">Kepada Yth</td><td class="colon">:</td><td>{{ $assetRequest->recipient_name }}</td></tr>
<tr><td class="label">Dari</td><td class="colon">:</td><td>{{ $assetRequest->sender_name }}</td></tr>
<tr><td class="label">Perihal</td><td class="colon">:</td><td>{{ $assetRequest->subject }}</td></tr>
</table>

<div class="line"></div>

<p>Dengan Hormat,</p><br>

<p>{{ $assetRequest->notes }}</p><br>

<p>Adapun total biaya tersebut adalah sebagai berikut :</p><br>

@php
$total=0;
@endphp

<table class="items">
<thead>
<tr>
<th width="30%">KETERANGAN</th>
<th width="15%">UNIT KERJA</th>
<th width="10%">JUMLAH</th>
<th width="17%">PENGAJUAN</th>
<th width="14%">REALISASI</th>
<th width="14%">KEKURANGAN/<br>KELEBIHAN</th>
</tr>
</thead>
<tbody>

@foreach($assetRequest->items as $item)
@php
$subtotal=$item->requested_amount;
$total+=$subtotal;
@endphp
<tr>
<td>
<strong>{{ $item->item_name }}</strong>
@if($item->specification)
<br><span style="font-size:10px">{{ $item->specification }}</span>
@endif
</td>
<td class="text-center">{{ $assetRequest->department->name }}</td>
<td class="text-center">{{ $item->quantity }} {{ $item->unit_name }}</td>
<td class="text-center">{{ number_format($subtotal,0,',','.') }}</td>
<td></td>
<td></td>
</tr>
@endforeach

<tr>
<td colspan="3" class="text-center"><strong>Total</strong></td>
<td class="text-center"><strong>{{ number_format($total,0,',','.') }}</strong></td>
<td></td>
<td></td>
</tr>

</tbody>
</table>

<br>

<p>Demikian kami sampaikan, atas perhatian dan dukungannya diucapkan terima kasih.</p>

<table style="width:100%;margin-top:20px;">
<tr><td style="text-align:center;">
<strong>PT. JAMKRIDA KALTIM</strong><br><br>
Pemohon
</td></tr>
</table>

<table style="width:100%;margin-top:45px;">
<tr><td style="text-align:center;">
<strong><u>{{ strtoupper($assetRequest->sender_name) }}</u></strong><br>
{{ $assetRequest->sender_name }}
</td></tr>
</table>

<table style="width:100%;margin-top:45px;">
<tr>
<td style="width:55%;vertical-align:top;">
<b>Catatan Direksi :</b><br><br>
@if($assetRequest->director_note)
{{ $assetRequest->director_note }}
@else
<div style="border-bottom:1px solid #000;height:18px;"></div>
<div style="border-bottom:1px solid #000;height:18px;"></div>
<div style="border-bottom:1px solid #000;height:18px;"></div>
@endif
</td>

<td style="width:45%;text-align:center;vertical-align:top;">
Disetujui Oleh :
<br><br><br><br>
<strong><u>{{ strtoupper($assetRequest->recipient_name) }}</u></strong><br>
Direktur Utama
</td>
</tr>
</table>

<div style="margin-top:25px;font-size:9pt;">
<b>Tembusan :</b>
<ol style="margin-top:4px;padding-left:18px;">
<li>Bagian Keuangan, Akuntansi dan Perencanaan</li>
<li>Arsip</li>
</ol>
</div>

</body>
</html>
