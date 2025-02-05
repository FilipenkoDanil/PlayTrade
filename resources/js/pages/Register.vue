<script>
export default {
    name: "Register"
}
</script>

<template>
    <v-container class="fill-height d-flex justify-center align-center">
        <v-card width="400" elevation="4">
            <v-card-title class="text-h5">Зарегистрироваться</v-card-title>
            <v-card-text>
                <v-form>
                    <v-text-field
                        label="Email"
                        type="email"
                        required
                    ></v-text-field>

                    <v-text-field
                        label="Пароль"
                        type="password"
                        required
                    ></v-text-field>

                    <v-text-field
                        label="Повторный пароль"
                        type="password"
                        required
                    ></v-text-field>

                    <v-btn type="submit" color="primary" block class="mt-2">
                        Подтвердить
                    </v-btn>

                    <v-btn :to="{name: 'login'}" variant="text" size="x-small" block class="text-caption text-indigo-lighten-2 mt-2">
                        Есть аккаунт?
                    </v-btn>
                </v-form>
            </v-card-text>
        </v-card>
    </v-container>
</template>

<style scoped>

</style>
