import { checkModerRole } from "@/router/helpers/auth.js";

export default [
    {
        path: '/categories/:id',
        name: 'category',
        component: () => import('@/pages/Category/CategoryShow.vue')
    },
    {
        path: '/categories/create',
        name: 'category.create',
        component: () => import('@/pages/Category/CategoryCreate.vue'),
        meta: { requiresAuth: true },
        beforeEnter: (to, from, next) => {
            checkModerRole() ? next() : next('/')
        }
    },
    {
        path: '/categories/:id/edit',
        name: 'category.edit',
        component: () => import('@/pages/Category/CategoryEdit.vue'),
        meta: { requiresAuth: true },
        beforeEnter: (to, from, next) => {
            checkModerRole() ? next() : next('/')
        }
    }
]
