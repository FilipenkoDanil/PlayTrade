<script>
export default {
    name: "Edit",

    data() {
        return {
            game: {},
            categories: []
        }
    },

    methods: {
        getGame() {
            axios.get(`api/games/${this.$route.params.id}`)
                .then(r => {
                    this.game = r.data.data
                })
        },

        updateGame() {
            axios.patch(`api/games/${this.game.id}`, {
                title: this.game.title
            })
                .then(() => console.log('OK'))
        },

        deleteGame() {
            axios.delete(`api/games/${this.game.id}`)
                .then(() => this.$router.push({name: 'game.list'}))
        }
    },

    mounted() {
        this.getGame()
    }
}
</script>

<template>
    <v-card>
        <v-card-title>Редактирование игры</v-card-title>

        <v-card-text>
            <v-text-field v-model="game.title" label="Title"></v-text-field>

            <span class="text-h6">Категории: </span>
            <v-chip-group>
                <v-chip v-for="cat in game.categories" :to="{name: 'category.edit', params: {id: cat.id}}">{{ cat.title }}</v-chip>
            </v-chip-group>
        </v-card-text>

        <v-card-actions>
            <v-btn @click="updateGame" color="primary">Сохранить изменения</v-btn>
            <v-spacer></v-spacer>
            <v-btn @click="deleteGame" color="red">Удалить игру</v-btn>
        </v-card-actions>
    </v-card>
</template>

<style scoped>

</style>
