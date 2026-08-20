<template>
  <div class="dashboard-page">
    <div class="dashboard-shell">
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
          <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
            <div>
              <p class="fw-bold mb-0">PENGAJUAN ASSET</p>
              <h2 class="fw-bold mb-0">Selamat Datang, {{ userName }}</h2>
            </div>
            <div class="d-flex gap-2 flex-wrap mt-3 mt-md-0">
              <button
    v-if="user?.role_id === 1"
    @click="router.push('/daftaraset')"
>
    Daftar Aset
</button>
              <button class="btn btn-outline-danger" @click="logout">
                Logout
              </button>
            </div>
          </div>

          <div class="row g-3 mb-4">
            <div v-for="item in stats" :key="item.title" class="col-12 col-md-6 col-lg-3">
              <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                  <div class="d-flex justify-content-between align-items-start">
                    <div>
                      <p class="text-muted small mb-1">{{ item.title }}</p>
                      <h3 class="fw-bold mb-0">{{ item.value }}</h3>
                    </div>
                    <span class="badge rounded-pill" :class="item.badgeClass">{{ item.label }}</span>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="dashboard-content">
          <div class="card shadow-sm border-0 mb-4 submission-form">
            <div class="card-body">
              <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0">Form Pengajuan Memo</h5>
                <button class="btn btn-success btn-sm" @click="saveSubmission">
                  Simpan
                </button>
              </div>

              <div class="row g-3">
                <div class="col-12 col-md-4">
    <label class="form-label fw-semibold">
        Nomor Memo
    </label>

    <input
        type="text"
        class="form-control"
        value="Otomatis oleh sistem"
        disabled
    />
