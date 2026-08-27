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
  <label class="form-label">Kondisi *</label>
  <select v-model="form.kondisi" class="form-select">
    <option>Baik</option>
    <option>Rusak Ringan</option>
    <option>Rusak Berat</option>
    <option>Hilang</option>
  </select>
</div>

            <div class="col-12 col-md-4">
              <label class="form-label">Tanggal Pembelian *</label>
              <input v-model="form.tanggalPembelian" type="date" class="form-control" placeholder="dd/mm/yyyy" />
            </div>

            <div class="col-12 col-md-4">
  <label class="form-label">Bukti Pembelian *</label>

  <input
    type="file"
    class="form-control"
    accept=".jpg,.jpeg,.png,.pdf"
    @change="onPurchaseProofChange"
  />

  <small v-if="purchaseProofName" class="text-muted">
    {{ purchaseProofName }}
  </small>
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

  <option
    v-for="category in categories"
    :key="category.id"
    :value="category.id"
  >
    {{ category.name }}
  </option>
</select>
            </div>

            <div class="col-12 col-md-4">
              <label class="form-label">Lokasi *</label>
              <select v-model="form.lokasi" class="form-select">
  <option value="" disabled>Pilih Lokasi</option>

  <option
    v-for="location in locations"
    :key="location.id"
    :value="location.id"
  >
    {{ location.name }}
  </option>
</select>
            </div>

            <div class="col-12 col-md-4">
              <label class="form-label">Pemakai *</label>
              <input
  v-model="form.pemakai"
  type="text"
  class="form-control"
  placeholder="Nama Pemakai"
/>
            </div>

            <div class="col-12 col-md-4">
              <label class="form-label">Harga *</label>
              <input v-model.number="form.harga" type="number" min="0" class="form-control" placeholder="0" />
            </div>

            <div class="col-12 col-md-4">

            </div>
            
            <div class="col-12 col-md-4">
              <label class="form-label">No Memo</label>
<input
  v-model="form.noMemo"
  type="text"
  class="form-control"
  placeholder="No Memo (opsional)"
/>
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
import { ref, onMounted } from 'vue'
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
  activeMenu.value = menu.label
  router.push(menu.path)
}


/*
|--------------------------------------------------------------------------
| DATA MASTER
|--------------------------------------------------------------------------
*/

const categories = ref([])
const locations = ref([])
const users = ref([])

const generatedAssetCode = ref('')

/*
|--------------------------------------------------------------------------
| FORM
|--------------------------------------------------------------------------
*/

