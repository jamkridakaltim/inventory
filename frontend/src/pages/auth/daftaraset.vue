<template>
  <div class="asset-list-page">
    <div class="asset-list-shell">
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
        </nav>
      </aside>

      <main class="main-content">
        <div class="container py-4">
          <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
            <div>
              <p class="text-uppercase text-muted mb-1 small">Daftar Aset</p>
              <h2 class="fw-bold mb-0">Inventaris Aset</h2>
            </div>
            <div class="d-flex gap-2 flex-wrap">
              <button class="btn btn-outline-primary" @click="refreshAssets">Refresh</button>
              <button class="btn btn-primary" @click="goToInputAset">Input Aset Baru</button>
            </div>
          </div>

          <div class="search-bar-card mb-4">
            <div class="search-input-shell" :class="{ 'is-focused': isSearchFocused }">
              <svg class="search-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24">
                <circle cx="11" cy="11" r="7" stroke="currentColor" stroke-width="2"/>
                <path d="m20 20-3.5-3.5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
              </svg>

              <input
                type="text"
                class="search-input"
                placeholder="Cari nama barang, no register, kategori, lokasi, atau pemakai..."
                v-model="searchQuery"
                @focus="isSearchFocused = true"
                @blur="isSearchFocused = false"
              />

              <button
                v-if="searchQuery"
                class="clear-btn"
                type="button"
                title="Bersihkan pencarian"
                @click="searchQuery = ''"
              >
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24">
                  <path d="M6 6l12 12M18 6 6 18" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"/>
                </svg>
              </button>
            </div>

            <div class="search-meta">
              <span class="result-chip" :class="{ 'result-chip--empty': filteredAssets.length === 0 && searchQuery }">
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24">
                  <rect x="3" y="4" width="18" height="16" rx="3" stroke="currentColor" stroke-width="2"/>
                  <path d="M3 9h18" stroke="currentColor" stroke-width="2"/>
                </svg>
                {{ filteredAssets.length }} / {{ assets.length }} aset
              </span>
              <transition name="fade-slide">
                <span v-if="searchQuery" class="search-term-hint">
                  hasil untuk <strong>&ldquo;{{ searchQuery }}&rdquo;</strong>
                </span>
              </transition>
            </div>
          </div>

          <div class="row g-4">
            <div v-for="asset in filteredAssets" :key="asset.id" class="col-12">
              <div class="card shadow-sm border-0 asset-card">
                <div class="card-body">
                  <div class="d-flex flex-column flex-lg-row justify-content-between gap-3">
                    <div>
                      <div class="d-flex align-items-center gap-3 mb-2">
                        <h5 class="asset-code mb-0">{{ asset.register }}</h5>
                        <span class="badge bg-success">{{ asset.condition }}</span>
                      </div>
                      <p class="mb-1 asset-name">{{ asset.name }}</p>
                      <div class="asset-meta">
                        <div><span class="meta-label">Merk/Type:</span> {{ asset.merk }}</div>
                        <div><span class="meta-label">Kategori:</span> {{ asset.category }}</div>
                        <div><span class="meta-label">Lokasi:</span> {{ asset.location }}</div>
                        <div><span class="meta-label">Pemakai:</span> {{ asset.user }}</div>
                      </div>
                    </div>

                    <div class="text-lg-end">
                      <p class="mb-1 text-muted">No Memo</p>
                      <h5 class="mb-2">{{ asset.memo }}</h5>
                      <p class="mb-0 fw-semibold text-primary">{{ formatPrice(asset.price) }}</p>
                    </div>
                  </div>

                  <div class="d-flex flex-wrap gap-2 mt-4">
                    <button class="btn btn-outline-secondary btn-sm" @click="viewAsset(asset)">Detail</button>
                    <button class="btn btn-outline-success btn-sm" @click="editAsset(asset)">Edit</button>
                    <button class="btn btn-outline-danger btn-sm" @click="deleteAsset(asset)">Hapus</button>
                  </div>
                </div>
              </div>
            </div>

            <div v-if="filteredAssets.length === 0" class="col-12">
              <div class="text-center text-muted py-5">
                Tidak ada aset yang cocok dengan pencarian "{{ searchQuery }}".
              </div>
            </div>
          </div>

          <div v-if="selectedAsset" class="asset-modal-backdrop" @click.self="selectedAsset = null">
            <div class="asset-modal-card">
              <div class="d-flex justify-content-between align-items-start mb-3 gap-3">
                <div>
                  <h5 class="fw-bold mb-1">Detail Aset</h5>
                  <p class="text-muted small mb-0">Informasi lengkap aset yang dipilih.</p>
                </div>
                <button class="btn btn-close" @click="selectedAsset = null"></button>
              </div>

              <div class="row g-3">
                <div class="col-12 col-md-6">
                  <p class="text-muted small mb-1">No Register</p>
                  <p class="fw-semibold mb-0">{{ selectedAsset.register }}</p>
                </div>
                <div class="col-12 col-md-6">
                  <p class="text-muted small mb-1">No Memo</p>
                  <p class="fw-semibold mb-0">{{ selectedAsset.memo }}</p>
                </div>
                <div class="col-12 col-md-6">
                  <p class="text-muted small mb-1">Nama Barang</p>
                  <p class="fw-semibold mb-0">{{ selectedAsset.name }}</p>
                </div>
                <div class="col-12 col-md-6">
                  <p class="text-muted small mb-1">Merk/Type</p>
                  <p class="fw-semibold mb-0">{{ selectedAsset.merk }}</p>
                </div>
                <div class="col-12 col-md-6">
                  <p class="text-muted small mb-1">Kategori</p>
                  <p class="fw-semibold mb-0">{{ selectedAsset.category }}</p>
                </div>
                <div class="col-12 col-md-6">
                  <p class="text-muted small mb-1">Lokasi</p>
                  <p class="fw-semibold mb-0">{{ selectedAsset.location }}</p>
                </div>
                <div class="col-12 col-md-6">
                  <p class="text-muted small mb-1">Pemakai</p>
                  <p class="fw-semibold mb-0">{{ selectedAsset.user }}</p>
                </div>
                <div class="col-12 col-md-6">
                  <p class="text-muted small mb-1">Harga</p>
                  <p class="fw-semibold mb-0">{{ formatPrice(selectedAsset.price) }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()
const activeMenu = ref('Daftar Aset')

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

const selectedAsset = ref(null)
const searchQuery = ref('')
const isSearchFocused = ref(false)

const assets = ref([
  {
    id: 1,
    register: '00226',
    memo: '11/20',
    name: 'Kursi',
    merk: 'Chitose - Caesar (Biru)',
    category: 'Meubelair',
    location: '241 - Ruang Rapat',
    user: 'PUBLIK',
    condition: 'Baik',
    price: 555000
  },
  {
    id: 2,
    register: '00290b',
    memo: '07/24',
    name: 'Mic',
    merk: 'DMU 280',
    category: 'Elektronik',
    location: '241 - Ruang Rapat',
    user: 'PUBLIK',
    condition: 'Baik',
    price: 739000
  }
])

const filteredAssets = computed(() => {
  const query = searchQuery.value.trim().toLowerCase()
  if (!query) return assets.value

  return assets.value.filter((asset) => {
    return (
      asset.register.toLowerCase().includes(query) ||
      asset.name.toLowerCase().includes(query) ||
      asset.merk.toLowerCase().includes(query) ||
      asset.category.toLowerCase().includes(query) ||
      asset.location.toLowerCase().includes(query) ||
      asset.user.toLowerCase().includes(query) ||
      asset.memo.toLowerCase().includes(query)
    )
  })
})

function formatPrice(value) {
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    maximumFractionDigits: 0
  }).format(value)
}

