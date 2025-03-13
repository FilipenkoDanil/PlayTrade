<script>
import { reloadRolesAndPermissions } from 'laravel-permission-to-vuejs'
export default {
    inject: ['isAuth', 'setAuth'],
    name: 'Login',
    data() {
        return {
            email: '',
            password: '',
        }
    },

    methods: {
        login() {
            axios.get('sanctum/csrf-cookie')
                .then(() => {
                    axios.post('login', {
                        email: this.email,
                        password: this.password
                    })
                        .then(() => {
                            axios.get('api/user')
                                .then(r => {
                                    localStorage.setItem('isAuth', 'true')
                                    localStorage.setItem('userId', r.data.id)
                                    this.setAuth(true);
                                    reloadRolesAndPermissions()
                                    this.$router.push({name: 'home'})
                                })
                        })
                })
        },
    }

}
</script>

<template>
    <v-container class="fill-height d-flex justify-center align-center">
        <v-card width="400" elevation="4">
            <v-card-title class="text-h5">Вход</v-card-title>
            <v-card-text>
                <v-form>
                    <v-text-field
                        v-model="email"
                        label="Email"
                        type="email"
                        required
                    ></v-text-field>

                    <v-text-field
                        v-model="password"
                        label="Пароль"
                        type="password"
                        required
                    ></v-text-field>

                    <v-btn @click.prevent="login" type="submit" color="primary" block class="mt-2">
                        Войти
                    </v-btn>

                    <v-btn :to="{name: 'register'}" variant="text" size="x-small" block
                           class="text-caption text-indigo-lighten-2 mt-2">
                        Нет аккаунта?
                    </v-btn>
                </v-form>
            </v-card-text>
        </v-card>
    </v-container>
</template>

<style scoped>

</style>
