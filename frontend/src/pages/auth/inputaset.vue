<template>
  <div class="asset-input-page">
    <div class="asset-input-shell">
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
          <div class="page-header d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
            <div>
              <p class="text-uppercase text-muted mb-1 small">Pencatatan Aset</p>
              <h2 class="fw-bold mb-0">Input Asset Baru</h2>
            </div>
            <button type="button" class="btn btn-outline-secondary mt-3 mt-md-0" @click="resetForm">
              Reset Form
            </button>
          </div>

      <div class="card shadow-sm border-0">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center mb-4 flex-column flex-md-row gap-3">
            <div>
              <h5 class="fw-bold mb-0">+ Input Aset Baru</h5>
              <p class="text-muted mb-0">Masukkan data aset persis seperti tampilan form yang diberikan.</p>
            </div>
            <button type="button" class="btn btn-primary" @click="saveAsset">
              Simpan Aset
            </button>
          </div>

          <form @submit.prevent="saveAsset" class="row g-3">
            <div class="col-12 col-md-4">
              <label class="form-label">No Register *</label>
              <input v-model="form.noRegister" type="text" class="form-control" placeholder="No Register" />
            </div>

            <div class="col-12 col-md-4">
              <label class="form-label">Tanggal Pembelian *</label>
              <input v-model="form.tanggalPembelian" type="date" class="form-control" placeholder="dd/mm/yyyy" />
            </div>

            <div class="col-12 col-md-4">
              <label class="form-label">Bukti Pembelian *</label>
              <input v-model="form.buktiPembelian" type="text" class="form-control" placeholder="Bukti Pembelian" />
            </div>

            <div class="col-12 col-md-4">
              <label class="form-label">Nomor Bukti Pembelian *</label>
              <input v-model="form.noBuktiPembelian" type="text" class="form-control" placeholder="Nomor Bukti Pembelian" />
            </div>

            <div class="col-12 col-md-4">
              <label class="form-label">Nama Barang *</label>
              <input v-model="form.namaBarang" type="text" class="form-control" placeholder="Nama Barang" />
            </div>

            <div class="col-12 col-md-4">
              <label class="form-label">Type *</label>
              <input v-model="form.type" type="text" class="form-control" placeholder="Type" />
            </div>

            <div class="col-12 col-md-4">
              <label class="form-label">Merk *</label>
              <input v-model="form.merk" type="text" class="form-control" placeholder="Merk" />
            </div>

            <div class="col-12 col-md-4">
              <label class="form-label">No. Seri *</label>
              <input v-model="form.noSeri" type="text" class="form-control" placeholder="No. Seri" />
            </div>

            <div class="col-12 col-md-4">
              <label class="form-label">Kategori *</label>
              <select v-model="form.kategori" class="form-select">
                <option value="" disabled>Pilih Kategori</option>
                <option>Elektronik</option>
                <option>Furniture</option>
                <option>Kendaraan</option>
                <option>Alat Kantor</option>
                <option>Lainnya</option>
              </select>
            </div>

            <div class="col-12 col-md-4">
              <label class="form-label">Lokasi *</label>
              <select v-model="form.lokasi" class="form-select">
                <option value="" disabled>Pilih Lokasi</option>
                <option>Gedung A</option>
                <option>Gedung B</option>
                <option>Gudang</option>
                <option>Site Lapangan</option>
              </select>
            </div>

            <div class="col-12 col-md-4">
              <label class="form-label">Pemakai *</label>
              <select v-model="form.pemakai" class="form-select">
                <option value="" disabled>Pilih Pemakai</option>
                <option>Admin</option>
                <option>Keuangan</option>
                <option>Teknisi</option>
                <option>Manajer</option>
              </select>
            </div>

            <div class="col-12 col-md-4">
              <label class="form-label">Harga *</label>
              <input v-model.number="form.harga" type="number" min="0" class="form-control" placeholder="0" />
            </div>

            <div class="col-12 col-md-4">
              <label class="form-label">Kondisi *</label>
              <select v-model="form.kondisi" class="form-select">
                <option>Baik</option>
                <option>Rusak Ringan</option>
                <option>Rusak Berat</option>
                <option>Perlu Servis</option>
              </select>
            </div>
            
            <div class="col-12 col-md-4">
              <label class="form-label">No Memo *</label>
              <input v-model="form.noMemo" type="text" class="form-control" placeholder="No Memo" />
            </div>

            <div class="col-12">
              <div class="row g-3">
                <div class="col-12 col-md-4">
                  <label class="form-label">Foto Aset</label>
                  <label class="upload-card" for="foto-aset">
                    <div class="upload-inner">
                      <div class="upload-icon">&#8679;</div>
                      <div>
                        <strong>Klik untuk upload atau drag & drop</strong>
                        <div class="text-muted">JPG/PNG, maks 100KB</div>
                      </div>
                    </div>
                    <input id="foto-aset" type="file" accept="image/png, image/jpeg" class="d-none" @change="onFileChange($event, 'fotoAset', 'fotoAsetPreview')" />
                    <img v-if="fotoAsetPreview" :src="fotoAsetPreview" alt="Foto Aset" class="upload-preview mt-3" />
                  </label>
                </div>

                <div class="col-12 col-md-4">
                  <label class="form-label">Foto Letak Stiker Aset</label>
                  <label class="upload-card" for="foto-stiker">
                    <div class="upload-inner">
                      <div class="upload-icon">&#8679;</div>
                      <div>
                        <strong>Klik untuk upload atau drag & drop</strong>
                        <div class="text-muted">JPG/PNG, maks 100KB</div>
                      </div>
                    </div>
                    <input id="foto-stiker" type="file" accept="image/png, image/jpeg" class="d-none" @change="onFileChange($event, 'fotoStiker', 'fotoStikerPreview')" />
                    <img v-if="fotoStikerPreview" :src="fotoStikerPreview" alt="Foto Stiker" class="upload-preview mt-3" />
                  </label>
                </div>

                <div class="col-12 col-md-4">
                  <label class="form-label">Foto Lokasi Aset</label>
                  <label class="upload-card" for="foto-lokasi">
                    <div class="upload-inner">
                      <div class="upload-icon">&#8679;</div>
                      <div>
                        <strong>Klik untuk upload atau drag & drop</strong>
                        <div class="text-muted">JPG/PNG, maks 100KB</div>
                      </div>
                    </div>
                    <input id="foto-lokasi" type="file" accept="image/png, image/jpeg" class="d-none" @change="onFileChange($event, 'fotoLokasi', 'fotoLokasiPreview')" />
                    <img v-if="fotoLokasiPreview" :src="fotoLokasiPreview" alt="Foto Lokasi" class="upload-preview mt-3" />
                  </label>
                </div>

                <div class="col-12 col-md-4">
                  <label class="form-label">Foto Memo</label>
                  <label class="upload-card" for="foto-memo">
                    <div class="upload-inner">
                      <div class="upload-icon">&#8679;</div>
                      <div>
                        <strong>Klik untuk upload atau drag & drop</strong>
                        <div class="text-muted">JPG/PNG, maks 100KB</div>
                      </div>
                    </div>
                    <input id="foto-memo" type="file" accept="image/png, image/jpeg" class="d-none" @change="onFileChange($event, 'fotoMemo', 'fotoMemoPreview')" />
                    <img v-if="fotoMemoPreview" :src="fotoMemoPreview" alt="Foto Memo" class="upload-preview mt-3" />
                  </label>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </main>