</div>
                <div class="col-12 col-md-4">
                  <label class="form-label fw-semibold">Kepada Yth</label>
                  <select v-model="submission.kepadaYth" class="form-select">
                    <option value="" disabled>Direktur</option>
                    <option value="Direktur Utama PT.Jamkrida Kaltim">Direktur Utama PT.Jamkrida Kaltim</option>
                    <option value="Direktur Umum PT.Jamkrida Kaltim">Direktur Umum PT.Jamkrida Kaltim</option>
                    <option value="Direktur Operasional PT.Jamkrida Kaltim">Direktur Operasional PT.Jamkrida Kaltim</option>
                  </select>
                </div>
                <div class="col-12 col-md-4">
                  <label class="form-label fw-semibold">Dari Bagian</label>
                  <select v-model="submission.dariBagian" class="form-select">
                    <option value="" disabled>Pilih Bagian</option>
                    <option value="Kepala Bagian Manajemen Resiko">Kepala Bagian Manajemen Resiko</option>
                    <option value="Kepala Bagian Administrasi Umum, SDM,">Kepala Bagian Administrasi Umum, SDM,</option>
                    <option value="Kepala Bagian TI">Kepala Bagian TI</option>
                    <option value="Kepala Bagian Penjaminan & Operasional">Kepala Bagian Penjaminan & Operasional</option>
                    <option value="Kepala Bagian Pengendalian, Klaim&Subgrasi">Kepala Bagian Pengendalian, Klaim&Subgrasi</option>
                    <option value="Kepala Bagian SKAI & Kepatuhan">Kepala Bagian SKAI & Kepatuhan</option>
                  </select>
                </div>
                <div class="col-12 col-md-6">
                  <label class="form-label fw-semibold">Perihal</label>
                  <input v-model="submission.purpose" type="text" class="form-control" placeholder="Isilah Perihal Berikut" />
                </div>
                <div class="col-12 col-md-6">
                  <label class="form-label fw-semibold">Catatan</label>
                  <input v-model="submission.notes" type="text" class="form-control" placeholder="Dalam rangka mendukung" />
                </div>
              </div>

              <div class="d-flex justify-content-between align-items-center mt-4 mb-2">
                <h6 class="fw-bold mb-0">Daftar Barang Diajukan</h6>
                <button class="btn btn-outline-primary btn-sm" type="button" @click="addSubmissionItem">
                  + Tambah Barang
                </button>
              </div>

              <div class="table-responsive">
                <table class="table align-middle mb-0">
                  <thead>
                    <tr>
                      <th>Nama Aset</th>
                      <th>Unit Kerja</th>
                      <th style="width: 100px;">Qty</th>
                      <th style="width: 160px;">Pengajuan</th>
                      <th style="width: 60px;"></th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(item, index) in submissionItems" :key="item.id">
                      <td>
                        <input v-model="item.name" type="text" class="form-control form-control-sm" placeholder="Contoh: Laptop" />
                      </td>
                      <td>
                        <input v-model="item.category" type="text" class="form-control form-control-sm" placeholder="Unit Kerja" />
                      </td>
                      <td>
                        <input v-model.number="item.quantity" type="number" min="1" class="form-control form-control-sm" placeholder="1" />
                      </td>
                      <td>
                        <input v-model.number="item.price" type="number" min="0" class="form-control form-control-sm" placeholder="0" />
                      </td>
                      <td class="text-center">
                        <button
                          class="btn-delete"
                          type="button"
                          title="Hapus baris"
                          :disabled="submissionItems.length === 1"
                          @click="removeSubmissionItem(index)"
                        >
                          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 6h18" />
                            <path d="M8 6V4a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2" />
                            <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6" />
                            <path d="M10 11v6" />
                            <path d="M14 11v6" />
                          </svg>
                        </button>
                      </td>
                    </tr>
                  </tbody>
                  <tfoot>
                    <tr>
                      <td colspan="2" class="text-end fw-semibold">Total</td>
                      <td class="fw-bold text-primary">{{ formatPrice(submissionTotal) }}</td>
                      <td colspan="2"></td>
                    </tr>
                  </tfoot>
                </table>
              </div>
            </div>
          </div>

          <div class="row g-4 mb-4 submission-history">
            <div class="col-12 col-lg-8">
              <div class="card shadow-sm border-0">
                <div class="card-body">
                  <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0">Pengajuan Aset</h5>
                    <span class="text-muted small">Data terbaru</span>
                  </div>

                  <div class="table-responsive">
                    <table class="table align-middle mb-0">
                      <thead>
                        <tr>
                          <th>Nama Aset</th>
                          <th>Unit Kerja</th>
                          <th>Pengajuan</th>
                          <th>Status</th>
                          <th style="width: 150px;">Aksi</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="submissionEntry in assets" :key="submissionEntry.id">
                          <td>
                            {{ itemNames(submissionEntry) }}
                            <span v-if="submissionEntry.items.length > 1" class="badge bg-light text-dark ms-1">
                              {{ submissionEntry.items.length }} barang
                            </span>
                          </td>
                          <td>{{ itemCategories(submissionEntry) }}</td>
                          <td>{{ formatPrice(submissionEntryTotal(submissionEntry)) }}</td>
                          <td>
                            <span class="badge" :class="submissionEntry.statusClass">{{ submissionEntry.status }}</span>
                          </td>
                          <td>
                            <div class="d-flex gap-2">
                              <button
                                class="btn-eye"
                                type="button"
                                title="Lihat detail"
                                aria-label="Lihat detail"
                                @click="viewAssetDetail(submissionEntry)"
                              >
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                  <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8Z" />
                                  <circle cx="12" cy="12" r="3" />
                                </svg>
                              </button>
                              <button
                                class="btn-edit"
                                type="button"
                                title="Edit aset"
                                aria-label="Edit aset"
                                @click="editAsset(submissionEntry)"
                              >
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                  <path d="M12 20h9" />
                                  <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4Z" />
                                </svg>
                              </button>
                              <button
                                class="btn-delete"
                                type="button"
                                title="Hapus aset"
                                aria-label="Hapus aset"
                                @click="deleteAsset(submissionEntry)"
                              >
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                  <path d="M3 6h18" />
                                  <path d="M8 6V4a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2" />
                                  <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6" />
                                  <path d="M10 11v6" />
                                  <path d="M14 11v6" />
                                </svg>
                              </button>
                            </div>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>

                  <div v-if="selectedAsset" class="asset-modal-backdrop" @click.self="selectedAsset = null">
                    <div class="asset-modal-card">
                      <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                          <h6 class="fw-bold mb-1">Detail Pengajuan</h6>
                          <p class="text-muted small mb-0">Informasi lengkap untuk memo yang dipilih</p>
                        </div>
                        <button class="btn btn-close" @click="selectedAsset = null"></button>
                      </div>

                      <div class="row g-3 mb-3">
                        <div class="col-12 col-md-6">
                          <p class="small text-muted mb-1">Nomor Memo</p>
                          <p class="fw-semibold mb-0">{{ selectedAsset.memoNumber }}</p>
                        </div>
                        <div class="col-12 col-md-6">
                          <p class="small text-muted mb-1">Tanggal Pengajuan</p>
                          <p class="fw-semibold mb-0">{{ selectedAsset.purchaseDate }}</p>
                        </div>
                        <div class="col-12 col-md-6">
                          <p class="small text-muted mb-1">Dari Bagian</p>
                          <p class="fw-semibold mb-0">{{ selectedAsset.pic }}</p>
                        </div>
                        <div class="col-12 col-md-6">
                          <p class="small text-muted mb-1">Kepada Yth</p>
                          <p class="fw-semibold mb-0">{{ selectedAsset.kepadaYth }}</p>
                        </div>
                        <div class="col-12 col-md-6">
                          <p class="small text-muted mb-1">Perihal</p>
                          <p class="fw-semibold mb-0">{{ selectedAsset.purpose }}</p>
                        </div>
                        <div class="col-12 col-md-6">
                          <p class="small text-muted mb-1">Status</p>
                          <p class="fw-semibold mb-0">{{ selectedAsset.status }}</p>
                        </div>
                        <div class="col-12">
                          <p class="small text-muted mb-1">Catatan</p>
                          <p class="fw-semibold mb-0">{{ selectedAsset.notes }}</p>
                        </div>
                      </div>

                      <p class="fw-bold mb-2">Daftar Barang</p>
                      <div class="table-responsive">
                        <table class="table table-sm align-middle mb-0">
                          <thead>
                            <tr>
                              <th>Nama Aset</th>
                              <th>Unit Kerja</th>
                              <th>Pengajuan</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr v-for="(detailItem, idx) in selectedAsset.items" :key="idx">
                              <td>{{ detailItem.name }}</td>
                              <td>{{ detailItem.category }}</td>
                              <td>{{ formatPrice(detailItem.price) }}</td>
                            </tr>
                          </tbody>
                          <tfoot>
                            <tr>
                              <td colspan="2" class="text-end fw-semibold">Total</td>
                              <td class="fw-bold text-primary">{{ formatPrice(submissionEntryTotal(selectedAsset)) }}</td>
                            </tr>
                          </tfoot>
                        </table>
                      </div>
                    </div>
                  </div>

                  <div v-if="editingAsset" class="asset-modal-backdrop" @click.self="cancelEditAsset">
                    <div class="asset-modal-card">
                      <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                          <h6 class="fw-bold mb-1">Edit Pengajuan</h6>
                          <p class="text-muted small mb-0">Perbarui informasi memo yang dipilih</p>
                        </div>
                        <button class="btn btn-close" @click="cancelEditAsset"></button>
                      </div>

                      <div class="row g-3 mb-3">
                        <div class="col-12 col-md-6">
                          <label class="form-label fw-semibold">Nomor Memo</label>
                          <input v-model="editingAsset.memoNumber" type="text" class="form-control" />
                        </div>
                        <div class="col-12 col-md-6">
                          <label class="form-label fw-semibold">Dari Bagian</label>
                          <input v-model="editingAsset.pic" type="text" class="form-control" />
                        </div>
                        <div class="col-12 col-md-6">
                          <label class="form-label fw-semibold">Kepada Yth</label>
                          <input v-model="editingAsset.kepadaYth" type="text" class="form-control" />
                        </div>
                        <div class="col-12 col-md-6">
                          <label class="form-label fw-semibold">Status</label>
                          <select v-model="editingAsset.status" class="form-select" @change="syncStatusClass">
                            <option value="Pending">Pending</option>
                            <option value="Disetujui">Disetujui</option>
                            <option value="Tidak Disetujui">Tidak Disetujui</option>
                          </select>
                        </div>
                        <div class="col-12">
                          <label class="form-label fw-semibold">Perihal</label>
                          <input v-model="editingAsset.purpose" type="text" class="form-control" />
                        </div>
                        <div class="col-12">
                          <label class="form-label fw-semibold">Catatan</label>
                          <input v-model="editingAsset.notes" type="text" class="form-control" />
                        </div>
                      </div>

                      <div class="d-flex justify-content-between align-items-center mb-2">
                        <p class="fw-bold mb-0">Daftar Barang</p>
                        <button class="btn btn-outline-primary btn-sm" type="button" @click="addEditingItem">
                          + Tambah Barang
                        </button>
                      </div>
                      <div class="table-responsive mb-3">
                        <table class="table table-sm align-middle mb-0">
                          <thead>
                            <tr>
                              <th>Nama Aset</th>
                              <th>Unit Kerja</th>
                              <th style="width: 100px;">Qty</th>
                              <th style="width: 140px;">Pengajuan</th>
                              <th style="width: 50px;"></th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr v-for="(editItem, idx) in editingAsset.items" :key="idx">
                              <td>
                                <input v-model="editItem.name" type="text" class="form-control form-control-sm" />
                              </td>
                              <td>
                                <input v-model="editItem.category" type="text" class="form-control form-control-sm" />
                              </td>
                              <td>
                                <input v-model.number="editItem.quantity" type="number" min="1" class="form-control form-control-sm" />
                              </td>
                              <td>
                                <input v-model.number="editItem.price" type="number" min="0" class="form-control form-control-sm" />
                              </td>
                              <td class="text-center">
                                <button
                                  class="btn-delete"
                                  type="button"
                                  :disabled="editingAsset.items.length === 1"
                                  @click="removeEditingItem(idx)"
                                >
                                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M3 6h18" />
                                    <path d="M8 6V4a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2" />
                                    <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6" />
                                    <path d="M10 11v6" />
                                    <path d="M14 11v6" />
                                  </svg>
                                </button>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </div>

                      <div class="d-flex justify-content-end gap-2 mt-4">
                        <button class="btn btn-outline-secondary btn-sm" @click="cancelEditAsset">Batal</button>
                        <button class="btn btn-primary btn-sm" @click="saveEditAsset">Simpan Perubahan</button>
                      </div>
                    </div>
                  </div>

                  <div v-if="assetToDelete" class="asset-modal-backdrop" @click.self="cancelDeleteAsset">
                    <div class="asset-modal-card asset-modal-card-sm">
                      <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                          <h6 class="fw-bold mb-1">Hapus Pengajuan</h6>
                          <p class="text-muted small mb-0">Tindakan ini tidak dapat dibatalkan</p>
                        </div>
                        <button class="btn btn-close" @click="cancelDeleteAsset"></button>
                      </div>

                      <p class="mb-0">
                        Yakin ingin menghapus pengajuan
                        <span class="fw-semibold">{{ itemNames(assetToDelete) }}</span>
                        dari daftar?
                      </p>

                      <div class="d-flex justify-content-end gap-2 mt-4">
                        <button class="btn btn-outline-secondary btn-sm btn-equal" @click="cancelDeleteAsset">Batal</button>
                        <button class="btn btn-danger btn-sm btn-equal" @click="confirmDeleteAsset">Hapus</button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-12 col-lg-4">
              <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                  <h5 class="fw-bold mb-3">History Hari Ini</h5>
                  <ul class="list-group list-group-flush">
                    <li v-for="activity in activities" :key="activity.text + activity.date" class="list-group-item px-0">
                      <div class="d-flex justify-content-between align-items-start gap-2">
                        <div>
                          <div class="fw-semibold"><span class="text-muted me-2">{{ activity.date }}</span>{{ activity.text }}</div>
                        </div>
                        <span class="badge bg-light text-dark">{{ activity.type || '' }}</span>
                      </div>
                    </li>
                  </ul>
                </div>
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
import { computed, onMounted, ref } from 'vue'
import { useRouter } from 'vue-router'
import api from '../../../api/axios'

