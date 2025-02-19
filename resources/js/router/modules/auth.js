export default [
    {
        path: '/login',
        name: 'login',
        component: () => import('@/pages/Auth/Login.vue')
    },
    {
        path: '/register',
        name: 'register',
        component: () => import('@/pages/Auth/Register.vue')
    }
]
