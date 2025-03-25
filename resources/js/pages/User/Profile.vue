<script>
import Chat from "@/components/Chat.vue"

export default {
    name: "Profile",
    components: {Chat},

    data() {
        return {
            user: {},
            offers: [],
            headers: [
                {title: 'Название', value: 'title'},
                {title: 'Наличие', value: 'amount', sortable: true},
                {title: 'Цена', value: 'price', sortable: true},
            ],
            reviews: []
        }
    },

    methods: {
        getUser() {
            axios.get(`api/users/${this.$route.params.id}`)
                .then(r => {
                    this.user = r.data.data
                    this.offers = r.data.data.offers
                    this.reviews = r.data[0]
                })
        },

        goToOffer(_, {item}) {
            this.$router.push({name: 'offer', params: {id: item.id}})
        },

        findServerById(servers, serverId) {
            return servers.find(server => server.id === serverId);
        },

        getHeaders(group) {
            const baseHeaders = [
                {title: 'Наличие', value: 'amount', sortable: true},
                {title: 'Цена', value: 'price', sortable: true},
            ];

            if (group.type === 1) {
                baseHeaders.unshift({title: 'Название', value: 'title'});
            }

            if (group.hasServers) {
                baseHeaders.unshift({title: 'Сервер', value: 'server'});
            }

            return baseHeaders;
        },
    },

    mounted() {
        this.getUser()
    },

    computed: {
        groupedOffers() {
            const groups = {};
            this.offers.forEach((offer) => {
                const key = `${offer.category.title} - ${offer.category.game.title}`;
                if (!groups[key]) {
                    groups[key] = {
                        category: offer.category.title,
                        game: offer.category.game.title,
                        type: offer.category.type, // Добавляем тип категории
                        offers: [],
                        hasServers: offer.category.servers.length > 0,
                    };
                }
                groups[key].offers.push(offer);
            });
            return Object.values(groups);
        },
    },
}
</script>

<template>
    <v-card class="pa-5 mb-5 elevation-3">
        <v-row align="center">
            <v-col cols="auto">
                <v-avatar size="100" class="mb-3">
                    <img src="https://picsum.photos/800" alt="Avatar">
                </v-avatar>
            </v-col>
            <v-col cols="auto">
                <h2 class="font-weight-bold text-h5">{{ user.name }}</h2>

                <!-- Статус онлайн/оффлайн -->
                <v-chip
                    :color="user.is_online ? 'green' : 'red'"
                    variant="outlined"
                    class="mt-2"
                >
                    <v-icon small left>mdi-checkbox-blank-circle</v-icon>
                    {{ user.is_online ? 'Онлайн' : 'Оффлайн' }}
                </v-chip>

                <!-- Дата последнего захода (если оффлайн) -->
                <p v-if="!user.is_online" class="text-grey-darken-2 mt-2">
                    Был в сети: {{ user.last_activity_at }}
                </p>

                <!-- Дата регистрации (если онлайн) -->
                <p v-else class="text-grey-darken-2 mt-2">
                    Дата регистрации: {{ user.created_at }}
                </p>
            </v-col>
        </v-row>
    </v-card>

    <v-row>
        <v-col cols="12" md="6">
            <template v-if="groupedOffers.length > 0">
                <v-row v-for="group in groupedOffers" :key="group.category">
                    <v-col cols="12">
                        <v-card>
                            <v-card-title class="text-h6 font-weight-bold">
                                {{ group.category }} {{ group.game }}
                            </v-card-title>
                            <v-card-text>
                                <v-data-table
                                    @click:row="goToOffer"
                                    :headers="getHeaders(group)"
                                :items="group.offers"
                                :items-per-page="100"
                                hide-default-footer
                                hover
                                >
                                <template v-slot:item.server="{ item }">
                                        <span v-if="item.server_id" class="text-medium-emphasis">
                                            {{
                                                findServerById(item.category.servers, item.server_id)?.title || 'Сервер не найден'
                                            }}
                                        </span>
                                    <span v-else>Нет сервера</span>
                                </template>

                                <template v-slot:item.amount="{ item }">
                                    {{ item.amount }}{{ item.category?.unit.title }}
                                </template>

                                <template v-slot:item.price="{ item }">
                                    {{ item.price }} ₴
                                </template>
                                </v-data-table>
                            </v-card-text>
                        </v-card>
                    </v-col>
                </v-row>
            </template>
            <template v-else>
                <v-alert type="info" variant="tonal">
                    Нет активных предложений.
                </v-alert>
            </template>

            <!-- Блок с отзывами -->
            <v-row class="mt-5">
                <v-col cols="12">
                    <template v-if="reviews.length === 0">
                        <v-alert type="info" variant="tonal" class="mb-4">
                            Нет отзывов.
                        </v-alert>
                    </template>

                    <template v-else>
                        <v-card>
                            <v-card-text>
                                <h3 class="text-h5 font-weight-bold mb-4">Отзывы</h3>
                                <v-list>
                                    <template v-for="(review, index) in reviews" :key="index">
                                        <v-list-item class="pa-3">
                                            <template v-slot:prepend>
                                                <v-avatar size="56" class="mr-4">
                                                    <v-img src="https://picsum.photos/200" alt="Аватар"></v-img>
                                                </v-avatar>
                                            </template>

                                            <v-list-item-title class="d-flex justify-space-between font-weight-bold">
                                                <div>
                                                    <router-link
                                                        class="text-decoration-none text-white"
                                                        :to="{ name: 'user.profile', params: { id: review.user.id } }"
                                                    >
                                                        {{ review.user.name }}
                                                    </router-link>
                                                    <span class="ml-2 text-medium-emphasis text-subtitle-2">
                                                        {{ review.deal.offer_game }}, {{
                                                            Math.round(review.deal.price)
                                                        }} ₴
                                                    </span>
                                                </div>
                                                <span class="text-medium-emphasis text-subtitle-2">{{
                                                        review.created_at
                                                    }}</span>
                                            </v-list-item-title>

                                            <v-rating
                                                v-model="review.rating"
                                                color="amber"
                                                half-increments
                                                readonly
                                                size="24"
                                            ></v-rating>

                                            <v-list-item-subtitle>
                                                {{ review.comment }}
                                            </v-list-item-subtitle>
                                        </v-list-item>

                                        <v-divider v-if="index < reviews.length - 1"></v-divider>
                                    </template>
                                </v-list>
                            </v-card-text>
                        </v-card>
                    </template>
                </v-col>
            </v-row>
        </v-col>

        <!-- Колонка с чатом -->
        <v-col cols="12" md="6">
            <Chat :secondUser="this.user.id"></Chat>
        </v-col>
    </v-row>
</template>

<style scoped>

</style>
