<script>
export default {
    name: "AttributeCreate",

    data() {
        return {
            title: '',
            games: [],
            game_id: null,

            loading: false,
            snack: false,
            snackMessage: '',
            snackColor: 'success',
        }
    },

    methods: {
        createAttribute() {
            if (!this.title.trim()) {
                this.snackMessage = "Название атрибута не может быть пустым";
                this.snackColor = "error";
                this.snack = true;
                return;
            }

            this.loading = true;

            axios.post("api/attributes", {
                title: this.title,
                game_id: this.game_id,
            })
                .then(() => {
                    this.snackMessage = "Атрибут успешно добавлен";
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
        },

        getGames() {
            axios.get('api/games')
                .then(r => this.games = r.data.data)
        }
    },

    mounted() {
        this.getGames()
    }
}
</script>

<template>
    <v-card class="pa-4  mx-auto" max-width="500">
        <v-card-title class="text-h5">Добавить атрибут</v-card-title>
        <v-card-text>
            <v-text-field v-model="title" label="Название атрибута" placeholder="Введите название"
                          clearable></v-text-field>
            <v-select v-model="game_id" :items="games" item-title="title" item-value="id" label="Игра"></v-select>

        </v-card-text>
        <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn :loading="loading" color="primary" @click="createAttribute">Добавить</v-btn>
        </v-card-actions>
    </v-card>

    <v-snackbar v-model="snack" :color="snackColor" timeout="3000">{{ snackMessage }}</v-snackbar>
</template>

<style scoped>

</style>
