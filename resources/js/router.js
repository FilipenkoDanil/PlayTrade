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

export default createRouter({
    history: createWebHistory(),
    routes
})
