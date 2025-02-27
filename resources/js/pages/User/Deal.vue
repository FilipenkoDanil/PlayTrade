<script>
export default {
    name: "Deal",
    data() {
        return {
            selectedDeal: {
                id: null,
                offer_game: '',
                offer_category: '',
                offer_title: '',
                offer_description: '',
                offer_attributes: '',
                offer_server: '',
                price: null,
                quantity: null,
                offer_unit: '',
                status_id: null
            }
        }
    },

    methods: {
        getDeal() {
            axios.get(`api/deals/${this.$route.params.id}`)
                .then(r => {
                    this.selectedDeal = r.data.data
                })
        },

        confirmDeal(dealId) {
            axios.patch(`/api/deals/${dealId}/confirm`)
                .then(() => {
                    this.getDeal()
                })
        },
        getStatusText(statusId) {
            switch (statusId) {
                case 1:
                    return 'In Progress';
                case 2:
                    return 'Completed';
                case 3:
                    return 'Canceled';
                case 4:
                    return 'Disputed';
                default:
                    return 'Unknown';
            }
        },
        getStatusColor(statusId) {
            switch (statusId) {
                case 1:
                    return 'blue';
                case 2:
                    return 'green';
                case 3:
                    return 'red';
                case 4:
                    return 'orange';
                default:
                    return 'grey';
            }
        },
    },

    mounted() {
        this.getDeal()
    }
}
</script>

<template>
    <v-container>
        <v-row>
            <v-col cols="5">
                <v-card>
                    <v-card-title>
                        Заказ #{{ selectedDeal.id }}
                    </v-card-title>
                    <v-card-text>
                        <p><strong>Игра:</strong> {{ selectedDeal.offer_game }}</p>
                        <p><strong>Категория:</strong> {{ selectedDeal.offer_category }}</p>
                        <v-divider class="my-3"></v-divider>
                        <p><strong>Название оффера:</strong> {{ selectedDeal.offer_title }}</p>
                        <p><strong>Описание:</strong> {{ selectedDeal.offer_description }}</p>

                        <div v-if="selectedDeal.offer_attributes && JSON.parse(selectedDeal.offer_attributes).length">
                            <v-divider class="my-3"></v-divider>
                            <p><strong>Атрибуты:</strong></p>
                            <v-list dense>
                                <v-list-item
                                    v-for="attr in JSON.parse(selectedDeal.offer_attributes)"
                                    :key="attr.id"
                                >
                                    <v-list-item-title>
                                        <strong>{{ attr.title }}</strong>: {{ attr.pivot.value }}
                                    </v-list-item-title>
                                </v-list-item>
                            </v-list>
                        </div>

                        <p v-if="selectedDeal.offer_server">
                            <v-divider class="my-3"></v-divider>
                            <strong>Сервер:</strong> {{ JSON.parse(selectedDeal.offer_server).title }}
                        </p>

                        <p>
                            <v-divider class="my-3"></v-divider>
                            <strong>Сумма: </strong> {{ selectedDeal.price }}
                            <strong>Количество: </strong> {{ selectedDeal.quantity }}{{ selectedDeal.offer_unit }}
                        </p>

                        <p>
                            <v-divider class="my-3"></v-divider>
                            <strong>Статус: </strong>
                            <v-chip :color="getStatusColor(selectedDeal.status_id)">
                                {{ getStatusText(selectedDeal.status_id) }}
                            </v-chip>
                        </p>


                        <div v-if="selectedDeal.status_id === 2">
                            <v-divider class="my-3"></v-divider>
                            <h3>Добавить отзыв</h3>
                            <v-rating color="yellow"></v-rating>
                            <v-textarea></v-textarea>
                            <v-btn color="primary">Сохранить</v-btn>
                        </div>

                    </v-card-text>

                    <v-card-actions>
                        <v-btn @click="confirmDeal(selectedDeal.id)" v-if="selectedDeal.status_id === 1" color="green">
                            Подтвердить сделку
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </v-col>

            <v-col cols="7">
                <v-card>
                    <v-card-title>Чат</v-card-title>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>

<style scoped>

</style>
