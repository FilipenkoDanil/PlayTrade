<script>
import axios from "axios";

export default {
    name: "App",

    data() {
        return {
            drawer: false,
            isAuth: localStorage.getItem('isAuth')
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
            this.isAuth = value;
        },

        logout() {
            axios.post('logout')
                .then(() => {
                    localStorage.removeItem('isAuth')
                    this.setAuth(false);
                    this.$router.push({name: 'login'})
                })
        }
    }
}
</script>

<template>
    <v-app>
        <v-app-bar>
            <v-app-bar-nav-icon @click.stop="drawer = !drawer"></v-app-bar-nav-icon>
            <router-link :to="{name: 'home'}" class="v-toolbar-title text-decoration-none text-white">PlayTrade</router-link>
            <v-spacer></v-spacer>
            <v-btn v-if="isAuth" @click="logout" icon="mdi-logout"></v-btn>
        </v-app-bar>

        <v-navigation-drawer
            v-model="drawer"
            temporary
            color="background"
        >
            <v-list dense nav>
                <v-list-subheader class="text-uppercase text-caption font-weight-bold text-grey-darken-1">
                    Аккаунт
                </v-list-subheader>
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

                <v-divider class="my-2"></v-divider>

                <v-list-subheader class="text-uppercase text-caption font-weight-bold text-grey-darken-1">
                    Игры
                </v-list-subheader>
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

                <v-divider class="my-2"></v-divider>

                <v-list-subheader class="text-uppercase text-caption font-weight-bold text-grey-darken-1">
                    Категории
                </v-list-subheader>
                <v-list-item
                    :to="{ name: 'category.create' }"
                    prepend-icon="mdi-folder-plus"
                    title="Создать категорию"
                ></v-list-item>

                <v-divider class="my-2"></v-divider>

                <v-list-subheader class="text-uppercase text-caption font-weight-bold text-grey-darken-1">
                    Сервера
                </v-list-subheader>
                <v-list-item
                    :to="{ name: 'server.create' }"
                    prepend-icon="mdi-server"
                    title="Создать сервер"
                ></v-list-item>
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
