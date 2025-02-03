import { createRouter, createWebHistory } from "vue-router";

const routes = [
    {
        path: '/',
        name: 'home',
        component: () => import('@/pages/Home.vue')
    },
    {
        path: '/category',
        name: 'category',
        component: () => import('@/pages/Category.vue')
    }
]

export default createRouter({
    history: createWebHistory(),
    routes
})
