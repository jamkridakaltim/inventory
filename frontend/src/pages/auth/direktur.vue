<template>
  <div class="pimpinan-page">
    <div class="pimpinan-shell">
      <aside class="sidebar">
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

    <!-- LOGOUT -->
    <button
        class="nav-item logout-item"
        type="button"
        @click="logout"
    >
        <span class="nav-icon">↪</span>
        <span>Logout</span>
    </button>
</nav>
      </aside>

      <main class="main-content">
        <div class="container py-4">
          <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
  <div>
    <p class="fw-bold mb-0">RINGKASAN PENGAJUAN</p>
    <h2 class="fw-bold mb-0">Persetujuan</h2>
  </div>
</div>

          <div class="card shadow-sm border-0 mb-3">
            <div class="card-body">
              <div class="d-flex align-items-center gap-2 mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <circle cx="11" cy="11" r="7" />
                  <path d="m21 21-4.3-4.3" />
                </svg>
                <h5 class="fw-bold mb-0">Submisions ({{ pimpinanAssets.length }})</h5>
              </div>
              <div class="pimpinan-search-box">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <circle cx="11" cy="11" r="7" />
                  <path d="m21 21-4.3-4.3" />
                </svg>
                <input
                  v-model="pimpinanSearch"
                  type="text"
                  placeholder="Cari berdasarkan no register, nama barang, merk, kategori, lokasi, atau pemakai..."
                />
              </div>
            </div>
          </div>

          <div
            v-for="asset in filteredPimpinanAssets"
            :key="asset.name"
            class="card shadow-sm border-0 mb-3"
          >
            <div class="card-body">
              <div class="d-flex justify-content-between align-items-start mb-2">
                <div>
                  <p class="fw-bold fs-5 mb-0">{{ asset.name }}</p>
                </div>
                <span class="badge rounded-pill" :class="asset.statusClass">{{ asset.status }}</span>
              </div>

              <div class="pimpinan-info-row">
                <span class="text-muted">Kategori:</span>
                <span>{{ asset.kategori }}</span>
              </div>
              <div class="pimpinan-info-row">
                <span class="text-muted">Lokasi:</span>
                <span>{{ asset.lokasi }}</span>
              </div>
              <div class="pimpinan-info-row">
                <span class="text-muted">Pemakai:</span>
                <span>{{ asset.pemakai }}</span>
              </div>
              <div class="pimpinan-info-row">
                <span class="text-muted">Harga:</span>
                <span class="fw-semibold text-primary">{{ formatPrice(asset.harga) }}</span>
              </div>

              <div class="d-flex justify-content-end gap-3 pt-3 mt-2 pimpinan-actions">
                <button class="pimpinan-link-btn text-primary" type="button" @click="viewPimpinanDetail(asset)">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8Z" />
                    <circle cx="12" cy="12" r="3" />
                  </svg>
                  Detail
                </button>
                <button class="pimpinan-link-btn text-success" type="button" @click="approvePimpinanAsset(asset)">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 6 9 17l-5-5" />
                  </svg>
                  Setuju
                </button>
                <button class="pimpinan-link-btn text-danger" type="button" @click="rejectPimpinanAsset(asset)">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 6 6 18" />
                    <path d="M6 6l12 12" />
                  </svg>
                  Tidak Setuju
                </button>
              </div>
            </div>
          </div>

          <div v-if="filteredPimpinanAssets.length === 0" class="text-center text-muted py-5">
            Tidak ada aset yang cocok dengan pencarian.
          </div>

          <div v-if="selectedPimpinanAsset" class="asset-modal-backdrop" @click.self="selectedPimpinanAsset = null">
            <div class="asset-modal-card">
              <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                  <h6 class="fw-bold mb-1">Detail Aset</h6>
                  <p class="text-muted small mb-0">Informasi lengkap untuk aset yang dipilih</p>
                </div>
                <button class="btn btn-close" @click="selectedPimpinanAsset = null"></button>
              </div>
              <div class="row g-3">
                <div class="col-12 col-md-6">
                  <p class="small text-muted mb-1">Nama Aset</p>
                  <p class="fw-semibold mb-0">{{ selectedPimpinanAsset.name }}</p>
                </div>
                <div class="col-12 col-md-6">
                  <p class="small text-muted mb-1">Kategori</p>
                  <p class="fw-semibold mb-0">{{ selectedPimpinanAsset.kategori }}</p>
                </div>
                <div class="col-12 col-md-6">
                  <p class="small text-muted mb-1">Harga</p>
                  <p class="fw-semibold mb-0">{{ formatPrice(selectedPimpinanAsset.harga) }}</p>
                </div>
                <div class="col-12 col-md-6">
                  <p class="small text-muted mb-1">Lokasi</p>
                  <p class="fw-semibold mb-0">{{ selectedPimpinanAsset.lokasi }}</p>
                </div>
                <div class="col-12 col-md-6">
                  <p class="small text-muted mb-1">Pemilik / PIC</p>
                  <p class="fw-semibold mb-0">{{ selectedPimpinanAsset.pemakai }}</p>
                </div>
                <div class="col-12 col-md-6">
                  <p class="small text-muted mb-1">Tanggal Pembelian</p>
                  <p class="fw-semibold mb-0">{{ selectedPimpinanAsset.purchaseDate }}</p>
                </div>
                <div class="col-12 col-md-6">
                  <p class="small text-muted mb-1">Kondisi</p>
                  <p class="fw-semibold mb-0">{{ selectedPimpinanAsset.condition }}</p>
                </div>
                <div class="col-12 col-md-6">
                  <p class="small text-muted mb-1">Keperluan</p>
                  <p class="fw-semibold mb-0">{{ selectedPimpinanAsset.purpose }}</p>
                </div>
                <div class="col-12">
                  <p class="small text-muted mb-1">Catatan</p>
                  <p class="fw-semibold mb-0">{{ selectedPimpinanAsset.notes }}</p>
                </div>
                <div class="col-12">
                  <p class="small text-muted mb-1">Deskripsi</p>
                  <p class="fw-semibold mb-0">{{ selectedPimpinanAsset.description }}</p>
                </div>
              </div>
            </div>
          </div>

          <div v-if="pimpinanAssetToApprove" class="asset-modal-backdrop" @click.self="cancelPimpinanApprove">
            <div class="asset-modal-card asset-modal-card-sm">
              <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                  <h6 class="fw-bold mb-1">Setujui Pengajuan</h6>
                  <p class="text-muted small mb-0">Konfirmasi persetujuan aset ini</p>
                </div>
                <button class="btn btn-close" @click="cancelPimpinanApprove"></button>
              </div>
              <p class="mb-0">
                Setujui pengajuan aset
                <span class="fw-semibold">{{ pimpinanAssetToApprove.name }}</span>
                ?
              </p>
              <div class="d-flex justify-content-end gap-2 mt-4">
                <button class="btn btn-outline-secondary btn-sm btn-equal" @click="cancelPimpinanApprove">Batal</button>
                <button class="btn btn-success btn-sm btn-equal" @click="confirmPimpinanApprove">Setuju</button>
              </div>
            </div>
          </div>

          <div v-if="pimpinanAssetToReject" class="asset-modal-backdrop" @click.self="cancelPimpinanReject">
            <div class="asset-modal-card asset-modal-card-sm">
              <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                  <h6 class="fw-bold mb-1">Tolak Pengajuan</h6>
                  <p class="text-muted small mb-0">Konfirmasi penolakan aset ini</p>
                </div>
                <button class="btn btn-close" @click="cancelPimpinanReject"></button>
              </div>
              <p class="mb-0">
                Tolak pengajuan aset
                <span class="fw-semibold">{{ pimpinanAssetToReject.name }}</span>
                ?
              </p>
              <div class="d-flex justify-content-end gap-2 mt-4">
                <button class="btn btn-outline-secondary btn-sm btn-equal" @click="cancelPimpinanReject">Batal</button>
                <button class="btn btn-danger btn-sm btn-equal" @click="confirmPimpinanReject">Tidak Setuju</button>
              </div>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>
