export default [
    {
        path: '/games/list',
        name: 'game.list',
        component: () => import('@/pages/Game/GameList.vue'),
        meta: { requiresAuth: true }
    },
    {
        path: '/games/create',
        name: 'game.create',
        component: () => import('@/pages/Game/GameCreate.vue'),
        meta: { requiresAuth: true }
    },
    {
        path: '/games/:id/edit',
        name: 'game.edit',
        component: () => import('@/pages/Game/GameEdit.vue'),
        meta: { requiresAuth: true }
    }
]
