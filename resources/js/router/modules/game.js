export default [
    {
        path: '/games/list',
        name: 'game.list',
        component: () => import('@/pages/Game/GameList.vue')
    },
    {
        path: '/games/create',
        name: 'game.create',
        component: () => import('@/pages/Game/GameCreate.vue')
    },
    {
        path: '/games/:id/edit',
        name: 'game.edit',
        component: () => import('@/pages/Game/GameEdit.vue')
    }
]
