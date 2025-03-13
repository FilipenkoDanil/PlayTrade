export default [
    {
        path: '/user/orders',
        name: 'user.orders',
        component: () => import('@/pages/User/Orders.vue'),
        meta: { requiresAuth: true }
    },
    {
        path: '/user/sales',
        name: 'user.sales',
        component: () => import('@/pages/User/Sales.vue'),
        meta: { requiresAuth: true }
    },
    {
        path: '/user/deals/:id',
        name: 'user.deal',
        component: () => import('@/pages/User/Deal.vue'),
        meta: { requiresAuth: true }
    },
    {
        path: '/user/transactions',
        name: 'user.transactions',
        component: () => import('@/pages/User/Transactions.vue'),
        meta: { requiresAuth: true }
    },
    {
        path: '/user/:id/profile',
        name: 'user.profile',
        component: () => import('@/pages/User/Profile.vue')
    }
]
