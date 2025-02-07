<script>
export default {
    name: "Category",

    data() {
        return {
            category: {
                game: {
                    title: ''
                }
            },
            offers: []
        }
    },

    mounted() {
        this.getCategory()
    },

    methods: {
        getCategory() {
            axios.get(`http://127.0.0.1:8000/api/categories/${this.$route.params.id}`)
                .then(res => {
                    this.category = res.data.data
                    this.offers = res.data.data.offers
                })
        }
    }
}
</script>

<template>
    <v-row>
        <v-col cols="12" md="8">
            <v-btn variant="text" color="primary" @click="$router.push({name: 'home'})" prepend-icon="mdi-arrow-left">Список игр</v-btn>

            <h1>{{ category.title }} - {{ category.game.title }}</h1>
            <p>{{ category.description }}</p>

            <v-btn v-for="cat in category.game.categories" :key="cat.id" :to="{name: 'category', params: {id: cat.id}}" active-color="indigo-darken-4" rounded size="large" class="mr-2 my-2">{{ cat.title }} <span class="text-medium-emphasis text-subtitle-1">320</span>
            </v-btn>

            <v-card class="offer-card px-4 mt-2 py-2" v-for="offer in offers" hover :to="{name: 'offer', params: {id: offer.id}}">
                <v-row align="center" no-gutters>
                    <!-- Левая часть: сервер, заголовок предложения -->
                    <v-col cols="12" sm="9" class="d-flex align-center">
                        <div class="text-caption text-grey-darken-1">
                            Европа (Амстердам)
                        </div>
                        <div class="ml-3 text-wrap">
                                <span class="text-body-2 ">
                                    {{ offer.title }}
                                </span>
                        </div>
                    </v-col>

                    <!-- Правая часть: аватар продавца, имя, рейтинг, цена -->
                    <v-col cols="12" sm="3" class="d-flex align-center justify-end">
                        <v-avatar size="40" class="mr-2">
                            <v-img src="https://picsum.photos/200" alt="Seller Avatar"></v-img>
                        </v-avatar>
                        <div>
                            <div class="">{{ offer.seller.name }}</div>
                            <div class="text-caption text-grey-darken-1">
                                ⭐ 1764
                            </div>
                        </div>
                        <v-spacer></v-spacer>
                        <div>
                            <span class="text-disabled text-body-2">{{ offer.amount }}</span>
                        </div>
                        <div class="ml-4 font-weight-bold">
                            {{ offer.price }} ₽
                        </div>
                    </v-col>
                </v-row>
            </v-card>
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
