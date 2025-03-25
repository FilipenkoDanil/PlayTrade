<script>
export default {
    name: "Edit",
    data() {
        return {
            title: '',
            description: '',
            game_id: null,
            unit_id: null,
            type: 1,

            loading: false,
            snack: false,
            snackMessage: '',
            snackColor: 'success',

            units: [],
            servers: [],
            attributes: [],
            games: [],
            selectedServers: [],
            selectedAttributes: [],
            selectedGame: {}
        };
    },
    methods: {
        updateCategory() {
            if (!this.title.trim()) {
                this.snackMessage = "Название категории не может быть пустым";
                this.snackColor = "error";
                this.snack = true;
                return;
            }

            this.loading = true;

            const payload = {
                title: this.title,
                description: this.description,
                game_id: this.game_id,
                unit_id: this.unit_id,
                servers: this.selectedServers,
                type: this.type
            }

            if (this.type === 1) {
                payload.attributes = this.selectedAttributes
            }

            axios.put(`api/categories/${this.$route.params.id}`, payload)
                .then(() => {
                    this.snackMessage = "Категория успешно обновлена";
                    this.snackColor = "success";
                })
                .catch(r => {
                    this.snackMessage = r.response.data.message || "Ошибка обновления категории";
                    this.snackColor = "error";
                })
                .finally(() => {
                    this.loading = false;
                    this.snack = true;
                });
        },

        getCategory() {
            axios.get(`api/categories/${this.$route.params.id}`)
                .then(r => {
                    this.title = r.data.data.title;
                    this.description = r.data.data.description;
                    this.selectedGame = r.data.data.game
                    this.type = r.data.data.type
                    this.game_id = r.data.data.game.id
                    this.unit_id = r.data.data.unit_id;
                    this.selectedServers = r.data.data.servers.map(s => s.id);
                    this.selectedAttributes = r.data.data.attributes.map(a => a.id);
                })
                .catch(err => {
                    this.snackMessage = "Ошибка загрузки категории";
                    this.snackColor = "error";
                    this.snack = true;
                    console.error(err);
                });
        },

        deleteCategory() {
            axios.delete(`api/categories/${this.$route.params.id}`)
                .then(() => this.$router.push({name: 'home'}))
        },

        getGames() {
            axios.get('api/games')
                .then(r => this.games = r.data.data)
        },

        getUnits() {
            axios.get('api/units')
                .then(r => this.units = r.data.data)
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

    mounted() {
        this.getGames()
        this.getServers()
        this.getUnits()
        this.getAttributes()
        this.getCategory()
    }
}
</script>

<template>
    <v-card class="pa-4  mx-auto" max-width="500">
        <v-card-title class="text-h5">Редактировать категорию</v-card-title>
        <v-card-text>
            <v-select v-model="type" :items="[ { title: 'Аккаунты и предметы', value: 1 }, { title: 'Валюта', value: 2 } ]"
                      item-title="title" item-value="value" label="Тип категории"></v-select>
            <v-text-field v-model="title" label="Название категории" placeholder="Введите название"
                          clearable></v-text-field>
            <v-textarea v-model="description" label="Описание категории"></v-textarea>

            <v-select v-model="game_id" :items="games" item-title="title" item-value="id" label="Игра"
                      disabled></v-select>

            <v-select v-if="filteredServers.length" v-model="selectedServers" :items="filteredServers"
                      item-title="title" item-value="id" label="Сервера" chips multiple clearable></v-select>

            <v-select v-if="filteredAttributes.length && type === 1" v-model="selectedAttributes" :items="filteredAttributes"
                      item-title="title" item-value="id" label="Атрибуты" multiple chips clearable></v-select>

            <v-select v-model="unit_id" :items="units" item-title="title" item-value="id"
                      label="Единицы измерения"></v-select>

        </v-card-text>
        <v-card-actions>
            <v-btn :loading="loading" color="primary" @click="updateCategory">Сохранить</v-btn>
            <v-spacer></v-spacer>
            <v-btn @click="deleteCategory" color="red">Удалить категорию</v-btn>
        </v-card-actions>
    </v-card>

    <v-snackbar v-model="snack" :color="snackColor" timeout="3000">{{ snackMessage }}</v-snackbar>
</template>

<style scoped>

</style>
