<template>
  <div class="report-page">
    <section class="memo-document" aria-label="Internal memo untuk dicetak">
      <h1>INTERNAL MEMO</h1>

      <div class="memo-meta">
        <div class="memo-meta__label">Nomor</div><div class="memo-meta__colon">:</div><div>{{ memoNumber }}</div>
        <div class="memo-meta__label">Tanggal</div><div class="memo-meta__colon">:</div><div>{{ memoDate }}</div>
        <div class="memo-meta__label">Kepada Yth</div><div class="memo-meta__colon">:</div><div>Direktur Utama PT. Jamkrida Kaltim</div>
        <div class="memo-meta__label">Dari</div><div class="memo-meta__colon">:</div><div>Bagian Administrasi Umum, SDM dan IT</div>
        <div class="memo-meta__label">Perihal</div><div class="memo-meta__colon">:</div><div>Permohonan Pengadaan Aset dan Inventaris Kantor</div>
      </div>

      <div class="memo-rule"></div>

      <p>Dengan Hormat,</p>
      <p>
        Dalam rangka mendukung kegiatan operasional PT. Jamkrida Kaltim, bersama ini kami
        mengajukan permohonan pengadaan aset dan inventaris kantor untuk menunjang sarana
        dan prasarana yang dibutuhkan.
      </p>
      <p>Adapun rincian biaya tersebut adalah sebagai berikut:</p>

      <table class="memo-table">
        <thead>
          <tr>
            <th>Keterangan</th>
            <th>Unit Kerja</th>
            <th>Pengajuan</th>
            <th>Realisasi</th>
            <th>Kekurangan/<br />Kelebihan</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="item in printRows" :key="item.id">
            <td>{{ item.name }}</td>
            <td>{{ item.kategori }}</td>
            <td class="memo-table__currency">{{ formatCurrency(item.pengajuan) }}</td>
            <td class="memo-table__currency">{{ item.status === 'Disetujui' ? formatCurrency(item.pengajuan) : '' }}</td>
            <td></td>
          </tr>
          <tr v-if="printRows.length === 0">
            <td colspan="5" class="memo-table__empty">Tidak ada pengajuan yang dipilih.</td>
          </tr>
        </tbody>
        <tfoot>
          <tr>
            <th colspan="2">Total</th>
            <th class="memo-table__currency">{{ formatCurrency(totalPengajuan) }}</th>
            <th class="memo-table__currency">{{ formatCurrency(totalRealisasi) }}</th>
            <th></th>
          </tr>
        </tfoot>
      </table>

      <p>Demikian kami sampaikan, atas perhatian dan dukungannya diucapkan terima kasih.</p>

      <div class="memo-applicant">
        <strong>PT. JAMKRIDA KALTIM</strong>
        <span>Pemohon,</span>
        <strong class="memo-signature-name">BAGIAN PENGAJUAN ASET</strong>
        <strong>Administrasi Umum, SDM dan IT</strong>
      </div>

      <div class="memo-approval">
        <div class="memo-notes">
          <strong>Catatan Direksi :</strong>
          <span></span><span></span><span></span><span></span>
        </div>
        <div class="memo-approved">
          <span>Disetujui Oleh :</span>
          <strong class="memo-signature-name">DIREKTUR UTAMA</strong>
          <span>PT. Jamkrida Kaltim</span>
        </div>
      </div>

      <div class="memo-copy">
        <span>Tembusan :</span>
        <ol>
          <li>Bagian Keuangan, Akuntansi dan Perencanaan</li>
          <li>Arsip</li>
        </ol>
      </div>
    </section>

    <div class="report-shell">
      <aside class="sidebar no-print">
        <div class="sidebar-brand">
          <div class="brand-icon">A</div>
          <div>
            <h5 class="mb-0">Inventory</h5>
            <small class="text-light-emphasis">Asset System</small>
          </div>
        </div>

        <nav class="sidebar-nav">
          <button
            v-for="menu in menus"
            :key="menu.label"
            class="nav-item"
            :class="{ active: activeMenu === menu.label }"
            type="button"
            @click="handleMenuClick(menu)"
          >
            <span class="nav-icon">{{ menu.icon }}</span>
            <span>{{ menu.label }}</span>
          </button>
        </nav>
      </aside>

      <main class="main-content">
        <div class="container py-4">
          <div class="print-only-header">
            <h2 class="fw-bold mb-1">Laporan Pengajuan</h2>
            <p class="text-muted mb-4">Dicetak pada {{ printedAt }}</p>
          </div>

          <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 no-print">
            <div>
              <p class="fw-bold mb-0">LAPORAN AKTIVITAS</p>
              <h2 class="fw-bold mb-0">Laporan Pengajuan</h2>
            </div>
            <div class="d-flex gap-2 mt-3 mt-md-0">
              <button v-if="selectedIds.length > 0" class="btn btn-success" @click="printSelected">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-1">
                  <path d="M20 6 9 17l-5-5" />
                </svg>
                Cetak Terpilih ({{ selectedIds.length }})
              </button>
              <button class="btn btn-primary" @click="printReport">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-1">
                  <path d="M6 9V2h12v7" />
                  <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2" />
                  <path d="M6 14h12v8H6z" />
                </svg>
                Cetak PDF
              </button>
              <button class="btn btn-outline-secondary" @click="router.push('/dashboard')">
                Kembali ke Dashboard
              </button>
            </div>
          </div>

          <div class="row g-3 mb-4">
            <div class="col-6 col-lg-3">
              <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                  <p class="text-muted small mb-1">Total Pengajuan</p>
                  <h3 class="fw-bold mb-0">{{ submissions.length }}</h3>
                </div>
              </div>
            </div>
            <div class="col-6 col-lg-3">
              <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                  <p class="text-muted small mb-1">Disetujui</p>
                  <h3 class="fw-bold mb-0 text-success">{{ countByStatus('Disetujui') }}</h3>
                </div>
              </div>
            </div>
            <div class="col-6 col-lg-3">
              <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                  <p class="text-muted small mb-1">Tidak Disetujui</p>
                  <h3 class="fw-bold mb-0 text-danger">{{ countByStatus('Tidak Disetujui') }}</h3>
                </div>
              </div>
            </div>
            <div class="col-6 col-lg-3">
              <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                  <p class="text-muted small mb-1">Menunggu</p>
                  <h3 class="fw-bold mb-0 text-warning">{{ countByStatus('Menunggu') }}</h3>
                </div>
              </div>
            </div>
          </div>

          <div class="card shadow-sm border-0">
            <div class="card-body">
              <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-3 no-print">
                <h5 class="fw-bold mb-0">Riwayat Pengajuan</h5>
                <div class="d-flex gap-2">
                  <input
                    v-model="search"
                    type="text"
                    class="form-control form-control-sm"
                    placeholder="Cari nama aset atau pengaju..."
                    style="max-width: 220px;"
                  />
                  <select v-model="statusFilter" class="form-select form-select-sm" style="max-width: 160px;">
                    <option value="Semua">Semua Status</option>
                    <option value="Disetujui">Disetujui</option>
                    <option value="Tidak Disetujui">Tidak Disetujui</option>
                    <option value="Menunggu">Menunggu</option>
                  </select>
                </div>
              </div>
              <h5 class="fw-bold mb-3 print-only">Riwayat Pengajuan</h5>

              <div class="table-responsive">
                <table class="table align-middle mb-0">
                  <thead>
                    <tr>
                      <th class="no-print" style="width: 40px;">
                        <input
                          type="checkbox"
                          class="form-check-input"
                          :checked="isAllSelected"
                          @change="toggleSelectAll"
                        />
                      </th>
                      <th>Nama Aset</th>
                      <th>Kategori</th>
                      <th>Pengaju</th>
                      <th>Tanggal</th>
                      <th>Status</th>
                      <th>Catatan</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr
                      v-for="item in filteredSubmissions"
                      :key="item.id"
                      :class="{ 'print-hide': isPrintingSelected && !selectedIds.includes(item.id) }"
                    >
                      <td class="no-print">
                        <input
                          type="checkbox"
                          class="form-check-input"
                          :value="item.id"
                          v-model="selectedIds"
                        />
                      </td>
                      <td class="fw-semibold">{{ item.name }}</td>
                      <td>{{ item.kategori }}</td>
                      <td>{{ item.pengaju }}</td>
                      <td>{{ item.tanggal }}</td>
                      <td>
                        <span class="badge rounded-pill" :class="statusBadgeClass(item.status)">{{ item.status }}</span>
                      </td>
                      <td class="text-muted small">{{ item.catatan }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>

              <div v-if="filteredSubmissions.length === 0" class="text-center text-muted py-5">
                Tidak ada data yang cocok.
              </div>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>
</template>

<script setup>
import { computed, nextTick, ref } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()
const activeMenu = ref('Laporan/Memo')

const menus = [
  { label: 'Dashboard', icon: '◉', path: '/dashboard' },
  { label: 'Persetujuan', icon: '✎', path: '/direktur' },
  { label: 'Laporan/Memo', icon: '▤', path: '/report' },
  { label: 'Daftar Aset', icon: '📋', path: '/daftaraset' },
  { label: 'Input Aset', icon: '✚', path: '/inputaset' },
  { label: 'Profile', icon: '☺', path: '/profile' }
]

function handleMenuClick(menu) {
  router.push(menu.path)
  activeMenu.value = menu.label
}

const search = ref('')
const selectedIds = ref([])
const isPrintingSelected = ref(false)
const statusFilter = ref('Semua')

const submissions = ref([
  {
    id: 1,
    name: 'Mobil Operasional 01',
    kategori: 'Kendaraan',
    pengaju: 'Budi Santoso',
    tanggal: '12 Jan 2024',
    status: 'Disetujui',
    catatan: 'Digunakan untuk distribusi internal.',
    pengajuan: 320000000
  },
  {
    id: 2,
    name: 'Laptop Lenovo',
    kategori: 'Elektronik',
    pengaju: 'Dina Rahma',
    tanggal: '02 Mar 2024',
    status: 'Menunggu',
    catatan: 'Masih menunggu approval.',
    pengajuan: 14500000
  },
  {
    id: 3,
    name: 'Printer Canon',
    kategori: 'Office',
    pengaju: 'Rina Wijaya',
    tanggal: '18 Apr 2023',
    status: 'Tidak Disetujui',
    catatan: 'Kualitas cetak perlu dicek ulang.',
    pengajuan: 7800000
  },
  {
    id: 4,
    name: 'Meja Kerja',
    kategori: 'Furniture',
    pengaju: 'Andi Pratama',
    tanggal: '08 Jul 2023',
    status: 'Disetujui',
    catatan: 'Sudah terpasang di ruang operasional.',
    pengajuan: 3200000
  },
  {
    id: 5,
    name: 'AC Split 1PK',
    kategori: 'Elektronik',
    pengaju: 'Siti Aminah',
    tanggal: '25 Mei 2024',
    status: 'Menunggu',
    catatan: 'Menunggu persetujuan pimpinan.',
    pengajuan: 4500000
  }
])

const countByStatus = (status) => submissions.value.filter((item) => item.status === status).length

const filteredSubmissions = computed(() => {
  const query = search.value.trim().toLowerCase()
  return submissions.value.filter((item) => {
    const matchesQuery =
      !query ||
      item.name.toLowerCase().includes(query) ||
      item.pengaju.toLowerCase().includes(query)
    const matchesStatus = statusFilter.value === 'Semua' || item.status === statusFilter.value
    return matchesQuery && matchesStatus
  })
})

function statusBadgeClass(status) {
  if (status === 'Disetujui') return 'bg-success-subtle text-success'
  if (status === 'Tidak Disetujui') return 'bg-danger-subtle text-danger'
  return 'bg-warning-subtle text-warning'
}

const printedAt = computed(() => {
  return new Date().toLocaleDateString('id-ID', {
    day: 'numeric',
    month: 'long',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
})

const memoDate = computed(() => {
  return new Date().toLocaleDateString('id-ID', {
    day: 'numeric',
    month: 'long',
    year: 'numeric'
  })
})

const memoNumber = computed(() => {
  const date = new Date()
  const romanMonths = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII']
  return `/JK-KP/IM/${romanMonths[date.getMonth()]}/${date.getFullYear()}`
})

const printRows = computed(() => {
  if (isPrintingSelected.value) {
    return submissions.value.filter((item) => selectedIds.value.includes(item.id))
  }
  return filteredSubmissions.value
})

const totalPengajuan = computed(() => {
  return printRows.value.reduce((total, item) => total + (Number(item.pengajuan) || 0), 0)
})

const totalRealisasi = computed(() => {
  return printRows.value
    .filter((item) => item.status === 'Disetujui')
    .reduce((total, item) => total + (Number(item.pengajuan) || 0), 0)
})

function formatCurrency(value) {
  return new Intl.NumberFormat('id-ID', {
    minimumFractionDigits: 0,
    maximumFractionDigits: 0
  }).format(Number(value) || 0)
}

const isAllSelected = computed(() => {
  return filteredSubmissions.value.length > 0 &&
    filteredSubmissions.value.every((item) => selectedIds.value.includes(item.id))
})

function toggleSelectAll() {
  if (isAllSelected.value) {
    const idsOnPage = filteredSubmissions.value.map((item) => item.id)
    selectedIds.value = selectedIds.value.filter((id) => !idsOnPage.includes(id))
  } else {
    const idsOnPage = filteredSubmissions.value.map((item) => item.id)
    selectedIds.value = Array.from(new Set([...selectedIds.value, ...idsOnPage]))
  }
}

function printReport() {
  isPrintingSelected.value = false
  window.print()
}

async function printSelected() {
  if (selectedIds.value.length === 0) return
  isPrintingSelected.value = true
  await nextTick()
  window.print()
}

window.addEventListener('afterprint', () => {
  isPrintingSelected.value = false
})
</script>

<style scoped>
.report-page {
  min-height: 100vh;
  background: linear-gradient(180deg, #f8fafc 0%, #ffffff 100%);
}

.memo-document {
  display: none;
}

.report-shell {
  display: flex;
  min-height: 100vh;
}

.sidebar {
  width: 260px;
  background: linear-gradient(180deg, #0b1320 0%, #111b2c 100%);
  color: #fff;
  padding: 24px 18px;
  display: flex;
  flex-direction: column;
  gap: 20px;
  font-family: Inter, system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
  box-shadow: inset -1px 0 0 rgba(255, 255, 255, 0.06);
}

.sidebar-brand {
  display: flex;
  align-items: center;
  gap: 12px;
  padding-bottom: 8px;
  border-bottom: 1px solid rgba(255, 255, 255, 0.08);
}

.brand-icon {
  width: 42px;
  height: 42px;
  border-radius: 9999px;
  display: flex;
  align-items: center;
  justify-content: center;
  background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
  font-weight: 700;
  color: #ffffff;
  box-shadow: 0 10px 16px rgba(56, 189, 248, 0.16);
  letter-spacing: 0.02em;
}

.sidebar-nav {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.nav-item {
  border: none;
  background: transparent;
  color: #cbd5e1;
  padding: 12px 10px;
  border-radius: 10px;
  text-align: left;
  display: flex;
  align-items: center;
  gap: 10px;
  cursor: pointer;
}

.nav-item:hover,
.nav-item.active {
  background: rgba(255, 255, 255, 0.12);
  color: #fff;
}

.nav-icon {
  width: 20px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
}

.nav-icon svg {
  display: block;
}

.main-content {
  flex: 1;
  padding: 0 8px 24px;
}

.card {
  border-radius: 14px;
}

.table th {
  color: #6c757d;
  font-weight: 600;
}

@media (max-width: 768px) {
  .report-shell {
    flex-direction: column;
  }

  .sidebar {
    width: 100%;
    border-radius: 0 0 20px 20px;
  }

  .main-content {
    padding: 8px 8px 24px;
  }
}

.print-only,
.print-only-header {
  display: none;
}

@media print {
  @page {
    size: A4 portrait;
    margin: 14mm 17mm 13mm;
  }

  .memo-document {
    display: block;
    color: #000;
    font-family: 'Times New Roman', Times, serif;
    font-size: 11pt;
    line-height: 1.3;
  }

  .memo-document h1 {
    margin: 0 0 13px;
    font-size: 16pt;
    font-weight: 700;
    text-align: center;
    text-decoration: underline;
  }

  .memo-meta {
    display: grid;
    grid-template-columns: 142px 18px 1fr;
    gap: 0;
    font-size: 11.5pt;
    font-weight: 700;
    line-height: 1.38;
  }

  .memo-meta__label {
    font-weight: 700;
  }

  .memo-meta__colon {
    text-align: center;
  }

  .memo-rule {
    border-top: 4px double #000;
    margin: 8px 0 14px;
  }

  .memo-document p {
    margin: 0 0 12px;
    text-align: justify;
  }

  .memo-table {
    width: 100%;
    border-collapse: collapse;
    margin: 4px 0 13px;
    font-size: 10pt;
  }

  .memo-table th,
  .memo-table td {
    border: 1px solid #000;
    padding: 5px 6px;
    vertical-align: middle;
  }

  .memo-table th {
    font-weight: 700;
    text-align: center;
    text-transform: uppercase;
  }

  .memo-table td:nth-child(1) { width: 27%; }
  .memo-table td:nth-child(2) { width: 16%; text-align: center; }
  .memo-table td:nth-child(3),
  .memo-table td:nth-child(4),
  .memo-table td:nth-child(5) { width: 19%; }

  .memo-table__currency {
    text-align: right;
    white-space: nowrap;
  }

  .memo-table__empty {
    padding: 10px;
    text-align: center;
  }

  .memo-applicant {
    display: flex;
    flex-direction: column;
    align-items: center;
    min-height: 155px;
    margin-top: 6px;
    text-align: center;
  }

  .memo-applicant > strong:first-child {
    font-size: 12pt;
  }

  .memo-applicant > span {
    margin-top: 6px;
  }

  .memo-signature-name {
    margin-top: auto;
    text-decoration: underline;
  }

  .memo-applicant > strong:last-child {
    margin-top: 0;
  }

  .memo-approval {
    display: grid;
    grid-template-columns: 1.2fr 0.8fr;
    gap: 36px;
    margin-top: 6px;
    min-height: 145px;
  }

  .memo-notes {
    display: flex;
    flex-direction: column;
    gap: 16px;
  }

  .memo-notes > strong {
    text-decoration: underline;
  }

  .memo-notes > span {
    display: block;
    border-bottom: 1px solid #000;
  }

  .memo-approved {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
  }

  .memo-approved > .memo-signature-name {
    margin-top: auto;
  }

  .memo-copy {
    font-size: 9pt;
    line-height: 1.2;
  }

  .memo-copy span {
    display: block;
  }

  .memo-copy ol {
    margin: 2px 0 0;
    padding-left: 22px;
  }

  .report-shell {
    display: none !important;
  }

  .no-print,
  .btn {
    display: none !important;
  }

  .print-hide {
    display: none !important;
  }

  .print-only {
    display: block !important;
  }

  .print-only-header {
    display: block !important;
  }

  .report-page {
    background: #fff;
  }

  .report-shell {
    display: block;
  }

  .main-content {
    padding: 0;
  }

  .card {
    border: none !important;
    box-shadow: none !important;
  }

  .table {
    font-size: 12px;
  }
}
</style>