</div>
</div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()
const activeMenu = ref('Input Aset')

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

const initialForm = () => ({ 
  noRegister: '',
  tanggalPembelian: '',
  buktiPembelian: '',
  noBuktiPembelian: '',
  namaBarang: '',
  type: '',
  merk: '',
  noSeri: '',
  kategori: '',
  lokasi: '',
  pemakai: '',
  harga: 0,
  kondisi: 'Baik',
  noMemo: '',
  fotoAset: null,
  fotoStiker: null,
  fotoLokasi: null,
  fotoMemo: null
})

const form = ref(initialForm())
const errors = ref({})
const fotoAsetPreview = ref(null)
const fotoStikerPreview = ref(null)
const fotoLokasiPreview = ref(null)
const fotoMemoPreview = ref(null)

function resetForm() {
  form.value = initialForm()
  errors.value = {}
  fotoAsetPreview.value = null
  fotoStikerPreview.value = null
  fotoLokasiPreview.value = null
  fotoMemoPreview.value = null
}

function onFileChange(event, fieldKey, previewKey) {
  const file = event.target.files?.[0]
  if (!file) return
  form.value[fieldKey] = file
  const reader = new FileReader()
  reader.onload = (e) => {
    if (previewKey === 'fotoAsetPreview') fotoAsetPreview.value = e.target.result
    if (previewKey === 'fotoStikerPreview') fotoStikerPreview.value = e.target.result
    if (previewKey === 'fotoLokasiPreview') fotoLokasiPreview.value = e.target.result
    if (previewKey === 'fotoMemoPreview') fotoMemoPreview.value = e.target.result
  }
  reader.readAsDataURL(file)
}

function validateForm() {
  errors.value = {}
  const requiredFields = [
    'noRegister',
    'tanggalPembelian',
    'buktiPembelian',
    'noBuktiPembelian',
    'namaBarang',
    'type',
    'merk',
    'noSeri',
    'kategori',
    'lokasi',
    'pemakai',
    'harga'
  ]

  requiredFields.forEach((field) => {
    if (!form.value[field] && form.value[field] !== 0) {
      errors.value[field] = 'Harap diisi.'
    }
  })

  return Object.keys(errors.value).length === 0
}

function saveAsset() {
  if (!validateForm()) {
    window.scrollTo({ top: 0, behavior: 'smooth' })
    return
  }

  const savedAsset = {
    ...form.value,
    harga: Number(form.value.harga),
    tanggalPembelian: form.value.tanggalPembelian
  }

  console.log('Asset tersimpan:', savedAsset)
  alert('Aset berhasil disimpan.')
  resetForm()
}
</script>

<style scoped>
.asset-input-page {
  min-height: 100vh;
  background: linear-gradient(180deg, #f8fafc 0%, #ffffff 100%);
}

.asset-input-shell {
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

.page-header {
  gap: 1rem;
}

.upload-card {
  display: block;
  min-height: 240px;
  border: 1px dashed #cbd5e1;
  border-radius: 16px;
  padding: 1.5rem;
  text-align: center;
  cursor: pointer;
  transition: border-color 0.2s ease, background-color 0.2s ease;
  background: #ffffff;
}

.upload-card:hover {
  border-color: #94a3b8;
  background: #f8fafc;
}

.upload-inner {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  gap: 0.75rem;
  color: #64748b;
}

.upload-icon {
  width: 48px;
  height: 48px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  font-size: 28px;
  border-radius: 50%;
  background: #e2e8f0;
  color: #1e293b;
}

.upload-preview {
  width: 100%;
  height: auto;
  max-height: 160px;
  object-fit: cover;
  border-radius: 12px;
  border: 1px solid #e2e8f0;
}

.form-label {
  font-weight: 600;
}

@media (max-width: 767px) {
  .page-header {
    align-items: stretch;
  }
}
</style>
