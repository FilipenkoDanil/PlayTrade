<script>
import axios from "axios";

export default {
    name: "Profile",

    data() {
        return {
            user: {},
            offers: [],
            headers: [
                {title: 'Название', value: 'title'},
                {title: 'Наличие', value: 'amount', sortable: true},
                {title: 'Цена', value: 'price', sortable: true},
            ]
        }
    },

    methods: {
        getUser() {
            axios.get(`api/users/${this.$route.params.id}`)
                .then(r => {
                    this.user = r.data.data
                    this.offers = r.data.data.offers
                })
        },

        goToOffer(_, {item}) {
            this.$router.push({name: 'offer', params: {id: item.id}})
        },

        findServerById(servers, serverId) {
            return servers.find(server => server.id === serverId);
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
                        offers: [],
                        hasServers: offer.category.servers.length > 0, // Проверка наличия серверов
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
    <!-- Блок профиля -->
    <v-card class="pa-5 mb-5 elevation-3">
        <v-row align="center">
            <v-col cols="auto">
                <!-- Аватар -->
                <v-avatar size="100" class="mb-3">
                    <img src="https://picsum.photos/800" alt="Avatar">
                </v-avatar>
            </v-col>
            <v-col cols="auto">
                <!-- Имя пользователя -->
                <h2 class="font-weight-bold text-h5">{{ user.name }}</h2>

                <!-- Статус онлайн -->
                <v-chip color="green" variant="outlined" class="mt-2">
                    <v-icon small left>mdi-checkbox-blank-circle</v-icon>
                    Онлайн
                </v-chip>

                <!-- Дата регистрации -->
                <p class="text-grey-darken-2 mt-2">
                    Дата регистрации: {{ user.created_at }}
                </p>
            </v-col>
        </v-row>
    </v-card>

    <v-row>
        <v-col cols="12" md="6">
            <template v-if="groupedOffers.length > 0">
                <v-row v-for="group in groupedOffers" :key="group.category" class="mb-4">
                    <v-col cols="12">
                        <v-card class="elevation-3 mb-4">
                            <v-card-title class="text-h6 font-weight-bold">
                                {{ group.category }} {{ group.game }}
                            </v-card-title>
                            <v-card-text>
                                <v-data-table
                                    @click:row="goToOffer"
                                    :headers="group.hasServers ? [{ title: 'Сервер', value: 'server' }, ...headers] : headers"
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

                                        <span v-else>
                                            Нет сервера
                                        </span>
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
        </v-col>

        <v-col cols="6">
            <v-card>
                <v-card-title>Чат с {{ user.name }}</v-card-title>
            </v-card>
        </v-col>
    </v-row>
</template>

<style scoped>

</style>
