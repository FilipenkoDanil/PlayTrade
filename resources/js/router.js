import { createRouter, createWebHistory } from "vue-router";

const routes = [
    {
        path: '/',
        name: 'home',
        component: () => import('@/pages/Home.vue')
    },
    {
        path: '/category/:id',
        name: 'category',
        component: () => import('@/pages/Category.vue')
    },
    {
        path: '/offer/:id',
        name: 'offer',
        component: () => import('@/pages/Offer.vue')
    },
    {
        path: '/login',
        name: 'login',
        component: () => import('@/pages/Login.vue')
    },
    {
        path: '/register',
        name: 'register',
        component: () => import('@/pages/Register.vue')
    }
]

const router = createRouter({
    history: createWebHistory(),
    routes
})

router.beforeEach((to, from, next) => {
    const isAuth = localStorage.getItem('isAuth')

    if (isAuth) {
        if (to.name === 'login' || to.name === 'register') {
            return next({
                name: 'home'
            })
        }
    }

    next()
})

export default router
