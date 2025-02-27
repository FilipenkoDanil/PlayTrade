export default [
    {
        path: '/user/orders',
        name: 'user.orders',
        component: () => import('@/pages/User/Orders.vue')
    },
    {
        path: '/user/sales',
        name: 'user.sales',
        component: () => import('@/pages/User/Sales.vue')
    },
    {
        path: '/user/deals/:id',
        name: 'user.deal',
        component: () => import('@/pages/User/Deal.vue')
    }
]
