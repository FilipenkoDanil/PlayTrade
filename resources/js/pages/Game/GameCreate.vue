<script>
export default {
    name: "Create",
    data() {
        return {
            title: "",
            loading: false,
            snack: false,
            snackMessage: "",
            snackColor: "success",
            units: [],
            categories: [],
            servers: [],
            attributes: [],
            categoryTypes: [
                { value: '1', text: 'Аккаунты' },
                { value: '2', text: 'Валюта' },
            ]
        }
    },
    mounted() {
        this.getUnits()
    },
    methods: {
        getUnits() {
            axios.get("/api/units")
                .then(response => {
                    this.units = response.data.data
                })
                .catch(() => {
                    this.snackMessage = "Ошибка при загрузке единиц измерения"
                    this.snackColor = "error"
                    this.snack = true;
                });
        },
        addCategory() {
            this.categories.push({ title: "", unit_id: null, type: '1' })
        },
        addServer() {
            this.servers.push({ title: "" })
        },
        addAttribute() {
            this.attributes.push({ title: "" })
        },
        removeCategory(index) {
            this.categories.splice(index, 1)
        },
        removeServer(index) {
            this.servers.splice(index, 1)
        },
        removeAttribute(index) {
            this.attributes.splice(index, 1)
        },
        createGame() {
            if (!this.title.trim()) {
                this.snackMessage = "Название игры не может быть пустым";
                this.snackColor = "error";
                this.snack = true;
                return;
            }

            this.loading = true;

            const data = {
                title: this.title,
                categories: this.categories.filter(c => c.title.trim()),
                servers: this.servers.filter(s => s.title.trim()),
                attributes: this.attributes.filter(a => a.title.trim())
            };

            axios.post("/api/games", data)
                .then(() => {
                    this.snackMessage = "Игра и связанные данные успешно добавлены";
                    this.snackColor = "success";
                    this.resetForm();
                })
                .catch(r => {
                    this.snackMessage = r.response.data.message || "Ошибка при добавлении";
                    this.snackColor = "error";
                })
                .finally(() => {
                    this.loading = false;
                    this.snack = true;
                });
        },
        resetForm() {
            this.title = "";
            this.categories = [{ title: "", unit_id: null, type: 'other' }];
            this.servers = [{ title: "" }];
            this.attributes = [{ title: "" }];
        }
    }
};
</script>

<template>
    <v-card class="pa-4 mx-auto" max-width="600">
        <v-card-title class="text-h5">Добавить игру</v-card-title>
        <v-card-text>
            <v-text-field v-model="title" label="Название игры" placeholder="Введите название" clearable></v-text-field>

            <v-divider class="my-4"></v-divider>

            <h3>Категории</h3>
            <div v-for="(category, index) in categories" :key="'cat-' + index" class="d-flex align-center mb-2">
                <v-text-field v-model="category.title" label="Название категории" class="mr-2"></v-text-field>
                <v-select v-model="category.type" :items="categoryTypes" item-value="value" item-title="text" label="Тип категории" class="mr-2"></v-select>
                <v-select v-model="category.unit_id" :items="units" item-value="id" item-title="title" label="Ед. изм." class="mr-2"></v-select>
                <v-btn icon size="small" color="red" @click="removeCategory(index)">
                    <v-icon>mdi-delete</v-icon>
                </v-btn>
            </div>
            <v-btn color="primary" size="small" @click="addCategory">Добавить категорию</v-btn>

            <v-divider class="my-4"></v-divider>

            <h3>Серверы</h3>
            <div v-for="(server, index) in servers" :key="'srv-' + index" class="d-flex align-center mb-2">
                <v-text-field v-model="server.title" label="Название сервера" class="mr-2"></v-text-field>
                <v-btn icon size="small" color="red" @click="removeServer(index)">
                    <v-icon>mdi-delete</v-icon>
                </v-btn>
            </div>
            <v-btn color="primary" size="small" @click="addServer">Добавить сервер</v-btn>

            <v-divider class="my-4"></v-divider>

            <h3>Атрибуты</h3>
            <div v-for="(attribute, index) in attributes" :key="'attr-' + index" class="d-flex align-center mb-2">
                <v-text-field v-model="attribute.title" label="Название атрибута" class="mr-2"></v-text-field>
                <v-btn icon size="small" color="red" @click="removeAttribute(index)">
                    <v-icon>mdi-delete</v-icon>
                </v-btn>
            </div>
            <v-btn color="primary" size="small" @click="addAttribute">Добавить атрибут</v-btn>
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
