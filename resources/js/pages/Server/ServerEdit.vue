<script>
export default {
    name: "ServerEdit",

    data() {
        return {
            title: '',
            game: null,

            loading: false,
            snack: false,
            snackMessage: '',
            snackColor: 'success',
        }
    },

    methods: {
        updateServer() {
            if (!this.title.trim()) {
                this.snackMessage = "Название категории не может быть пустым";
                this.snackColor = "error";
                this.snack = true;
                return;
            }

            this.loading = true;

            axios.put(`api/servers/${this.$route.params.id}`, {
                title: this.title,
            })
                .then(() => {
                    this.snackMessage = "Сервер успешно обновлен";
                    this.snackColor = "success";
                })
                .catch(r => {
                    this.snackMessage = r.response.data.message;
                    this.snackColor = "red";
                })
                .finally(() => {
                    this.loading = false;
                    this.snack = true;
                });
        },

        getServer() {
            axios.get(`api/servers/${this.$route.params.id}`)
                .then(r => {
                    this.title = r.data.data.title
                    this.game = r.data.data.game
                })
        },

    },

    mounted() {
        this.getServer()
    }
}
</script>

<template>
    <v-card class="pa-4  mx-auto" max-width="500">
        <v-card-title class="text-h5">Обновить сервер</v-card-title>
        <v-card-text>
            <v-text-field v-model="title" label="Название сервера" placeholder="Введите название"
                          clearable></v-text-field>
            <v-select v-model="game"  item-title="title" item-value="id" label="Игра" disabled></v-select>

        </v-card-text>
        <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn :loading="loading" color="primary" @click="updateServer">Сохранить</v-btn>
        </v-card-actions>
    </v-card>

    <v-snackbar v-model="snack" :color="snackColor" timeout="3000">{{ snackMessage }}</v-snackbar>
</template>

<style scoped>

</style>
