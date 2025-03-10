export default [
    {
        path: '/disputes',
        name: 'dispute',
        component: () => import('@/pages/Disputes/DisputeList.vue')
    },
    {
        path: '/disputes/:id',
        name: 'dispute.info',
        component: () => import('@/pages/Disputes/DisputeInfo.vue')
    }
]