const router = useRouter()
const userName = ref('Admin')
const userRoleId = ref(null)
const activeMenu = ref('Dashboard')

const user = ref(null)

try {
    const userData = localStorage.getItem('user')

    if (userData) {
        user.value = JSON.parse(userData)
    }
} catch (error) {
    console.error('Gagal membaca data user:', error)
}

const allMenus = [
  { label: 'Dashboard', icon: '◉', path: '/dashboard', roles: [1, 2] },
  { label: 'Persetujuan', icon: '✎', path: '/direktur', roles: [3] },
  { label: 'Laporan/Memo', icon: '▤', path: '/report', roles: [1] },
  { label: 'Daftar Aset', icon: '📋', path: '/daftaraset', roles: [1, 3] },
  { label: 'Input Aset', icon: '✚', path: '/inputaset', roles: [1] },
  { label: 'Profile', icon: '☺', path: '/profile', roles: [1, 2, 3] }
]

const menus = computed(() => {
  return allMenus.filter(menu =>
    menu.roles.includes(userRoleId.value)
  )
})

function handleMenuClick(menu) {
  router.push(menu.path)
  activeMenu.value = menu.label
}

const selectedAsset = ref(null)
const editingAsset = ref(null)
const assetBeingEdited = ref(null)
const assetToDelete = ref(null)
const submission = ref({
  nomor: '',
  kepadaYth: '',
  dariBagian: '',
  purpose: '',
  notes: ''
})

