<script>
export default {
    name: "Home",
    data() {
        return {
            search: "",
            games: [],
            colors: ["red", "blue", "green", "orange", "purple", "teal"],
        }
    },

    mounted() {
        this.getGames()
    },

    methods: {
        getGames() {
            axios.get('api/games')
                .then(res => {
                    this.games = res.data.data
                })
        }
    },

    computed: {
        filteredGames() {
            return this.games.filter(game => game.title.toLowerCase().includes(this.search.toLowerCase()))
        }
    }
}
</script>

<template>
    <v-row justify="center">
        <v-col cols="12" sm="6" md="4">
            <v-text-field
                v-model="search"
                prepend-inner-icon="mdi-magnify"
                placeholder="Search games"
            ></v-text-field>
        </v-col>
    </v-row>

    <v-row>
        <v-col v-for="game in filteredGames" cols="12" sm="6" md="4" lg="3">
            <v-card class="mx-auto">
                <v-img src="https://picsum.photos/800" height="200px" cover></v-img>
                <v-card-title>{{ game.title }}</v-card-title>
                <v-card-text>
                    <v-btn
                        v-for="(category, idx) in game.categories"
                        :to="{name: 'category', params: {id: category.id}}"
                        rounded
                        size="small"
                        :color="colors[idx % colors.length]"
                        variant="outlined"
                        class="mr-1 mb-1"
                    >
                        {{ category.title }}
                    </v-btn>
                </v-card-text>
            </v-card>
        </v-col>
    </v-row>
</template>

<style scoped>

</style>
