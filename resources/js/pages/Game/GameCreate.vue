<script>
export default {
    name: "Create",
    data() {
        return {
            title: "",
            loading: false,
            snack: false,
            snackMessage: "",
            snackColor: "success"
        };
    },
    methods: {
        createGame() {
            if (!this.title.trim()) {
                this.snackMessage = "Название игры не может быть пустым";
                this.snackColor = "error";
                this.snack = true;
                return;
            }

            this.loading = true;

            axios.post("/api/games", { title: this.title })
                .then(() => {
                    this.snackMessage = "Игра успешно добавлена";
                    this.snackColor = "success";
                    this.title = "";
                })
                .catch(r => {
                    this.snackMessage = r.response.data.message;
                    this.snackColor = "red";
                })
                .finally(() => {
                    this.loading = false;
                    this.snack = true;
                });
        }
    }
}
</script>

<template>
    <v-card class="pa-4  mx-auto" max-width="500">
        <v-card-title class="text-h5">Добавить игру</v-card-title>
        <v-card-text>
            <v-text-field v-model="title" label="Название игры" placeholder="Введите название"
                          clearable></v-text-field>
        </v-card-text>
        <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn :loading="loading" color="primary" @click="createGame">Добавить</v-btn>
        </v-card-actions>
    </v-card>

    <v-snackbar v-model="snack" :color="snackColor" timeout="3000">{{ snackMessage }}</v-snackbar>
</template>

<style scoped>

</style>
