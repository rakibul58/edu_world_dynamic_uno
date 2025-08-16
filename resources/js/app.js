import { createApp } from 'vue'
import { createRouter, createWebHistory } from 'vue-router'
import { createStore } from 'vuex'
import axios from 'axios'
import App from './App.vue'

// Configure axios
axios.defaults.baseURL = '/api'
axios.defaults.withCredentials = true

// Get CSRF token
const token = document.head.querySelector('meta[name="csrf-token"]')
if (token) {
    axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content
}

// Import components
import Home from './views/Home.vue'
import Admin from './views/Admin.vue'
import Login from './views/Login.vue'

// Store modules
import siteModule from './store/modules/site'
import adminModule from './store/modules/admin'
import authModule from './store/modules/auth'

// Create Vuex store
const store = createStore({
    modules: {
        site: siteModule,
        admin: adminModule,
        auth: authModule
    }
})

// Create router
const routes = [
    { path: '/', name: 'Home', component: Home },
    { path: '/admin', name: 'Admin', component: Admin, meta: { requiresAuth: true } },
    { path: '/login', name: 'Login', component: Login },
]

const router = createRouter({
    history: createWebHistory(),
    routes,
    scrollBehavior(to, from, savedPosition) {
        if (savedPosition) {
            return savedPosition
        } else {
            return { top: 0 }
        }
    }
})

// Navigation guard
router.beforeEach(async (to, from, next) => {
    const requiresAuth = to.matched.some(record => record.meta.requiresAuth)
    const token = localStorage.getItem('auth_token')
    
    if (requiresAuth) {
        if (token) {
            try {
                await store.dispatch('auth/getUser')
                next()
            } catch (error) {
                store.dispatch('auth/logout')
                next('/login')
            }
        } else {
            next('/login')
        }
    } else {
        next()
    }
})

// Create and mount app
const app = createApp(App)
app.use(store)
app.use(router)

// Global axios interceptor
axios.interceptors.request.use(
    config => {
        const token = localStorage.getItem('auth_token')
        if (token) {
            config.headers.Authorization = `Bearer ${token}`
        }
        return config
    },
    error => {
        return Promise.reject(error)
    }
)

axios.interceptors.response.use(
    response => response,
    error => {
        if (error.response?.status === 401) {
            store.dispatch('auth/logout')
            router.push('/login')
        }
        return Promise.reject(error)
    }
)

app.mount('#app')