let nextItemId = 1
function createEmptyItem() {
    return {
        id: nextItemId++,
        name: '',
        specification: '',
        category: '',
        quantity: 1,
        price: '',
        location: ''
    }
}

const submissionItems = ref([createEmptyItem()])

const submissionTotal = computed(() => {
  return submissionItems.value.reduce(
    (sum, item) => sum + (Number(item.price) || 0) * (Number(item.quantity) || 1),
    0
  )
})

function addSubmissionItem() {
  submissionItems.value.push(createEmptyItem())
}

function removeSubmissionItem(index) {
  if (submissionItems.value.length === 1) return
  submissionItems.value.splice(index, 1)
}

onMounted(async () => {
  const savedUser = localStorage.getItem('user')

  if (savedUser) {
    try {
      const user = JSON.parse(savedUser)

      userName.value =
        user.full_name ||
        user.username ||
        'Admin'

      userRoleId.value = user.role_id

    } catch (error) {
      console.error('Gagal membaca data user:', error)
    }
  }

  await getAssetRequests()
})

const totalAjuan = computed(() => assets.value.length)
const totalDisetujui = computed(() => assets.value.filter((a) => a.status === 'Disetujui').length)
const totalPending = computed(() => assets.value.filter((a) => a.status === 'Pending').length)
const totalTidakDisetujui = computed(() => assets.value.filter((a) => a.status === 'Tidak Disetujui').length)