const initialForm = () => ({
  tanggalPembelian: '',
  buktiPembelian: null,
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

/*
|--------------------------------------------------------------------------
| PREVIEW FOTO
|--------------------------------------------------------------------------
*/

const fotoAsetPreview = ref(null)
const fotoStikerPreview = ref(null)
const fotoLokasiPreview = ref(null)
const fotoMemoPreview = ref(null)

const purchaseProofName = ref('')

/*
|--------------------------------------------------------------------------
| RESET FORM
|--------------------------------------------------------------------------
*/

function resetForm() {
  form.value = initialForm()

  errors.value = {}

  generatedAssetCode.value = ''

  purchaseProofName.value = ''

  fotoAsetPreview.value = null
  fotoStikerPreview.value = null
  fotoLokasiPreview.value = null
  fotoMemoPreview.value = null
}


/*
|--------------------------------------------------------------------------
| FILE BUKTI PEMBELIAN
|--------------------------------------------------------------------------
*/

function onPurchaseProofChange(event) {
  const file = event.target.files?.[0]

  if (!file) {
    form.value.buktiPembelian = null
    purchaseProofName.value = ''
    return
  }

  form.value.buktiPembelian = file
  purchaseProofName.value = file.name
}


/*
|--------------------------------------------------------------------------
| FILE FOTO
|--------------------------------------------------------------------------
*/

function onFileChange(event, fieldKey, previewKey) {
  const file = event.target.files?.[0]

  if (!file) return

  form.value[fieldKey] = file

  const reader = new FileReader()

  reader.onload = (e) => {
    if (previewKey === 'fotoAsetPreview') {
      fotoAsetPreview.value = e.target.result
    }

    if (previewKey === 'fotoStikerPreview') {
      fotoStikerPreview.value = e.target.result
    }

    if (previewKey === 'fotoLokasiPreview') {
      fotoLokasiPreview.value = e.target.result
    }

    if (previewKey === 'fotoMemoPreview') {
      fotoMemoPreview.value = e.target.result
    }
  }

  reader.readAsDataURL(file)
}


/*
|--------------------------------------------------------------------------
| VALIDASI FORM
|--------------------------------------------------------------------------
*/

function validateForm() {
  errors.value = {}

  if (!form.value.tanggalPembelian) {
    errors.value.tanggalPembelian = 'Tanggal pembelian wajib diisi.'
  }

  if (!form.value.buktiPembelian) {
    errors.value.buktiPembelian = 'Bukti pembelian wajib diupload.'
  }

  if (!form.value.noBuktiPembelian) {
    errors.value.noBuktiPembelian = 'Nomor bukti pembelian wajib diisi.'
  }

  if (!form.value.namaBarang) {
    errors.value.namaBarang = 'Nama barang wajib diisi.'
  }

  if (!form.value.type) {
    errors.value.type = 'Type wajib diisi.'
  }

  if (!form.value.merk) {
    errors.value.merk = 'Merk wajib diisi.'
  }

  if (!form.value.noSeri) {
    errors.value.noSeri = 'Nomor seri wajib diisi.'
  }

  if (!form.value.kategori) {
    errors.value.kategori = 'Kategori wajib dipilih.'
  }

  if (!form.value.lokasi) {
    errors.value.lokasi = 'Lokasi wajib dipilih.'
  }

  if (!form.value.pemakai.trim()) {
  errors.value.pemakai = 'Pemakai wajib diisi.'
}

  if (
    form.value.harga === null ||
    form.value.harga === '' ||
    Number(form.value.harga) < 0
  ) {
    errors.value.harga = 'Harga wajib diisi.'
  }

  if (!form.value.fotoAset) {
    errors.value.fotoAset = 'Foto aset wajib diupload.'
  }

  if (!form.value.fotoStiker) {
    errors.value.fotoStiker = 'Foto stiker wajib diupload.'
  }

  if (!form.value.fotoLokasi) {
    errors.value.fotoLokasi = 'Foto lokasi wajib diupload.'
  }

  return Object.keys(errors.value).length === 0
}


/*
|--------------------------------------------------------------------------
| TOKEN
|--------------------------------------------------------------------------
*/

function getToken() {
  return (
    localStorage.getItem('token') ||
    localStorage.getItem('access_token') ||
    localStorage.getItem('auth_token')
  )
}


/*
|--------------------------------------------------------------------------
| LOAD MASTER DATA
|--------------------------------------------------------------------------
*/

async function loadMasterData() {
  const token = getToken()

  try {

    const headers = {
      Accept: 'application/json'
    }

    if (token) {
      headers.Authorization = `Bearer ${token}`
    }


    /*
     * KATEGORI
     */

    const categoryResponse = await fetch(
      '/api/categories',
      {
        headers
      }
    )

    if (categoryResponse.ok) {
      const categoryResult = await categoryResponse.json()

      categories.value = categoryResult.data || []
    }


    /*
     * LOKASI
     */

    const locationResponse = await fetch(
      '/api/locations',
      {
        headers
      }
    )

    if (locationResponse.ok) {
      const locationResult = await locationResponse.json()

      locations.value = locationResult.data || []
    }


    /*
     * USER
     */

    const userResponse = await fetch(
      '/api/users',
      {
        headers
      }
    )

    if (userResponse.ok) {
      const userResult = await userResponse.json()

      users.value = userResult.data || []
    }

  } catch (error) {

    console.error(
      'Gagal mengambil data master:',
      error
    )

  }
}


/*
|--------------------------------------------------------------------------
| SIMPAN ASSET
|--------------------------------------------------------------------------
*/

async function saveAsset() {

  if (!validateForm()) {
    window.scrollTo({
      top: 0,
      behavior: 'smooth'
    })

    return
  }


  const token = getToken()


  /*
   * Gunakan FormData karena ada upload file.
   */

  const formData = new FormData()


  /*
   * Data asset
   */

  formData.append(
    'asset_name',
    form.value.namaBarang
  )

  formData.append(
    'category_id',
    form.value.kategori
  )

  formData.append(
    'brand',
    form.value.merk
  )

  formData.append(
    'model',
    form.value.type
  )

  formData.append(
    'serial_number',
    form.value.noSeri
  )

  formData.append(
    'location_id',
    form.value.lokasi
  )

  formData.append(
  'assigned_user_name',
  form.value.pemakai.trim()
)

formData.append(
  'assigned_user_name',
  form.value.pemakai
)

  formData.append(
    'purchase_date',
    form.value.tanggalPembelian
  )

  formData.append(
    'purchase_proof_number',
    form.value.noBuktiPembelian
  )

  formData.append(
    'acquisition_cost',
    Number(form.value.harga)
  )

  formData.append(
    'condition_status',
    convertCondition(form.value.kondisi)
  )



  /*
   * Bukti pembelian
   */

  if (form.value.buktiPembelian) {
    formData.append(
      'purchase_proof',
      form.value.buktiPembelian
    )
  }


  /*
   * Foto asset
   */

  if (form.value.fotoAset) {
    formData.append(
      'photo_asset',
      form.value.fotoAset
    )
  }


  /*
   * Foto sticker
   */

  if (form.value.fotoStiker) {
    formData.append(
      'photo_sticker',
      form.value.fotoStiker
    )
  }


  /*
   * Foto lokasi
   */

  if (form.value.fotoLokasi) {
    formData.append(
      'photo_location',
      form.value.fotoLokasi
    )
  }


  /*
   * Foto memo
   */

  if (form.value.fotoMemo) {
    formData.append(
      'photo_memo',
      form.value.fotoMemo
    )
  }


  try {

    const headers = {
      Accept: 'application/json'
    }

    if (token) {
      headers.Authorization = `Bearer ${token}`
    }


    const response = await fetch(
      '/api/assets',
      {
        method: 'POST',
        headers,
        body: formData
      }
    )


    const result = await response.json()


    if (!response.ok || !result.success) {

      console.error(
        'Gagal menyimpan asset:',
        result
      )

      alert(
        result.message ||
        'Aset gagal disimpan.'
      )

      return
    }


    /*
     * Ambil nomor register dari backend.
     */

    generatedAssetCode.value =
      result.data?.asset_code || ''


    alert(
      `Aset berhasil disimpan.\nNo Register: ${generatedAssetCode.value}`
    )


    /*
     * Setelah berhasil:
     * kembali ke daftar aset.
     */

    router.push('/daftaraset')


  } catch (error) {

    console.error(
      'Error saat menyimpan asset:',
      error
    )

    alert(
      'Terjadi kesalahan saat menghubungi server.'
    )

  }
}


/*
|--------------------------------------------------------------------------
| KONVERSI KONDISI FRONTEND → DATABASE
|--------------------------------------------------------------------------
*/

function convertCondition(condition) {
  const mapping = {
    'Baik': 'good',
    'Rusak Ringan': 'fair',
    'Rusak Berat': 'damaged',
    'Hilang': 'lost'
  }

  return mapping[condition] || 'good'
}


/*
|--------------------------------------------------------------------------
| LOAD SAAT HALAMAN DIBUKA
|--------------------------------------------------------------------------
*/

onMounted(() => {
  loadMasterData()
})
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
