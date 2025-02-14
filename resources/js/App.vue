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
            temporary>
            <v-list>
                <v-list-item :to="{name: 'orders'}">Мои покупки</v-list-item>
                <v-list-item :to="{name: 'sales'}">Мои продажи</v-list-item>
                <v-divider></v-divider>
                <v-list-item :to="{name: 'game.create'}">Create Game</v-list-item>
                <v-list-item :to="{name: 'game.list'}">List Games</v-list-item>
                <v-divider></v-divider>
                <v-list-item :to="{name: 'category.create'}">Create Category</v-list-item>
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
