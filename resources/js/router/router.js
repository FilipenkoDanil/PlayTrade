import { createRouter, createWebHistory } from "vue-router";
import authRoutes from "./modules/auth";
import categoryRoutes from "./modules/category";
import gameRoutes from "./modules/game";
import offerRoutes from "./modules/offer";
import userRoutes from "./modules/user";

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
    ...userRoutes
]

const router = createRouter({
    history: createWebHistory(),
    routes
})

router.beforeEach((to, from, next) => {
    const isAuth = localStorage.getItem('isAuth')

    if (isAuth) {
        if (to.name === 'login' || to.name === 'register') {
            return next({
                name: 'home'
            })
        }
    }

    next()
})

export default router
