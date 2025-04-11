<script>
export default {
    name: "Category",

    data() {
        return {
            category: {
                game: {},
                servers: []
            },
            offers: [],
            filteredOffers: [],
            selectedServer: null,
            searchQuery: "",
            attributeFilters: {},
            headers: [
                {title: 'Продавец', value: 'seller.name'},
                {title: 'Количество', value: 'amount', sortable: true},
                {title: 'Цена', value: 'price', sortable: true}
            ],
            loading: true
        }
    },

    mounted() {
        this.getCategory()
    },

    methods: {
        getCategory() {
            axios.get(`api/categories/${this.$route.params.id}`)
                .then(res => {
                    this.category = res.data.data;
                    this.offers = res.data.data.offers;
                    this.filteredOffers = [...this.offers];
                    this.loading = false;

                    if (this.category.type === 1) {
                        this.headers.unshift({title: 'Название', value: 'title'});
                    }

                    if (this.category.servers?.length) {
                        this.headers.unshift({title: 'Сервер', value: 'server.title'});
                    }

                    this.attributeFilters = this.category.attributes.reduce((acc, attr) => {
                        acc[attr.id] = "";
                        return acc;
                    }, {});
                })
        },

        goToOffer(_, {item}) {
            this.$router.push({name: 'offer', params: {id: item.id}})
        },

        filterOffers() {
            this.filteredOffers = this.offers.filter(offer => {
                if (this.selectedServer && offer.server_id !== this.selectedServer) {
                    return false;
                }

                return !(this.searchQuery && !offer.title.toLowerCase().includes(this.searchQuery.toLowerCase()));
            });
        }
    }
}
</script>

<template>
    <v-container>
        <v-row>
            <v-col cols="12" md="8">
                <v-btn variant="text" color="primary" @click="$router.push({name: 'home'})" prepend-icon="mdi-arrow-left">
                    Список игр
                </v-btn>

                <v-skeleton-loader v-if="loading" type="heading"></v-skeleton-loader>
                <template v-else>
                    <h1 class="">{{ category.title }} - {{ category.game.title }}</h1>
                    <p class="text-body-2 text-medium-emphasis">{{ category.description }}</p>
                </template>

                <v-skeleton-loader class="mt-2 mb-8" v-if="loading" type="chip, chip, chip, chip"></v-skeleton-loader>
                <template v-else>
                    <v-btn v-for="cat in category.game.categories" :key="cat.id" :to="{name: 'category', params: {id: cat.id}}"
                           active-color="indigo-darken-4" rounded  class="mr-2 my-2">
                        {{ cat.title }}
                        <span class="text-medium-emphasis text-subtitle-2">{{ cat.offers_count }}</span>
                    </v-btn>
                </template>

                <v-row>
                    <v-col cols="12" md="4" v-if="category.type === 1">
                        <v-text-field v-model="searchQuery" label="Поиск по названию" variant="outlined" density="comfortable" @input="filterOffers"></v-text-field>
                    </v-col>

                    <v-col v-if="category.servers?.length > 0" cols="12" md="4">
                        <v-select
                            v-model="selectedServer"
                            :items="category.servers"
                            item-title="title"
                            item-value="id"
                            clearable
                            variant="outlined"
                            placeholder="Выберите сервер"
                            density="comfortable"
                            @update:model-value="filterOffers">
                        </v-select>
                    </v-col>
                </v-row>

                <div class="d-flex justify-end mb-4">
                    <v-btn @click="$router.push({name: 'offer.create', params: {categoryId: $route.params.id}})" color="indigo" class="font-weight-bold">
                        Продать {{ category.title }}
                    </v-btn>
                </div>

                <v-skeleton-loader v-if="loading" type="table"></v-skeleton-loader>
                <v-data-table
                    v-else
                    :headers="headers"
                    :items="filteredOffers"
                    item-value="id"
                    hover
                    class="offer-table"
                    items-per-page="25"
                    @click:row="goToOffer"
                >
                    <template v-if="category.servers?.length" v-slot:item.server.title="{ item }">
                        <span class="text-medium-emphasis">{{ item.server?.title || '—' }}</span>
                    </template>

                    <template v-slot:item.seller.name="{ item }">
                        <div class="d-flex align-center">
                            <v-avatar
                                size="40"
                                class="mr-2"
                                :class="item.seller.is_online ? 'online-avatar' : 'offline-avatar'"
                            >
                                <v-img src="https://picsum.photos/500" alt="Avatar"></v-img>
                            </v-avatar>

                            <span class="mr-2">{{ item.seller.name }}</span>

                        </div>
                    </template>

                    <template v-slot:item.amount="{ item }">
                        {{ item.amount }} {{ category.unit?.title }}
                    </template>

                    <template v-slot:item.price="{ item }">
                        {{ item.price }} ₴
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
                            3. Дождитесь выполнения и подтвердите.
                        </v-expansion-panel-text>
                    </v-expansion-panel>
                </v-expansion-panels>
            </v-col>
        </v-row>
    </v-container>
</template>

<style scoped>
.offer-table {
    border-radius: 8px;
    overflow: hidden;
}

.online-avatar {
    border: 2px solid green;
}

.offline-avatar {
    border: 2px solid red;
}
</style>