function viewAsset(asset) {
  selectedAsset.value = asset
}

function editAsset(asset) {
  router.push({ path: '/inputaset', query: { editId: asset.id } })
}

function deleteAsset(asset) {
  const confirmed = window.confirm('Yakin ingin menghapus aset ini?')
  if (!confirmed) return
  assets.value = assets.value.filter((item) => item.id !== asset.id)
}

function refreshAssets() {
  alert('Daftar aset diperbarui.')
}

function goToInputAset() {
  router.push('/inputaset')
}
</script>

<style scoped>
.asset-list-shell {
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

.main-content {
  flex: 1;
  padding: 0 8px 24px;
}

.search-bar-card {
  display: flex;
  flex-direction: column;
  gap: 0.6rem;
}

.search-input-shell {
  position: relative;
  display: flex;
  align-items: center;
  gap: 0.65rem;
  background: #ffffff;
  border: 1.5px solid #e5e7eb;
  border-radius: 999px;
  padding: 0.7rem 1.1rem;
  box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
  transition: border-color 0.2s ease, box-shadow 0.2s ease, transform 0.15s ease;
}

.search-input-shell.is-focused {
  border-color: #1059b9;
  box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.12), 0 8px 20px rgba(15, 23, 42, 0.06);
  transform: translateY(-1px);
}

