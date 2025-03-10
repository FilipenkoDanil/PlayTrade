<script>

import Chat from "@/components/Chat.vue";

export default {
    name: "Deal",
    components: {Chat},
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
            },

            messages: [],
            message: '',

            currentUserId: parseInt(localStorage.getItem('userId')),
            chatId: null,
            companion: {},

            rating: {
                rating: 0,
                comment: ''
            },
        }
    },

    methods: {
        getDeal() {
            axios.get(`api/deals/${this.$route.params.id}`)
                .then(r => {
                    this.selectedDeal = r.data.data
                    this.rating = r.data.data.rating || { rating: 0, comment: '' }
                    this.getChat()
                })
        },


        getChat() {
            axios.post('api/chats/find', {
                userFirst: this.selectedDeal.buyer_id,
                userSecond: this.selectedDeal.offer.seller.id
            }).then(r => {
                this.chatId = r.data.id
                this.companion = r.data.users.find(user => user.id !== this.currentUserId)
                axios.get(`api/chats/${r.data.id}`)
                    .then(r => {
                        this.messages = r.data.map(message => ({
                            id: message.id,
                            text: message.message,
                            time: message.created_at,
                            sender: message.user_id === this.currentUserId ? 'user' : 'other',
                            type: message.type,
                            name: message.user.name
                        }))

                        this.$nextTick(() => {
                            this.scrollToBottom()
                        })
                    })
            })
        },

        updateRating() {
            axios.post('api/ratings', {
                deal_id: this.selectedDeal.id,
                rating: this.rating.rating,
                comment: this.rating.comment
            })
                .then(() => this.getDeal())
        },

        deleteRating() {
            axios.delete(`api/ratings/${this.rating.id}`)
                .then(() => this.getDeal())
        },

        sendMessage() {
            axios.post('api/messages', {chat_id: this.chatId, message: this.message})
                .then(r => {
                    this.messages.push({
                        id: r.data.id,
                        text: r.data.message,
                        time: r.data.created_at,
                        sender: 'user',
                        name: r.data.user.name
                    })
                    this.message = '';

                    this.scrollToBottom()
                })
        },

        scrollToBottom() {
            const container = this.$refs.messageContainer;
            if (container) {
                setTimeout(() => {
                    container.scrollTop = container.scrollHeight
                    this.showMessages = true
                }, 50)
            }
        },

        confirmDeal(dealId) {
            axios.patch(`/api/deals/${dealId}/confirm`)
                .then(() => this.getDeal())
        },

        cancelDeal(dealId) {
            axios.patch(`api/deals/${dealId}/cancel`)
                .then(() => this.getDeal())
        },

        disputeDeal(dealId) {
            axios.patch(`api/deals/${dealId}/dispute`)
                .then(() => this.getDeal())
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
                    <v-card-title class="d-flex align-center">
                        Заказ #{{ selectedDeal.id }}
                        <v-spacer></v-spacer>
                        <v-btn v-if="selectedDeal.status_id === 1" @click="disputeDeal(selectedDeal.id)" color="orange-darken-4" variant="text" size="small">Открыть спор</v-btn>
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
                            <strong>Сумма: </strong> {{ selectedDeal.price }} ₴
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

                            <div v-if="currentUserId === selectedDeal.buyer_id">
                                <h3 v-if="!selectedDeal.rating">Добавить отзыв</h3>
                                <h3 v-else>Изменить отзыв</h3>
                                <v-rating v-model="rating.rating" color="yellow"></v-rating>
                                <v-textarea v-model="rating.comment" placeholder="Оставьте ваш отзыв."></v-textarea>
                                <div class="d-flex">
                                    <v-btn @click="updateRating" color="primary">Сохранить</v-btn>
                                    <v-spacer></v-spacer>
                                    <v-btn v-if="selectedDeal.rating" @click="deleteRating" color="red">Удалить</v-btn>
                                </div>
                            </div>

                            <div v-else-if="selectedDeal.rating">
                                <h3>Отзыв покупателя</h3>
                                <v-rating v-model="selectedDeal.rating.rating" color="yellow" readonly></v-rating>
                                <p class="text-subtitle-2">{{ selectedDeal.rating.comment }}</p>
                            </div>
                        </div>

                    </v-card-text>

                    <v-card-actions v-if="selectedDeal.status_id === 1">
                        <v-btn @click="confirmDeal(selectedDeal.id)" v-if="currentUserId === selectedDeal.buyer_id" color="green">
                            Подтвердить сделку
                        </v-btn>
                        <v-btn @click="cancelDeal(selectedDeal.id)" v-else color="red">
                            Отменить сделку
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </v-col>

            <!-- Чат -->
            <v-col cols="7">
                <Chat :secondUser="selectedDeal.buyer_id === currentUserId ? selectedDeal.offer.seller.id : selectedDeal.buyer_id"></Chat>
            </v-col>
        </v-row>
    </v-container>
</template>

<style scoped>

</style>
