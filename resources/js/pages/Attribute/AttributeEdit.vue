<script>
export default {
    name: "AttributeEdit",

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
        updateAttribute() {
            if (!this.title.trim()) {
                this.snackMessage = "Название атрибута не может быть пустым";
                this.snackColor = "error";
                this.snack = true;
                return;
            }

            this.loading = true;

            axios.put(`api/attributes/${this.$route.params.id}`, {
                title: this.title,
            })
                .then(() => {
                    this.snackMessage = "Атрибут успешно обновлен";
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

        deleteAttribute() {
            axios.delete(`api/attributes/${this.$route.params.id}`)
                .then(() => this.$router.push({'name': 'game.edit', params: {id: this.game.id}}))
        },

        getAttribute() {
            axios.get(`api/attributes/${this.$route.params.id}`)
                .then(r => {
                    this.title = r.data.data.title
                    this.game = r.data.data.game
                })
        },

    },

    mounted() {
        this.getAttribute()
    }
}
</script>

<template>
    <v-card class="pa-4  mx-auto" max-width="500">
        <v-card-title class="text-h5">Обновить атрибут</v-card-title>
        <v-card-text>
            <v-text-field v-model="title" label="Название атрибута" placeholder="Введите название"
                          clearable></v-text-field>
            <v-select v-model="game"  item-title="title" item-value="id" label="Игра" disabled></v-select>

        </v-card-text>
        <v-card-actions>
            <v-btn :loading="loading" color="primary" @click="updateAttribute">Сохранить</v-btn>
            <v-spacer></v-spacer>
            <v-btn color="red" @click="deleteAttribute">Удалить атрибут</v-btn>
        </v-card-actions>
    </v-card>

    <v-snackbar v-model="snack" :color="snackColor" timeout="3000">{{ snackMessage }}</v-snackbar>
</template>

<style scoped>

</style>