.search-icon {
  flex-shrink: 0;
  color: #9ca3af;
  transition: color 0.2s ease;
}

.search-input-shell.is-focused .search-icon {
  color: #10b981;
}

.search-input {
  flex: 1;
  border: none;
  outline: none;
  background: transparent;
  font-size: 0.95rem;
  color: #111827;
}

.search-input::placeholder {
  color: #9ca3af;
}

.clear-btn {
  flex-shrink: 0;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 26px;
  height: 26px;
  border-radius: 50%;
  border: none;
  background: #f3f4f6;
  color: #6b7280;
  cursor: pointer;
  transition: background 0.15s ease, color 0.15s ease, transform 0.15s ease;
}

.clear-btn:hover {
  background: #fee2e2;
  color: #ef4444;
  transform: scale(1.08);
}

.search-meta {
  display: flex;
  align-items: center;
  flex-wrap: wrap;
  gap: 0.6rem;
  padding-left: 0.35rem;
}

.result-chip {
  display: inline-flex;
  align-items: center;
  gap: 0.4rem;
  font-size: 0.8rem;
  font-weight: 600;
  color: #047857;
  background: #ecfdf5;
  border: 1px solid #a7f3d0;
  border-radius: 999px;
  padding: 0.25rem 0.7rem;
}

.result-chip--empty {
  color: #b91c1c;
  background: #fef2f2;
  border-color: #fecaca;
}

.search-term-hint {
  font-size: 0.82rem;
  color: #6b7280;
}

.search-term-hint strong {
  color: #111827;
}

.fade-slide-enter-active,
.fade-slide-leave-active {
  transition: opacity 0.15s ease, transform 0.15s ease;
}

.fade-slide-enter-from,
.fade-slide-leave-to {
  opacity: 0;
  transform: translateX(-4px);
}

.asset-card {
  border-radius: 18px;
}

.asset-code {
  font-size: 1.25rem;
}

.asset-name {
  font-size: 1rem;
  font-weight: 600;
}

.asset-meta {
  display: grid;
  gap: 0.45rem;
  color: #6b7280;
  font-size: 0.95rem;
}

.meta-label {
  color: #111827;
  font-weight: 600;
}

.asset-modal-backdrop {
  position: fixed;
  inset: 0;
  background: rgba(15, 23, 42, 0.55);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 30;
  padding: 1.5rem;
}

.asset-modal-card {
  width: min(940px, 100%);
  background: #ffffff;
  border-radius: 20px;
  box-shadow: 0 22px 60px rgba(15, 23, 42, 0.15);
  padding: 1.75rem;
}

.btn-eye,
.btn-edit,
.btn-delete {
  border: 1px solid #d1d5db;
  color: #374151;
  background: #ffffff;
  width: 42px;
  height: 42px;
  border-radius: 12px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  transition: transform 0.15s ease, border-color 0.15s ease;
}

.btn-eye:hover,
.btn-edit:hover,
.btn-delete:hover {
  transform: translateY(-1px);
}

.btn-edit {
  border-color: #10b981;
  color: #10b981;
}

.btn-delete {
  border-color: #ef4444;
  color: #ef4444;
}
</style>