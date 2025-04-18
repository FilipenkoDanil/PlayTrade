export default [
    {
        path: '/offer/:id',
        name: 'offer',
        component: () => import('@/pages/Offer/OfferShow.vue')
    },
    {
        path: '/user/offers',
        name: 'user.offers',
        component: () => import('@/pages/Offer/OfferList.vue'),
        meta: { requiresAuth: true }
    },
    {
        path: '/categories/:categoryId/offers/create',
        name: 'offer.create',
        component: () => import('@/pages/Offer/OfferCreate.vue'),
        meta: { requiresAuth: true }
    },
    {
        path: '/user/offers/:id/edit',
        name: 'offer.edit',
        component: () => import('@/pages/Offer/OfferEdit.vue'),
        meta: { requiresAuth: true }
    }
]
