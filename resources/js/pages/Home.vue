<script>
export default {
    name: "Home",
    data() {
        return {
            search: "",
            games: [],
            colors: ["red", "blue", "green", "orange", "purple", "teal"],
            loading: true,
        };
    },

    mounted() {
        this.getGames();
    },

    methods: {
        getGames() {
            axios.get("api/games").then((res) => {
                this.games = res.data.data;
                this.loading = false;
            });
        },
    },

    computed: {
        filteredGames() {
            return this.games.filter((game) =>
                game.title.toLowerCase().includes(this.search.toLowerCase())
            );
        },
    },
}
</script>

<template>
    <v-container>
        <v-row justify="center" class="mb-4">
            <v-col cols="12" sm="8" md="6">
                <v-text-field
                    v-model="search"
                    prepend-inner-icon="mdi-magnify"
                    placeholder="Search games"
                    variant="outlined"
                    dense
                    hide-details
                    class="custom-search"
                ></v-text-field>
            </v-col>
        </v-row>

        <v-row>
            <template v-if="loading">
                <v-col v-for="n in 16" :key="n" cols="12" sm="6" md="4" lg="3">
                    <v-skeleton-loader type="card"></v-skeleton-loader>
                </v-col>
            </template>
            <template v-else>
                <v-col v-for="game in filteredGames" :key="game.id" cols="12" sm="6" md="4" lg="3">
                    <v-card class="game-card">
                        <v-img :src="game.image || 'https://picsum.photos/800'" height="200px" cover></v-img>
                        <v-card-title class="font-weight-bold">{{ game.title }}</v-card-title>
                        <v-card-text>
                            <v-chip
                                v-for="(category, idx) in game.categories"
                                :key="category.id"
                                :to="{ name: 'category', params: { id: category.id } }"
                                :color="colors[idx % colors.length]"
                                class="mr-1 mb-1 text-white"
                                label
                            >
                                {{ category.title }}
                            </v-chip>
                        </v-card-text>
                    </v-card>
                </v-col>
            </template>
        </v-row>
    </v-container>
</template>

<style scoped>
.custom-search {
    border-radius: 8px;
}

.game-card {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
}

.game-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
}
</style>