const stats = computed(() => [
  { title: 'Total Ajuan', value: totalAjuan.value, label: 'Ajuan', badgeClass: 'bg-primary-subtle text-primary' },
  { title: 'Total Disetujui', value: totalDisetujui.value, label: 'Disetujui', badgeClass: 'bg-success-subtle text-success' },
  { title: 'Total Pending', value: totalPending.value, label: 'Pending', badgeClass: 'bg-warning-subtle text-warning' },
  { title: 'Total Tidak Disetujui', value: totalTidakDisetujui.value, label: 'Tidak Disetujui', badgeClass: 'bg-danger-subtle text-danger' }
])

const assets = ref([])
const loadingAssets = ref(false)
const assetError = ref('')

async function saveSubmission() {

    // ==============================
    // VALIDASI HEADER
    // ==============================

    if (!submission.value.kepadaYth?.trim()) {
        alert('Kolom Kepada Yth wajib diisi.')
        return
    }

    if (!submission.value.dariBagian?.trim()) {
        alert('Kolom Dari Bagian wajib diisi.')
        return
    }

    if (!submission.value.purpose?.trim()) {
        alert('Perihal wajib diisi.')
        return
    }

    // ==============================
    // VALIDASI ITEM
    // ==============================

    if (submissionItems.value.length === 0) {
        alert('Minimal harus ada satu barang.')
        return
    }

    for (const item of submissionItems.value) {

        if (!item.name?.trim()) {
            alert('Nama barang wajib diisi.')
            return
        }

        if (!item.quantity || Number(item.quantity) < 1) {
            alert('Quantity setiap barang minimal 1.')
            return
        }

        if (!item.category?.trim()) {
            alert('Unit kerja wajib diisi.')
            return
        }

        if (
            item.price === '' ||
            item.price === null ||
            Number(item.price) < 0
        ) {
            alert('Harga barang tidak boleh kosong atau negatif.')
            return
        }
    }

    try {

        loadingAssets.value = true

        // ==============================
        // AMBIL USER LOGIN
        // ==============================

        const savedUser = localStorage.getItem('user')

        if (!savedUser) {
            alert('Data user tidak ditemukan. Silakan login kembali.')
            router.push('/login')
            return
        }

        const user = JSON.parse(savedUser)

        // ==============================
        // 1. BUAT HEADER PENGAJUAN
        // ==============================

        const requestResponse = await api.post(
            '/asset-requests',
            {
                request_date:
                    new Date().toISOString().split('T')[0],

                requester_id:
                    user.id,

                department_id:
                    user.department_id,

                recipient_name:
                    submission.value.kepadaYth.trim(),

                sender_name:
                    submission.value.dariBagian.trim(),

                subject:
                    submission.value.purpose.trim(),

                notes:
                    submission.value.notes?.trim() || null
            }
        )

        console.log(
            'CREATE REQUEST:',
            requestResponse.data
        )

        if (!requestResponse.data.success) {
            throw new Error(
                requestResponse.data.message ||
                'Gagal membuat pengajuan.'
            )
        }

        const createdRequest =
            requestResponse.data.data

        // ==============================
        // 2. TAMBAHKAN ITEM
        // ==============================

        for (const item of submissionItems.value) {

            const itemResponse = await api.post(
                `/asset-requests/${createdRequest.id}/items`,
                {
                    item_name:
                        item.name.trim(),

                    specification:
                        item.specification?.trim() || null,

                    quantity:
                        Number(item.quantity),

                    unit_name:
                        item.category.trim(),

                    requested_amount:
                        Number(item.price)
                }
            )

            console.log(
                'CREATE ITEM:',
                itemResponse.data
            )

            if (!itemResponse.data.success) {
                throw new Error(
                    itemResponse.data.message ||
                    'Gagal menambahkan item.'
                )
            }
        }

        // ==============================
        // 3. SUBMIT PENGAJUAN
        // ==============================

        const submitResponse = await api.patch(
            `/asset-requests/${createdRequest.id}/submit`
        )

        console.log(
            'SUBMIT REQUEST:',
            submitResponse.data
        )

        if (!submitResponse.data.success) {
            throw new Error(
                submitResponse.data.message ||
                'Gagal submit pengajuan.'
            )
        }

        // ==============================
        // 4. BERHASIL
        // ==============================

        const result =
            submitResponse.data.data

        alert(
            `Pengajuan berhasil dibuat!\n\nNomor Memo: ${result.memo_number}`
        )

        // ==============================
        // 5. RESET FORM
        // ==============================

        submission.value = {
            nomor: '',
            kepadaYth: '',
            dariBagian: '',
            purpose: '',
            notes: ''
        }

        submissionItems.value = [
            createEmptyItem()
        ]

        // ==============================
        // 6. REFRESH DATA
        // ==============================

        await getAssetRequests()

    } catch (error) {

        console.error(
            'SAVE SUBMISSION ERROR:',
            error
        )

        if (error.response) {

            console.error(
                'BACKEND RESPONSE:',
                error.response.data
            )

            alert(
                error.response.data?.message ||
                'Terjadi kesalahan pada server.'
            )

        } else {

            alert(
                error.message ||
                'Gagal menyimpan pengajuan.'
            )
        }

    } finally {

        loadingAssets.value = false

    }
}

