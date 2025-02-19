export default [
    {
        path: '/servers/create',
        name: 'server.create',
        component: () => import('@/pages/Server/ServerCreate.vue')
    },
    {
        path: '/servers/:id/edit',
        name: 'server.edit',
        component: () => import('@/pages/Server/ServerEdit.vue')
    }
]
