<template>
  <div class="profile-page">
    <div class="profile-shell">
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
              <p class="fw-bold mb-0">AKUN SAYA</p>
              <h2 class="fw-bold mb-0">Profile</h2>
            </div>
            <button class="btn btn-outline-secondary mt-3 mt-md-0" @click="router.push('/dashboard')">
              Kembali ke Dashboard
            </button>
          </div>

          <div class="card shadow-sm border-0">
            <div class="card-body">
              <div class="d-flex flex-column align-items-center text-center mb-4">
                <div class="avatar-wrapper">
                  <img v-if="profile.photoUrl" :src="profile.photoUrl" alt="Foto profil" class="avatar-img" />
                  <svg v-else xmlns="http://www.w3.org/2000/svg" width="44" height="44" viewBox="0 0 24 24" fill="currentColor">
                    <circle cx="12" cy="8" r="4" />
                    <path d="M12 14c-5.33 0-8 2.667-8 8h16c0-5.333-2.67-8-8-8z" />
                  </svg>
                  <button class="avatar-edit-btn" type="button" title="Ganti foto" @click="triggerFileInput">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <path d="M12 20h9" />
                      <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4Z" />
                    </svg>
                  </button>
                  <input ref="fileInput" type="file" accept="image/*" class="d-none" @change="onPhotoChange" />
                </div>
                <h5 class="fw-bold mt-3 mb-0">{{ profile.name || 'Nama Pengguna' }}</h5>
                <p class="text-muted small mb-0">@{{ profile.username || 'username' }}</p>
              </div>

              <div class="row g-3 justify-content-center">
                <div class="col-12 col-md-8">
                  <label class="form-label fw-semibold">Nama</label>
                  <input v-model="profile.name" type="text" class="form-control" placeholder="Nama lengkap" />
                </div>
                <div class="col-12 col-md-8">
                  <label class="form-label fw-semibold">Username</label>
                  <input v-model="profile.username" type="text" class="form-control" placeholder="Username" />
                </div>
                <div class="col-12 col-md-8">
                  <label class="form-label fw-semibold">Email</label>
                  <input v-model="profile.email" type="email" class="form-control" placeholder="nama@email.com" />
                </div>

                <div class="col-12 col-md-8">
                  <hr class="my-2" />
                </div>

                <div class="col-12 col-md-8">
                  <label class="form-label fw-semibold">Password</label>
                  <input v-model="profile.password" type="password" class="form-control" placeholder="Masukkan password saat ini" />
                </div>
                <div class="col-12 col-md-8">
                  <label class="form-label fw-semibold">Password Baru</label>
                  <input v-model="profile.newPassword" type="password" class="form-control" placeholder="Masukkan password baru" />
                </div>

                <div class="col-12 col-md-8 d-flex justify-content-end">
                  <button class="btn btn-primary btn-sm" @click="saveProfile">Simpan Perubahan</button>
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
import { onMounted, ref } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()
const activeMenu = ref('Profile')

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

const fileInput = ref(null)

const profile = ref({
  name: 'Admin',
  username: 'admin',
  email: '',
  photoUrl: '',
  password: '',
  newPassword: ''
})

onMounted(() => {
  const savedEmail = localStorage.getItem('loggedInUserEmail')
  if (savedEmail) {
    profile.value.name = savedEmail.split('@')[0]
    profile.value.username = savedEmail.split('@')[0]
    profile.value.email = savedEmail
  }
})

function triggerFileInput() {
  fileInput.value.click()
}

function onPhotoChange(event) {
  const file = event.target.files[0]
  if (!file) return
  const reader = new FileReader()
  reader.onload = (e) => {
    profile.value.photoUrl = e.target.result
  }
  reader.readAsDataURL(file)
}

function saveProfile() {
  if (profile.value.newPassword && !profile.value.password) {
    alert('Masukkan password saat ini untuk mengganti password')
    return
  }
  alert('Profile berhasil disimpan')
  profile.value.password = ''
  profile.value.newPassword = ''
}
</script>

<style scoped>
.profile-page {
  min-height: 100vh;
  background: linear-gradient(180deg, #f8fafc 0%, #ffffff 100%);
}

.profile-shell {
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

.avatar-wrapper {
  position: relative;
  width: 96px;
  height: 96px;
  border-radius: 50%;
  background: #eff6ff;
  color: #2563eb;
  display: flex;
  align-items: center;
  justify-content: center;
  overflow: hidden;
}

.avatar-img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.avatar-edit-btn {
  position: absolute;
  bottom: -2px;
  right: -2px;
  width: 30px;
  height: 30px;
  border-radius: 50%;
  border: 2px solid #fff;
  background: #2563eb;
  color: #fff;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
}

.avatar-edit-btn:hover {
  background: #1d4ed8;
}

@media (max-width: 768px) {
  .profile-shell {
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