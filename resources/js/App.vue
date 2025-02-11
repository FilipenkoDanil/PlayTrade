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
            <v-app-bar-title>PlayTrade</v-app-bar-title>
            <v-spacer></v-spacer>
            <v-btn v-if="isAuth" @click="logout" icon="mdi-logout"></v-btn>
        </v-app-bar>

        <v-navigation-drawer
            v-model="drawer"
            temporary>
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
