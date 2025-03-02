<script>
import axios from "axios";

export default {
    name: "Edit",

    data() {
        return {
            category: {},
            offer: {},

            selectedServerId: null,
            attributes: []
        }
    },

    methods: {
        getOffer() {
            axios.get(`api/offers/${this.$route.params.id}`)
                .then(r => {
                    this.offer = r.data.data
                    this.selectedServerId = r.data.data.server_id
                    this.getCategory()
                })
        },

        getCategory() {
            axios.get(`api/categories/${this.offer.category_id}`)
                .then(r => {
                    this.category = r.data.data

                    this.attributes = this.category.attributes.map(catAttr => {
                        const offerAttr = this.offer.attributes.find(attr => attr.id === catAttr.id)
                        return {
                            id: catAttr.id,
                            title: catAttr.title,
                            value: offerAttr ? offerAttr.value : ''
                        };
                    });
                })
        },

        updateOffer() {
            const payload = {
                title: this.offer.title,
                description: this.offer.description,
                auto_message: this.offer.auto_message,
                amount: this.offer.amount,
                price: this.offer.price,
                is_active: this.offer.is_active,
                server_id: this.selectedServerId
            }

            if (!this.category?.servers?.length) {
                payload.server_id = null
            }


            payload.attributes = this.attributes
                .filter(attr => attr.value)
                .map(attr => ({id: attr.id, value: attr.value}))

            axios.put(`api/offers/${this.$route.params.id}`, payload)
                .then(response => {
                    console.log("Offer updated successfully:", response.data)
                })
                .catch(error => {
                    console.error("Error updating offer:", error)
                });
        },

        deleteOffer() {
            axios.delete(`api/offers/${this.$route.params.id}`)
                .then(() => this.$router.push({name: 'user.offers'}))
        }
    },

    mounted() {
        this.getOffer();
    }
}
</script>

<template>
    <v-card class="pa-4 mx-auto" max-width="600">
        <v-btn @click="this.$router.push({name: 'user.offers'})" prepend-icon="mdi-arrow-left" variant="text"
               color="primary">Назад
        </v-btn>
        <v-card-title>Редактирование предложения</v-card-title>

        <v-card-text>
            <v-row>
                <v-col cols="12">
                    <span class="text-h5 text-medium-emphasis">{{ category.game?.title }} - {{ category.title }}</span>
                </v-col>
            </v-row>

            <v-row>
                <v-col cols="12">
                    <v-text-field v-model="offer.title" label="Название"></v-text-field>
                </v-col>
            </v-row>

            <v-row>
                <v-col cols="6">
                    <v-text-field v-model="offer.amount" :label="'Количество ' + category.unit?.title" type="number"
                                  min="1"></v-text-field>
                </v-col>
                <v-col cols="6">
                    <v-text-field v-model="offer.price" :label="'Цена/' + category.unit?.title" type="number"
                                  min="1"></v-text-field>
                </v-col>

                <v-col v-if="category?.servers?.length" cols="12">
                    <v-select v-model="selectedServerId" :items="category.servers" label="Сервер" item-title="title"
                              item-value="id"></v-select>
                </v-col>
            </v-row>

            <v-textarea v-model="offer.description" label="Описание"></v-textarea>

            <v-textarea v-model="offer.auto_message" label="Автосообщение"></v-textarea>

            <v-checkbox v-model="offer.is_active" label="Активно" :false-value="0" :true-value="1"></v-checkbox>

            <div v-if="attributes.length">
                <p class="text-subtitle-1 font-weight-medium">Атрибуты</p>
                <v-row>
                    <v-col cols="4" v-for="(attr, index) in attributes" :key="attr.id">
                        <v-text-field v-model="attributes[index].value" :label="attr.title"></v-text-field>
                    </v-col>
                </v-row>
            </div>
        </v-card-text>

        <v-card-actions>
            <v-btn @click="updateOffer" color="primary">Сохранить изменения</v-btn>
            <v-spacer></v-spacer>
            <v-btn @click="deleteOffer" color="red">Удалить предложение</v-btn>
        </v-card-actions>
    </v-card>
</template>

<style scoped>

</style>