async function getAssetRequests() {
    try {
        loadingAssets.value = true
        assetError.value = ''

        const response = await api.get('/asset-requests')

        console.log(
            'GET ASSET REQUESTS:',
            response.data
        )

        if (response.data.success) {

            assets.value =
                response.data.data.map(mapAssetRequest)

        } else {

            assetError.value =
                response.data.message ||
                'Gagal mengambil data pengajuan.'
        }

    } catch (error) {

        console.error(
            'GET ASSET REQUESTS ERROR:',
            error
        )

        assetError.value =
            error.response?.data?.message ||
            'Tidak dapat mengambil data pengajuan.'

    } finally {
        loadingAssets.value = false
    }
}

function mapAssetRequest(item) {
  return {
    id: item.id,

    memoNumber:
      item.memo_number ||
      item.memoNumber ||
      '-',

    kepadaYth:
      item.recipient_name ||
      item.kepadaYth ||
      '-',

    pic:
      item.sender_name ||
      item.requester?.full_name ||
      item.requester?.username ||
      '-',

    purchaseDate:
      formatDate(item.request_date),

    status:
      mapStatus(item.status),

    statusClass:
      getStatusClass(item.status),

    purpose:
      item.subject ||
      item.purpose ||
      '-',

    notes:
      item.notes ||
      '-',

    items: (
      item.items ||
      item.request_items ||
      []
    ).map((detail) => ({
      id: detail.id,

      name:
        detail.item_name ||
        detail.name ||
        '-',

      category:
        detail.unit_name ||
        detail.category?.category_name ||
        detail.category ||
        '-',

      quantity:
        Number(detail.quantity || 1),

      price:
        Number(
          detail.actual_amount ??
          detail.requested_amount ??
          detail.price ??
          0
        )
    }))
  }
}

