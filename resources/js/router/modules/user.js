export default [
    {
        path: '/orders',
        name: 'orders',
        component: () => import('@/pages/User/Orders.vue')
    },
    {
        path: '/sales',
        name: 'sales',
        component: () => import('@/pages/User/Sales.vue')
    }
]
