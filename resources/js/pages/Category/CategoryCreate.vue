<script>
export default {
    name: "Create",
    data() {
        return {
            title: '',
            description: '',
            game_id: null,
            unit_id: null,

            loading: false,
            snack: false,
            snackMessage: '',
            snackColor: 'success',

            units: [],
            servers: [],
            attributes: [],
            games: [],
            selectedServers: [],
            selectedAttributes: []
        };
    },
    methods: {
        createCategory() {
            if (!this.title.trim()) {
                this.snackMessage = "Название категории не может быть пустым";
                this.snackColor = "error";
                this.snack = true;
                return;
            }

            this.loading = true;

            axios.post("api/categories", {
                title: this.title,
                description: this.description,
                game_id: this.game_id,
                unit_id: this.unit_id,
                attributes: this.selectedAttributes,
                servers: this.selectedServers
            })
                .then(() => {
                    this.snackMessage = "Категория успешно добавлена";
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

        getUnits() {
            axios.get('api/units')
                .then(r => this.units = r.data.data)
        },

        getGames() {
            axios.get('api/games')
                .then(r => this.games = r.data.data)
        },

        getServers() {
            axios.get('api/servers')
                .then(r => this.servers = r.data.data)
        },

        getAttributes() {
            axios.get('api/attributes')
                .then(r => this.attributes = r.data.data)
        }
    },
    computed: {
        filteredServers() {
            if (!this.game_id) return [];
            return this.servers.filter(server => server.game_id === this.game_id);
        },

        filteredAttributes() {
            if (!this.game_id) return [];
            return this.attributes.filter(attribute => attribute.game_id === this.game_id);
        }
    },
    watch: {
        game_id() {
            this.selectedServers = [];
        }
    },
    mounted() {
        this.getGames()
        this.getUnits()
        this.getServers()
        this.getAttributes()
    }
}
</script>

<template>
    <v-card class="pa-4  mx-auto" max-width="500">
        <v-card-title class="text-h5">Добавить категорию</v-card-title>
        <v-card-text>
            <v-text-field v-model="title" label="Название категории" placeholder="Введите название"
                          clearable></v-text-field>
            <v-textarea v-model="description" label="Описание категории"></v-textarea>
            <v-select v-model="game_id" :items="games" item-title="title" item-value="id" label="Игра"></v-select>
            <v-select v-if="filteredServers.length" v-model="selectedServers" :items="filteredServers"
                      item-title="title" item-value="id" label="Сервера" chips multiple clearable></v-select>
            <v-select v-if="filteredAttributes.length" v-model="selectedAttributes" :items="filteredAttributes"
                      item-title="title" item-value="id" label="Атрибуты" multiple chips clearable></v-select>
            <v-select v-model="unit_id" :items="units" item-title="title" item-value="id"
                      label="Единицы измерения"></v-select>

        </v-card-text>
        <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn :loading="loading" color="primary" @click="createCategory">Добавить</v-btn>
        </v-card-actions>
    </v-card>

    <v-snackbar v-model="snack" :color="snackColor" timeout="3000">{{ snackMessage }}</v-snackbar>
</template>

<style scoped>

</style>