function mapStatus(status) {
  const map = {
    approved: 'Disetujui',
    rejected: 'Tidak Disetujui',
    submitted: 'Pending',
    pending: 'Pending',
    draft: 'Pending'
  }

  return map[status] || status || 'Pending'
}

function getStatusClass(status) {
  const map = {
    approved: 'bg-success-subtle text-success',
    rejected: 'bg-danger-subtle text-danger',
    submitted: 'bg-warning-subtle text-warning',
    pending: 'bg-warning-subtle text-warning',
    draft: 'bg-secondary-subtle text-secondary'
  }

  return map[status] || 'bg-warning-subtle text-warning'
}

function formatDate(date) {
  if (!date) return '-'

  const parsed = new Date(date)

  if (Number.isNaN(parsed.getTime())) {
    return date
  }

  return parsed.toLocaleDateString('id-ID', {
    day: '2-digit',
    month: 'short',
    year: 'numeric'
  })
}

const activities = [
  { text: 'Laptop, Drone', date: '12-01-2024'},
  { text: 'Printer', date: '12-01-2024'},
  { text: 'Meja Kerja', date: '12-01-2024'},
  { text: 'Kursi Kantor', date: '12-01-2024'}
]

function formatPrice(value) {
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    maximumFractionDigits: 0
  }).format(value)
}