</template>

<script setup>
import { computed, ref } from 'vue'
import { useRouter } from 'vue-router'
import api from '../../api/axios'

const router = useRouter()
const activeMenu = ref('Persetujuan')

const menus = computed(() => {
  return [
    {
      label: 'Persetujuan',
      icon: '✎',
      path: '/direktur'
    },
    {
      label: 'Daftar Aset',
      icon: '📋',
      path: '/daftaraset'
    },
    {
  label: 'Profile',
  icon: '☺',
  path: '/profile'
}
  ]
})

function handleMenuClick(menu) {
  router.push(menu.path)
  activeMenu.value = menu.label
}

async function logout() {
  try {
    await api.post('/logout')
  } catch (error) {
    console.error('Logout error:', error)
  } finally {
    localStorage.removeItem('token')
    localStorage.removeItem('user')
    localStorage.removeItem('loggedInUserEmail')

    router.push('/login')
  }
}

const pimpinanSearch = ref('')
const selectedPimpinanAsset = ref(null)
const pimpinanAssetToApprove = ref(null)
const pimpinanAssetToReject = ref(null)

const pimpinanAssets = ref([
  {
    name: 'Mobil Operasional 01',
    status: 'Disetujui',
    statusClass: 'bg-success-subtle text-success',
    kategori: 'Kendaraan',
    lokasi: 'Gudang A',
    pemakai: 'Budi Santoso',
    harga: 320000000,
    purchaseDate: '12 Jan 2024',
    condition: 'Baik',
    purpose: 'Operasional harian',
    notes: 'Digunakan untuk distribusi internal.',
    description: 'Mobil operasional untuk kebutuhan distribusi internal harian.'
  },
  {
    name: 'Laptop Lenovo',
    status: 'Pending',
    statusClass: 'bg-warning-subtle text-warning',
    kategori: 'Elektronik',
    lokasi: 'Ruang IT',
    pemakai: 'Dina Rahma',
    harga: 14500000,
    purchaseDate: '02 Mar 2024',
    condition: 'Cukup Baik',
    purpose: 'Kebutuhan kerja tim IT',
    notes: 'Masih menunggu approval.',
    description: 'Laptop kerja utama untuk tim IT dengan spesifikasi standar.'
  },
  {
    name: 'Printer Canon',
    status: 'Tidak Disetujui',
    statusClass: 'bg-danger-subtle text-danger',
    kategori: 'Office',
    lokasi: 'Ruang Admin',
    pemakai: 'Rina Wijaya',
    harga: 7800000,
    purchaseDate: '18 Apr 2023',
    condition: 'Perlu Servis',
    purpose: 'Pencetakan dokumen kantor',
    notes: 'Memerlukan pengecekan kualitas cetak.',
    description: 'Printer kantor yang sering digunakan untuk pencetakan dokumen.'
  },
  {
    name: 'Meja Kerja',
    status: 'Disetujui',
    statusClass: 'bg-success-subtle text-success',
    kategori: 'Furniture',
    lokasi: 'Gudang B',
    pemakai: 'Andi Pratama',
    harga: 3200000,
    purchaseDate: '08 Jul 2023',
    condition: 'Baik',
    purpose: 'Area kerja staf',
    notes: 'Sudah terpasang di ruang operasional.',
    description: 'Meja kerja ergonomis untuk area operasional staff.'
  }
])

