<script>

export default {
    name: "Create",

    data() {
        return {
            category: {},
            title: '',
            description: '',
            price: null,
            amount: null,
            selectedServer: null,
            selectedAttributes: []
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
            const payload = {
                title: this.title,
                description: this.description,
                price: this.price,
                amount: this.amount,
                category_id: this.category.id
            }

            if (this.selectedServer) {
                payload.server_id = this.selectedServer
            }

            const attributesArray = Object.entries(this.selectedAttributes)
                .filter(([_, value]) => value.trim() !== "")
                .map(([id, value]) => ({ id: Number(id), value }));

            if (attributesArray.length) {
                payload.attributes = attributesArray;
            }

            axios.post('api/offers', payload)
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

            <v-row>
                <v-col cols="12">
                    <v-skeleton-loader v-if="!category.id" type="text" />
                    <v-text-field v-else v-model="title" label="Название"></v-text-field>
                </v-col>
            </v-row>

            <v-row>
                <v-col cols="6">
                    <v-skeleton-loader v-if="!category.id" type="text" />
                    <v-text-field v-else v-model="amount" :label="'Количество ' + category.unit?.title" type="number" min="1"></v-text-field>
                </v-col>
                <v-col cols="6">
                    <v-skeleton-loader v-if="!category.id" type="text" />
                    <v-text-field v-else v-model="price" :label="'Цена/' + category.unit?.title " type="number" min="1"></v-text-field>
                </v-col>
                <v-col v-if="category.servers?.length" cols="12">
                    <v-skeleton-loader v-if="!category.id" type="text" />
                    <v-select v-else v-model="selectedServer" :items="category.servers" label="Сервер" item-title="title" item-value="id"></v-select>
                </v-col>
            </v-row>

            <v-skeleton-loader v-if="!category.id" type="text" class="mb-2"/>
            <v-textarea v-else v-model="description" label="Описание"></v-textarea>

            <div v-if="category.attributes?.length">
                <p class="text-subtitle-1 font-weight-medium">Атрибуты</p>
                <v-row>
                    <v-col cols="4" v-for="attr in category.attributes" :key="attr.id">
                        <v-skeleton-loader v-if="!category.id" type="text" />
                        <v-text-field v-else v-model="selectedAttributes[attr.id]" :label="attr.title"></v-text-field>
                    </v-col>
                </v-row>
            </div>
        </v-card-text>

        <v-card-actions>
            <v-skeleton-loader v-if="!category.id" type="button" />
            <v-btn v-else @click="createOffer" color="primary">Сохранить изменения</v-btn>
        </v-card-actions>
    </v-card>
</template>

<style>

</style>
