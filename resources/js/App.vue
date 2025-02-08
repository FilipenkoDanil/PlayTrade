<script>
import axios from "axios";

export default {
    name: "App",

    data() {
        return {
            drawer: false
        }
    },

    methods: {
        logout() {
            axios.post('logout')
                .then(() => {
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
            <v-btn @click="logout" icon="mdi-account"></v-btn>
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
