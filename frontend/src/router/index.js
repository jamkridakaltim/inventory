import { createRouter, createWebHistory } from 'vue-router'

import login from '../pages/auth/login.vue'

import dashboard from '../pages/auth/Dashboard/dashboard.vue'
import profile from '../pages/auth/profile.vue'
import direktur from '../pages/auth/direktur.vue'
import report from '../pages/auth/report.vue'
import inputaset from '../pages/auth/inputaset.vue'
import daftaraset from '../pages/auth/daftaraset.vue'


const routes = [

    // =========================
    // LOGIN
    // =========================
    {
        path: '/',
        redirect: '/login'
    },

    {
        path: '/login',
        component: login,
        meta: {
            guest: true
        }
    },


    // =========================
    // DASHBOARD
    // Admin + Staff
    // =========================
    {
        path: '/dashboard',
        component: dashboard,
        meta: {
            requiresAuth: true,
            roles: [1, 2]
        }
    },


    // =========================
    // PROFILE
    // Semua role
    // =========================
    {
        path: '/profile',
        component: profile,
        meta: {
            requiresAuth: true,
            roles: [1, 2, 3]
        }
    },


    // =========================
    // PERSETUJUAN
    // Direktur
    // =========================
    {
        path: '/direktur',
        component: direktur,
        meta: {
            requiresAuth: true,
            roles: [3]
        }
    },


    // =========================
    // LAPORAN / MEMO
    // Admin
    // =========================
    {
        path: '/report',
        component: report,
        meta: {
            requiresAuth: true,
            roles: [1]
        }
    },


    // =========================
    // INPUT ASET
    // Admin
    // =========================
    {
        path: '/inputaset',
        component: inputaset,
        meta: {
            requiresAuth: true,
            roles: [1]
        }
    },


    // =========================
    // DAFTAR ASET
    // Admin + Direktur
    // =========================
    {
        path: '/daftaraset',
        component: daftaraset,
        meta: {
            requiresAuth: true,
            roles: [1, 3]
        }
    },




]


const router = createRouter({

    history: createWebHistory(),

    routes

})


// =====================================================
// ROUTE GUARD
// =====================================================

router.beforeEach((to) => {

    const token = localStorage.getItem('token')

    const userData = localStorage.getItem('user')

    let user = null

    try {
        user = userData
            ? JSON.parse(userData)
            : null
    } catch (error) {

        console.error(
            'Gagal membaca data user:',
            error
        )

        localStorage.removeItem('user')

        user = null
    }


    // ==========================================
    // HALAMAN YANG MEMBUTUHKAN LOGIN
    // ==========================================

    if (to.meta.requiresAuth) {

        if (!token || !user) {

            return '/login'

        }

    }


    // ==========================================
    // HALAMAN LOGIN
    // Jika sudah login, jangan kembali ke login
    // ==========================================

    if (to.meta.guest) {

    if (token && user) {

        const roleId = Number(user?.role_id)

        const roleHome = {
            1: '/dashboard',
            2: '/dashboard',
            3: '/direktur'
        }

        return roleHome[roleId] || '/login'
    }

}


    // ==========================================
    // CEK ROLE
    // ==========================================

    if (to.meta.roles) {

        const allowedRoles = to.meta.roles

        const roleId = Number(user?.role_id)


        if (!allowedRoles.includes(roleId)) {

            console.warn(
                `Akses ditolak. Role ${roleId} tidak memiliki akses ke ${to.path}`
            )

            // Arahkan ke halaman yang sesuai role
            const roleHome = {
    1: '/dashboard',
    2: '/dashboard',
    3: '/direktur'
}

if (roleHome[roleId]) {
    return roleHome[roleId]
}

            // Kalau role tidak dikenal
            localStorage.removeItem('token')
            localStorage.removeItem('user')

            return '/login'

        }

    }


    return true

})


export default router