<template>
    <div class="login-page d-flex flex-column align-items-center">
        <div class="header-logo text-center w-100 mb-3">
            <img src="/jamkrida-kaltim.png" alt="Jamkrida Kaltim" class="top-logo" />
        </div>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-md-8 col-lg-5">
                    <div class="card shadow-sm p-4 border-0 login-card">
                        <div class="text-center mb-3">
                            <h2 class="mb-1">PENGAJUAN ASET</h2>
                            <div class="login-subtitle">LOGIN</div>
                        </div>

                        <p class="text-muted">Access your account now and enjoy our services</p>

                        <div
    v-if="errors.general"
    class="alert alert-danger"
    role="alert"
>
    {{ errors.general }}
</div>

                        <form @submit.prevent="onSubmit" novalidate>
                            <div class="mb-3">
                                <label for="email" class="form-label visually-hidden">Email</label>
                                <div class="input-group">
                                    <span class="input-group-text" id="email-addon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true"><path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v.217l-8 4.8-8-4.8V4z"/><path d="M0 6.383V12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V6.383l-7.555 4.533a1 1 0 0 1-1.09 0L0 6.383z"/></svg>
                                    </span>
                                    <input
    id="email"
    name="email"
    v-model="email"
    type="text"
    class="form-control form-control-lg"
    placeholder="Email atau Username"
    :class="{ 'is-invalid': errors.email }"
    aria-describedby="email-addon"
    autocomplete="username"
    required
/>
                                    <div class="invalid-feedback">{{ errors.email }}</div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label visually-hidden">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text" id="password-addon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true"><path d="M8 1a3 3 0 0 0-3 3v2H4a2 2 0 0 0-2 2v4a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2v-4a2 2 0 0 0-2-2h-1V4a3 3 0 0 0-3-3zM6 4a2 2 0 1 1 4 0v2H6V4z"/></svg>
                                    </span>
                                    <input
                                        id="password"
                                        name="password"
                                        :type="showPassword ? 'text' : 'password'"
                                        v-model="password"
                                        class="form-control form-control-lg"
                                        placeholder="Password"
                                        :class="{'is-invalid': errors.password}"
                                        aria-describedby="password-addon"
                                        autocomplete="current-password"
                                        required
                                    />
                                    <button class="btn btn-outline-secondary" type="button" @click="showPassword = !showPassword" :aria-pressed="showPassword" :aria-label="showPassword ? 'Hide password' : 'Show password'">{{ showPassword ? 'Hide' : 'Show' }}</button>
                                    <div class="invalid-feedback">{{ errors.password }}</div>
                                </div>
                            </div>

                            <!-- Button color is developer-controlled. Change `buttonColor` default in the script section. -->

                            <button
    :style="{
        backgroundColor: buttonColor,
        borderColor: buttonColor,
        color: textColor
    }"
    class="btn btn-lg w-100 mb-3"
    type="submit"
    :disabled="loading"
>
    {{ loading ? 'Logging in...' : 'Login' }}
</button>
                        </form>

                        <div class="text-center mt-3 text-muted small">
    Made With ❤️ JAMKRIDA KALTIM
</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useRouter } from 'vue-router'
import api from '../../api/axios'

const router = useRouter()

const email = ref('')
const password = ref('')
const showPassword = ref(false)

const errors = ref({
    email: '',
    password: '',
    general: ''
})

const buttonColor = ref('#1c7d58')
const loading = ref(false)

const textColor = computed(() => {
    const hex = buttonColor.value.replace('#', '')

    const r = parseInt(hex.substring(0, 2), 16)
    const g = parseInt(hex.substring(2, 4), 16)
    const b = parseInt(hex.substring(4, 6), 16)

    const lum =
        (0.2126 * r + 0.7152 * g + 0.0722 * b) / 255

    return lum > 0.6 ? '#000000' : '#ffffff'
})

function validate() {
    let ok = true

    errors.value.email = ''
    errors.value.password = ''
    errors.value.general = ''

    /*
     * Backend bisa login menggunakan:
     * - username
     * - email
     *
     * Jadi frontend tidak perlu memaksa format email.
     */
    if (!email.value.trim()) {
        errors.value.email = 'Email atau username wajib diisi'
        ok = false
    }

    if (!password.value) {
        errors.value.password = 'Password wajib diisi'
        ok = false
    }

    return ok
}

async function onSubmit() {
    if (!validate()) return

    loading.value = true

    errors.value.general = ''

    try {
        const response = await api.post('/login', {
            login: email.value.trim(),
            password: password.value
        })

        console.log('Login response:', response.data)

        if (response.data.success) {

            /*
             * Simpan token Sanctum
             */
            localStorage.setItem(
                'token',
                response.data.token
            )

            /*
             * Simpan data user
             */
            localStorage.setItem(
                'user',
                JSON.stringify(response.data.user)
            )

            /*
             * Tetap simpan email/username untuk kompatibilitas
             * dengan kode frontend lama jika ada.
             */
            localStorage.setItem(
                'loggedInUserEmail',
                email.value.trim()
            )

            /*
             * Masuk ke dashboard
             */
            router.push('/dashboard')
        }

    } catch (error) {

        console.error('Login error:', error)

        if (error.response) {

            /*
             * Error dari Laravel
             */

            const status = error.response.status
            const message = error.response.data?.message

            if (status === 401) {
                errors.value.password =
                    message || 'Password salah.'
            }

            else if (status === 404) {
                errors.value.email =
                    message || 'Username atau email tidak ditemukan.'
            }

            else if (status === 403) {
                errors.value.general =
                    message || 'Akun tidak aktif.'
            }

            else {
                errors.value.general =
                    message || 'Terjadi kesalahan saat login.'
            }

        } else {

            /*
             * Backend tidak dapat dihubungi
             */
            errors.value.general =
                'Tidak dapat terhubung ke server.'
        }

    } finally {
        loading.value = false
    }
}

function setColor(c) {
    buttonColor.value = c
}
</script>

<style scoped>
.login-page{min-height:100vh;background:linear-gradient(180deg,#f8fafc 0%,#fff 40%);padding:40px 0}
.login-card{border-radius:12px}
.login-logo{width:110px}
.top-logo{height:85px;object-fit:contain;margin:0 auto 9px;display:inline-block}
.input-with-icon .icon-left{position:absolute;left:12px;top:50%;transform:translateY(-50%);font-size:18px;color:#6c757d}
.input-with-icon .form-control{padding-left:44px}
.toggle-eye{position:absolute;right:10px;top:50%;transform:translateY(-50%);}
.form-label.visually-hidden{position:absolute !important;clip:rect(1px,1px,1px,1px);padding:0;border:0;height:1px;width:1px;overflow:hidden}
.google-btn{border-radius:8px;border:1px solid #e6e6e6}
.google-btn svg{vertical-align:middle}
.login-subtitle{color:#000 !important}
</style>