const filteredPimpinanAssets = computed(() => {
  const query = pimpinanSearch.value.trim().toLowerCase()
  if (!query) return pimpinanAssets.value
  return pimpinanAssets.value.filter((asset) => {
    return [asset.name, asset.kategori, asset.lokasi, asset.pemakai]
      .join(' ')
      .toLowerCase()
      .includes(query)
  })
})

function formatPrice(value) {
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    maximumFractionDigits: 0
  }).format(value)
}

function viewPimpinanDetail(asset) {
  selectedPimpinanAsset.value = asset
}

function approvePimpinanAsset(asset) {
  pimpinanAssetToApprove.value = asset
}

function confirmPimpinanApprove() {
  const targetIndex = pimpinanAssets.value.indexOf(pimpinanAssetToApprove.value)
  if (targetIndex !== -1) {
    pimpinanAssets.value[targetIndex].status = 'Disetujui'
    pimpinanAssets.value[targetIndex].statusClass = 'bg-success-subtle text-success'
  }
  pimpinanAssetToApprove.value = null
}

function cancelPimpinanApprove() {
  pimpinanAssetToApprove.value = null
}

function rejectPimpinanAsset(asset) {
  pimpinanAssetToReject.value = asset
}

function confirmPimpinanReject() {
  const targetIndex = pimpinanAssets.value.indexOf(pimpinanAssetToReject.value)
  if (targetIndex !== -1) {
    pimpinanAssets.value[targetIndex].status = 'Tidak Disetujui'
    pimpinanAssets.value[targetIndex].statusClass = 'bg-danger-subtle text-danger'
  }
  pimpinanAssetToReject.value = null
}

function cancelPimpinanReject() {
  pimpinanAssetToReject.value = null
}
</script>

<style scoped>
.pimpinan-page {
  min-height: 100vh;
  background: linear-gradient(180deg, #f8fafc 0%, #ffffff 100%);
}

.pimpinan-shell {
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

.logout-item {
    margin-top: 10px;
    color: #fca5a5;
}

.logout-item:hover {
    background: rgba(220, 38, 38, 0.15);
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

.pimpinan-search-box {
  display: flex;
  align-items: center;
  gap: 8px;
  border: 1px solid #dee2e6;
  border-radius: 10px;
  padding: 10px 14px;
  color: #6c757d;
}

.pimpinan-search-box input {
  border: none;
  outline: none;
  flex: 1;
  font-size: 14px;
  background: transparent;
}

.pimpinan-info-row {
  display: flex;
  justify-content: space-between;
  padding: 4px 0;
  font-size: 14px;
}

.pimpinan-actions {
  border-top: 1px solid #f1f3f5;
}

.pimpinan-link-btn {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  border: none;
  background: transparent;
  font-weight: 600;
  font-size: 14px;
  cursor: pointer;
  padding: 4px 2px;
}

.pimpinan-link-btn:hover {
  text-decoration: underline;
}

.asset-modal-backdrop {
  position: fixed;
  inset: 0;
  background: rgba(15, 23, 42, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 20px;
  z-index: 1050;
}

.asset-modal-card {
  width: 100%;
  max-width: 560px;
  background: #fff;
  border-radius: 16px;
  padding: 24px;
  box-shadow: 0 16px 40px rgba(15, 23, 42, 0.2);
}

.asset-modal-card-sm {
  max-width: 420px;
}

.btn-equal {
  min-width: 92px;
}

@media (max-width: 768px) {
  .pimpinan-shell {
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
</style>