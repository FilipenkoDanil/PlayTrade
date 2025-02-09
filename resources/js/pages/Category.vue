<script>
export default {
    name: "Category",

    data() {
        return {
            category: {
                game: {}
            },
            offers: [],
            headers: [
                {title: 'Название', value: 'title'},
                {title: 'Продавец', value: 'seller.name'},
                {title: 'Количество', value: 'amount'},
                {title: 'Цена', value: 'price'}
            ],
        }
    },

    mounted() {
        this.getCategory()
    },

    methods: {
        getCategory() {
            axios.get(`api/categories/${this.$route.params.id}`)
                .then(res => {
                    this.category = res.data.data
                    this.offers = res.data.data.offers
                })
        },

        goToOffer(_, {item}) {
            this.$router.push({name: 'offer', params: {id: item.id}})
        }
    }
}
</script>

<template>
    <v-row>
        <v-col cols="12" md="8">
            <v-btn variant="text" color="primary" @click="$router.push({name: 'home'})" prepend-icon="mdi-arrow-left">
                Список игр
            </v-btn>

            <h1>{{ category.title }} - {{ category.game.title }}</h1>
            <p class="text-body-2 text-medium-emphasis">{{ category.description }}</p>

            <v-btn v-for="cat in category.game.categories" :key="cat.id" :to="{name: 'category', params: {id: cat.id}}"
                   active-color="indigo-darken-4" rounded size="large" class="mr-2 my-2">{{ cat.title }} <span
                class="text-medium-emphasis text-subtitle-1">320</span>
            </v-btn>

            <div v-if="category.servers?.length > 0">
                <v-divider></v-divider>
                <v-select :items="category.servers" placeholder="Выберите сервер"></v-select>
                <v-divider></v-divider>
            </div>

            <v-data-table
                :headers="headers"
                :items="offers"
                item-value="id"
                hover
                @click:row="goToOffer"
            >
                <template v-slot:item.seller.name="{ item }">
                    <v-avatar size="40">
                        <v-img src="https://picsum.photos/500" alt="Avatar"></v-img>
                    </v-avatar>
                    {{ item.seller.name }}
                </template>

                <template v-slot:item.amount="{ item }">
                    {{ item.amount }}{{ this.category.unit.title }}
                </template>

                <template v-slot:item.price="{ item }">
                    {{ item.price }} $
                </template>
            </v-data-table>
        </v-col>

        <v-col cols="12" md="4">
            <v-card class="mb-4">
                <v-card-title>Топ продавцы</v-card-title>
                <v-list>
                    <v-list-item>SilverMaster (500+ сделок)</v-list-item>
                    <v-list-item>FastGold (300+ сделок)</v-list-item>
                </v-list>
            </v-card>

            <v-expansion-panels>
                <v-expansion-panel>
                    <v-expansion-panel-title>Как купить?</v-expansion-panel-title>
                    <v-expansion-panel-text>
                        1. Выберите продавца.<br>
                        2. Оформите сделку.<br>
                        3. Дождитесь подтверждения.
                    </v-expansion-panel-text>
                </v-expansion-panel>
            </v-expansion-panels>
        </v-col>
    </v-row>

</template>

<style scoped>

</style>
