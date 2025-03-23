<script>
import {reloadRolesAndPermissions} from 'laravel-permission-to-vuejs'

export default {
    name: "App",

    data() {
        return {
            drawer: false,
            isAuth: localStorage.getItem('isAuth'),
            userId: localStorage.getItem('userId'),
        }
    },

    provide() {
        return {
            isAuth: this.isAuth,
            setAuth: this.setAuth
        }
    },

    methods: {
        setAuth(value) {
            this.isAuth = value.isAuth
            this.userId = value.userId
        },

        checkAuth() {
            if (localStorage.getItem('isAuth') && localStorage.getItem('userId')) {
                axios.get('api/user', {
                    ignoreRedirect: true
                })
                    .then(r => {
                        this.isAuth = true
                        this.userId = r.data.id
                    })
                    .catch(() => {
                        this.isAuth = false
                        this.userId = null
                        localStorage.removeItem('isAuth');
                        localStorage.removeItem('userId');
                    });
            }
        },

        logout() {
            axios.post('logout')
                .then(() => {
                    localStorage.removeItem('isAuth')
                    localStorage.removeItem('userId')
                    this.setAuth(false);
                    reloadRolesAndPermissions()
                    this.$router.push({name: 'login'})
                })
        }
    },

    mounted() {
        this.checkAuth()
    }
}
</script>

<template>
    <v-app>
        <v-app-bar>
            <v-app-bar-nav-icon v-if="isAuth" @click.stop="drawer = !drawer"></v-app-bar-nav-icon>
            <router-link :to="{name: 'home'}" class="v-toolbar-title text-decoration-none text-white">PlayTrade
            </router-link>
            <v-spacer></v-spacer>
            <v-btn v-if="isAuth" @click="logout" icon="mdi-logout"></v-btn>
            <v-btn v-else @click="this.$router.push({name: 'login'})" icon="mdi-login"></v-btn>
        </v-app-bar>

        <v-navigation-drawer
            v-model="drawer"
            temporary
            color="background"
            v-if="isAuth"
        >
            <v-list dense nav>
                <v-list-subheader class="text-uppercase text-caption font-weight-bold text-grey-darken-1">
                    Аккаунт
                </v-list-subheader>
                <v-list-item
                    :to="{ name: 'user.profile', params: {id: userId}}"
                    prepend-icon="mdi-account"
                    title="Мой профиль"
                ></v-list-item>
                <v-list-item
                    :to="{ name: 'user.orders' }"
                    prepend-icon="mdi-cart"
                    title="Мои покупки"
                ></v-list-item>
                <v-list-item
                    :to="{ name: 'user.sales' }"
                    prepend-icon="mdi-currency-usd"
                    title="Мои продажи"
                ></v-list-item>
                <v-list-item
                    :to="{ name: 'user.offers' }"
                    prepend-icon="mdi-tag"
                    title="Мои предложения"
                ></v-list-item>
                <v-list-item
                    :to="{ name: 'user.chats' }"
                    prepend-icon="mdi-message"
                    title="Сообщения"
                ></v-list-item>
                <v-list-item
                    :to="{ name: 'user.transactions' }"
                    prepend-icon="mdi-wallet"
                    title="Финансы"
                ></v-list-item>

                <v-divider class="my-2"></v-divider>

                <div v-if="is('moder')">
                    <v-list-subheader class="text-uppercase text-caption font-weight-bold text-grey-darken-1">
                        Модератор
                    </v-list-subheader>
                    <v-list-item
                        :to="{ name: 'dispute' }"
                        prepend-icon="mdi-format-list-bulleted"
                        title="Активные споры"
                    ></v-list-item>

                    <v-list-item
                        :to="{ name: 'server.create' }"
                        prepend-icon="mdi-server"
                        title="Создать сервер"
                    ></v-list-item>

                    <v-list-item
                        :to="{ name: 'withdrawal.list' }"
                        prepend-icon="mdi-finance"
                        title="Заявки на вывод"
                    ></v-list-item>

                    <v-list-item
                        :to="{ name: 'category.create' }"
                        prepend-icon="mdi-folder-plus"
                        title="Создать категорию"
                    ></v-list-item>

                    <v-list-item
                        :to="{ name: 'game.create' }"
                        prepend-icon="mdi-plus-circle"
                        title="Создать игру"
                    ></v-list-item>
                    <v-list-item
                        :to="{ name: 'game.list' }"
                        prepend-icon="mdi-format-list-bulleted"
                        title="Список игр"
                    ></v-list-item>
                </div>
            </v-list>
        </v-navigation-drawer>


        <v-main>
            <v-container>
                <router-view :key="$route.path"></router-view>
            </v-container>
        </v-main>
    </v-app>
</template>

<style scoped>

</style>
