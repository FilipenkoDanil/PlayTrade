<script>
import axios from "axios";

export default {
    name: "List",
    data() {
        return {
            games: [],
            trashedGames: [],
            headers: [
                {title: '#', value: 'id'},
                {title: 'Название', value: 'title'},
            ]
        }
    },

    methods: {
        getGames() {
            axios.get('api/games')
                .then(r => {
                    this.games = r.data.data
                })
        },

        getTrashedGames() {
            axios.get('api/trash/games')
                .then(r => {
                    this.trashedGames = r.data
                })
        },

        openGame(_, item) {
            this.$router.push({name: 'game.edit', params: {id: item.item.id}})
        }
    },

    mounted() {
        this.getGames()
        this.getTrashedGames()
    }
}
</script>

<template>
    <v-card class="mb-6">
        <v-card-title>Активные игры</v-card-title>
        <v-divider></v-divider>
        <v-data-table
            :items="games"
            :headers="headers"
            class="elevation-1"
            hover
            items-per-page="100"
            @click:row="openGame"
        ></v-data-table>
    </v-card>

    <v-card v-if="trashedGames.length">
        <v-card-title>Удалённые игры</v-card-title>
        <v-divider></v-divider>
        <v-data-table
            :items="trashedGames"
            :headers="headers"
            class="elevation-1"
            hover
            @click:row="openGame"
        >
            <template v-slot:item="{ item }">
                <tr class="text-grey-darken-1">
                    <td>{{ item.id }}</td>
                    <td>{{ item.title }}</td>
                </tr>
            </template>
        </v-data-table>
    </v-card>
</template>

<style scoped>

</style>
