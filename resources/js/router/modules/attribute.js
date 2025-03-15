export default [
    {
        path: '/attributes/create',
        name: 'attribute.create',
        component: () => import('@/pages/Attribute/AttributeCreate.vue'),
        meta: { requiresAuth: true }
    },
    {
        path: '/attributes/:id/edit',
        name: 'attribute.edit',
        component: () => import('@/pages/Attribute/AttributeEdit.vue'),
        meta: { requiresAuth: true }
    }
]
