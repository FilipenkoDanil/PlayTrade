import { checkModerRole } from "@/router/helpers/auth.js";

export default [
    {
        path: '/attributes/create',
        name: 'attribute.create',
        component: () => import('@/pages/Attribute/AttributeCreate.vue'),
        meta: { requiresAuth: true },
        beforeEnter: (to, from, next) => {
            checkModerRole() ? next() : next('/')
        }
    },
    {
        path: '/attributes/:id/edit',
        name: 'attribute.edit',
        component: () => import('@/pages/Attribute/AttributeEdit.vue'),
        meta: { requiresAuth: true },
        beforeEnter: (to, from, next) => {
            checkModerRole() ? next() : next('/')
        }
    }
]
