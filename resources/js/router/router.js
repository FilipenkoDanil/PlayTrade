import { createRouter, createWebHistory } from "vue-router";
import authRoutes from "./modules/auth";
import categoryRoutes from "./modules/category";
import gameRoutes from "./modules/game";
import offerRoutes from "./modules/offer";
import userRoutes from "./modules/user";
import serverRoutes from "./modules/server.js"
import chatRoutes from "./modules/chat.js"
import disputeRoutes from "./modules/dispute.js"

const routes = [
    {
        path: '/',
        name: 'home',
        component: () => import('@/pages/Home.vue')
    },
    ...authRoutes,
    ...categoryRoutes,
    ...gameRoutes,
    ...offerRoutes,
    ...userRoutes,
    ...serverRoutes,
    ...chatRoutes,
    ...disputeRoutes
]

const router = createRouter({
    history: createWebHistory(),
    routes
})

router.beforeEach((to, from, next) => {
    const isAuth = localStorage.getItem('isAuth')
    const requiresAuth = to.matched.some(record => record.meta.requiresAuth)

    if (requiresAuth && !isAuth) {
        next({ name: 'login' })
    } else if (isAuth && (to.name === 'login' || to.name === 'register')) {
        next({ name: 'home' })
    } else {
        next();
    }
})

export default router
