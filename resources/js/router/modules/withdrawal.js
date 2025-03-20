export default [
    {
        path: '/withdrawals',
        name: 'withdrawal.list',
        component: () => import('@/pages/Withdrawal/WithdrawalList.vue'),
        meta: { requiresAuth: true }
    }
]
