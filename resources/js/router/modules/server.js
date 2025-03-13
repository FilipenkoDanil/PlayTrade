export default [
    {
        path: '/servers/create',
        name: 'server.create',
        component: () => import('@/pages/Server/ServerCreate.vue'),
        meta: { requiresAuth: true }
    },
    {
        path: '/servers/:id/edit',
        name: 'server.edit',
        component: () => import('@/pages/Server/ServerEdit.vue'),
        meta: { requiresAuth: true }
    }
]