function itemNames(submissionEntry) {
  return submissionEntry.items.map((i) => i.name).join(', ')
}

function itemCategories(submissionEntry) {
  const unique = [...new Set(submissionEntry.items.map((i) => i.category))]
  return unique.join(', ')
}

function submissionEntryTotal(submissionEntry) {
  return submissionEntry.items.reduce(
    (sum, i) => sum + (Number(i.price) || 0) * (Number(i.quantity) || 1),
    0
  )
}

function viewAssetDetail(submissionEntry) {
  selectedAsset.value = submissionEntry
}

function editAsset(submissionEntry) {
  assetBeingEdited.value = submissionEntry
  editingAsset.value = {
    ...submissionEntry,
    items: submissionEntry.items.map((i) => ({ ...i }))
  }
}

function addEditingItem() {
  editingAsset.value.items.push({ name: '', category: '', price: '' })
}

function removeEditingItem(index) {
  if (editingAsset.value.items.length === 1) return
  editingAsset.value.items.splice(index, 1)
}

function syncStatusClass() {
  const map = {
    Disetujui: 'bg-success-subtle text-success',
    'Tidak Disetujui': 'bg-danger-subtle text-danger',
    Pending: 'bg-warning-subtle text-warning'
  }
  editingAsset.value.statusClass = map[editingAsset.value.status] || 'bg-warning-subtle text-warning'
}

function saveEditAsset() {
  const targetIndex = assets.value.indexOf(assetBeingEdited.value)
  if (targetIndex !== -1) {
    assets.value[targetIndex] = { ...editingAsset.value }
  }
  editingAsset.value = null
  assetBeingEdited.value = null
}

function cancelEditAsset() {
  editingAsset.value = null
  assetBeingEdited.value = null
}

function deleteAsset(submissionEntry) {
  assetToDelete.value = submissionEntry
}

function confirmDeleteAsset() {
  const targetIndex = assets.value.indexOf(assetToDelete.value)
  if (targetIndex !== -1) {
    assets.value.splice(targetIndex, 1)
  }
  assetToDelete.value = null
}

function cancelDeleteAsset() {
  assetToDelete.value = null
}


function goToDaftarAset() {
  router.push('/daftaraset')
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
</script>

<style scoped>
.dashboard-page {
  min-height: 100vh;
  background: linear-gradient(180deg, #f8fafc 0%, #ffffff 100%);
}

.dashboard-shell {
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

.dashboard-content {
  display: flex;
  flex-direction: column;
}

.submission-history {
  order: 1;
}

.submission-form {
  order: 2;
}

.btn-eye,
.btn-edit,
.btn-delete {
  border: none;
  background: transparent;
  width: 34px;
  height: 34px;
  border-radius: 10px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: all 0.2s ease;
}

.btn-eye:hover {
  color: #2563eb;
  background: #eff6ff;
}

.btn-edit:hover {
  color: #0f766e;
  background: #ecfeff;
}

.btn-delete:hover {
  color: #dc2626;
  background: #fef2f2;
}

.btn-delete:disabled {
  opacity: 0.35;
  cursor: not-allowed;
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
  overflow-y: auto;
}

.asset-modal-card {
  width: 100%;
  max-width: 620px;
  background: #fff;
  border-radius: 16px;
  padding: 24px;
  box-shadow: 0 16px 40px rgba(15, 23, 42, 0.2);
  max-height: 90vh;
  overflow-y: auto;
}

.asset-modal-card-sm {
  max-width: 420px;
}

.btn-equal {
  min-width: 92px;
}

@media (max-width: 768px) {
  .dashboard-shell {
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
