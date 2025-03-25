import { checkModerRole } from "@/router/helpers/auth.js";

export default [
    {
        path: '/disputes',
        name: 'dispute',
        component: () => import('@/pages/Disputes/DisputeList.vue'),
        meta: { requiresAuth: true },
        beforeEnter: (to, from, next) => {
            checkModerRole() ? next() : next('/')
        }
    },
    {
        path: '/disputes/:id',
        name: 'dispute.info',
        component: () => import('@/pages/Disputes/DisputeInfo.vue'),
        meta: { requiresAuth: true },
        beforeEnter: (to, from, next) => {
            checkModerRole() ? next() : next('/')
        }
    }
]
