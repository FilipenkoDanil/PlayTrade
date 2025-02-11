<script>
import axios from "axios";

export default {
    name: "Register",
    data() {
        return {
            email: '',
            name: '',
            password: '',
            conf_password: '',
        }
    },

    methods: {
        register() {
            axios.get('sanctum/csrf-cookie')
                .then(() => {
                    axios.post('register', {
                        email: this.email,
                        name: this.name,
                        password: this.password,
                        password_confirmation: this.conf_password
                    })
                        .then(() => {
                            localStorage.setItem('isAuth', 'true')
                            this.$router.push({name: 'home'})
                        })
                })
        },
    }
}
</script>

<template>
    <v-container class="fill-height d-flex justify-center align-center">
        <v-card width="400" elevation="4">
            <v-card-title class="text-h5">Зарегистрироваться</v-card-title>
            <v-card-text>
                <v-form>
                    <v-text-field
                        v-model="email"
                        label="Email"
                        type="email"
                        required
                    ></v-text-field>

                    <v-text-field
                        v-model="name"
                        label="Name"
                        type="text"
                        required
                    ></v-text-field>

                    <v-text-field
                        v-model="password"
                        label="Пароль"
                        type="password"
                        required
                    ></v-text-field>

                    <v-text-field
                        v-model="conf_password"
                        label="Повторный пароль"
                        type="password"
                        required
                    ></v-text-field>

                    <v-btn @click.prevent="register" type="submit" color="primary" block class="mt-2">
                        Подтвердить
                    </v-btn>

                    <v-btn :to="{name: 'login'}" variant="text" size="x-small" block
                           class="text-caption text-indigo-lighten-2 mt-2">
                        Есть аккаунт?
                    </v-btn>
                </v-form>
            </v-card-text>
        </v-card>
    </v-container>
</template>

<style scoped>

</style>
