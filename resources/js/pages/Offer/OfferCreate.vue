<script>
export default {
    name: "Create",

    data() {
        return {
            category: {},
            title: '',
            description: '',
            autoMessage: '',
            price: null,
            amount: null,
            selectedServer: null,
            selectedAttributes: [],
            errors: {},
            snackbar: false, // Управление видимостью снекбара
            snackbarMessage: '', // Сообщение снекбара
            snackbarColor: 'success' // Цвет снекбара (success, error и т.д.)
        }
    },

    methods: {
        getCategory() {
            axios.get(`api/categories/${this.$route.params.categoryId}`)
                .then(r => {
                    this.category = r.data.data;

                    if (this.category.attributes?.length) {
                        this.selectedAttributes = this.category.attributes.reduce((acc, attr) => {
                            acc[attr.id] = ""
                            return acc
                        }, {});
                    }
                })
        },

        createOffer() {
            this.errors = {};

            const payload = {
                price: this.price,
                amount: this.amount,
                category_id: this.category.id
            };

            if (this.category.type === 1) {
                payload.title = this.title
                payload.description = this.description
                payload.auto_message = this.autoMessage

                const attributesArray = Object.entries(this.selectedAttributes)
                    .filter(([_, value]) => value && value.trim() !== "")
                    .map(([id, value]) => ({id: Number(id), value}))

                if (attributesArray.length) {
                    payload.attributes = attributesArray
                }
            }

            if (this.selectedServer) {
                payload.server_id = this.selectedServer
            }

            axios.post('api/offers', payload)
                .then(() => {
                    // Очищаем форму
                    this.title = '';
                    this.description = '';
                    this.autoMessage = '';
                    this.price = null;
                    this.amount = null;
                    this.selectedServer = null;
                    this.selectedAttributes = {};

                    this.snackbarMessage = 'Предложение успешно добавлено!';
                    this.snackbarColor = 'success';
                    this.snackbar = true;
                })
                .catch(err => {
                    if (err.response && err.response.data.errors) {
                        this.errors = err.response.data.errors;

                        this.snackbarMessage = 'Ошибка при добавлении предложения. Проверьте данные.';
                        this.snackbarColor = 'error';
                        this.snackbar = true;
                    }
                });
        }
    },

    mounted() {
        this.getCategory()
    }
}
</script>

<template>
    <v-card class="pa-4 mx-auto" max-width="600">
        <v-btn @click="this.$router.push({name: 'category', params: {id: this.$route.params.categoryId}})"
               prepend-icon="mdi-arrow-left"
               variant="text"
               color="primary">
            Назад
        </v-btn>

        <v-skeleton-loader v-if="!category.id" type="heading" class="mb-2"/>
        <v-card-title v-else>Добавление предложения</v-card-title>

        <v-card-text>
            <v-skeleton-loader v-if="!category.id" type="paragraph" class="mb-4"/>

            <v-row v-if="category.type === 1">
                <v-col cols="12">
                    <v-skeleton-loader v-if="!category.id" type="text"/>
                    <v-text-field
                        v-else
                        v-model="title"
                        label="Название"
                        :error-messages="errors.title"
                    ></v-text-field>
                </v-col>
            </v-row>

            <v-row>
                <v-col cols="6">
                    <v-skeleton-loader v-if="!category.id" type="text"/>
                    <v-text-field
                        v-else
                        v-model="amount"
                        :label="'Количество ' + category.unit?.title"
                        type="number"
                        min="1"
                        :error-messages="errors.amount"
                    ></v-text-field>
                </v-col>
                <v-col cols="6">
                    <v-skeleton-loader v-if="!category.id" type="text"/>
                    <v-text-field
                        v-else
                        v-model="price"
                        :label="'Цена/' + category.unit?.title"
                        type="number"
                        min="1"
                        suffix="₴"
                        :error-messages="errors.price"
                    ></v-text-field>
                </v-col>
                <v-col v-if="category.servers?.length" cols="12">
                    <v-skeleton-loader v-if="!category.id" type="text"/>
                    <v-select
                        v-else
                        v-model="selectedServer"
                        :items="category.servers"
                        label="Сервер"
                        item-title="title"
                        item-value="id"
                        :error-messages="errors.server_id"
                    ></v-select>
                </v-col>
            </v-row>

            <v-skeleton-loader v-if="!category.id" type="text" class="mb-2"/>
            <v-textarea
                v-if="category.type === 1"
                v-model="description"
                label="Описание"
                :error-messages="errors.description"
            ></v-textarea>

            <v-skeleton-loader v-if="!category.id" type="text" class="mb-2"/>
            <v-textarea
                v-if="category.type === 1"
                v-model="autoMessage"
                label="Автоосообщение"
                :error-messages="errors.auto_message"
            ></v-textarea>

            <div v-if="category.attributes?.length && category.type === 1">
                <p class="text-subtitle-1 font-weight-medium">Атрибуты</p>
                <v-row>
                    <v-col cols="4" v-for="attr in category.attributes" :key="attr.id">
                        <v-skeleton-loader v-if="!category.id" type="text"/>
                        <v-text-field
                            v-else
                            v-model="selectedAttributes[attr.id]"
                            :label="attr.title"
                            :error-messages="errors['attributes']"
                        ></v-text-field>
                    </v-col>
                </v-row>
            </div>
        </v-card-text>

        <v-card-actions>
            <v-skeleton-loader v-if="!category.id" type="button"/>
            <v-btn v-else @click="createOffer" color="primary">Сохранить изменения</v-btn>
        </v-card-actions>
    </v-card>

    <!-- Снекбар для уведомлений -->
    <v-snackbar
        v-model="snackbar"
        :color="snackbarColor"
        timeout="3000"
    >
        {{ snackbarMessage }}
    </v-snackbar>
</template>

<style>

</style>
