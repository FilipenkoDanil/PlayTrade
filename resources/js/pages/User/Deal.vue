<script>
import ChatWindow from "@/components/ChatWindow.vue";

export default {
    name: "Deal",
    components: {ChatWindow},
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

            currentUserId: null,

            chatId: null,
        }
    },

    methods: {
        getDeal() {
            axios.get(`api/deals/${this.$route.params.id}`)
                .then(r => {
                    this.selectedDeal = r.data.data
                    this.getChat()
                })
        },

        getCurrentUser() {
            axios.get('/api/user')
                .then(r => this.currentUserId = r.data.id)
        },

        getChat() {
            axios.post('api/chats/find', {
                userFirst: this.selectedDeal.buyer_id,
                userSecond: this.selectedDeal.seller.id
            }).then(r => {
                this.chatId = r.data.id
                axios.get(`api/chats/${r.data.id}`)
                    .then(r => {
                        this.messages = r.data.map(message => ({
                            id: message.id,
                            text: message.message,
                            time: message.created_at,
                            sender: message.user_id === this.currentUserId ? 'user' : 'other',
                            type: message.type
                        }))

                        this.$nextTick(() => {
                            this.scrollToBottom();
                        })
                    })
            })
        },

        sendMessage() {
            axios.post('api/messages', {chat_id: this.chatId, message: this.message})
                .then(r => {
                    this.messages.push({
                        id: r.data.id,
                        text: r.data.message,
                        time: r.data.created_at,
                        sender: 'user'
                    })
                    this.message = '';

                    this.scrollToBottom()
                })
        },

        scrollToBottom() {
            const container = this.$refs.messageContainer;
            if (container) {
                setTimeout(() => {
                    container.scrollTop = container.scrollHeight;
                    this.showMessages = true; // Показываем сообщения только после скролла
                }, 50);
            }
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
        this.getCurrentUser()
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

                    <v-card-actions v-if="selectedDeal.status_id === 1">
                        <v-btn @click="confirmDeal(selectedDeal.id)" v-if="selectedDeal.status_id === 1" color="green">
                            Подтвердить сделку
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </v-col>

            <v-col cols="7">
                <v-card class="d-flex flex-column" rounded>
                    <v-card-title>
                        <h2 class="text-h5">Чат</h2>
                        <v-divider></v-divider>
                    </v-card-title>

                    <v-card-text class="flex-grow-1 overflow-y-auto custom-scroll custom-height">
                        <div
                            class="flex-grow-1 overflow-y-auto custom-scroll custom-height"
                            ref="messageContainer"
                        >
                            <!-- Сообщения и уведомления -->
                            <div
                                v-for="message in messages"
                                :key="message.id"
                                :class="[
                        'd-flex',
                        message.type === 'notify' ? 'justify-center' : (message.sender === 'user' ? 'justify-end' : 'justify-start')
                    ]"
                            >
                                <!-- Уведомление -->
                                <v-card
                                    v-if="message.type === 'notify'"
                                    class="pa-3 mb-2"
                                    color="grey-lighten-3"
                                    flat
                                >
                                    <v-card-text class="pa-0 d-flex align-center">
                                        <v-icon class="mr-2" color="grey">mdi-bell</v-icon>
                                        <span class="text-caption">{{ message.text }}</span>
                                    </v-card-text>
                                </v-card>

                                <!-- Обычное сообщение -->
                                <v-card
                                    v-else
                                    class="pa-3 mb-2"
                                    :color="message.sender === 'user' ? 'primary' : 'grey-darken-3'"
                                >
                                    <v-card-text class="pa-0">
                                        {{ message.text }}
                                    </v-card-text>
                                    <v-card-actions class="pa-0 mt-1">
                                        <span class="text-caption text-grey-lighten-1">{{ message.time }}</span>
                                    </v-card-actions>
                                </v-card>
                            </div>
                        </div>
                    </v-card-text>

                    <v-divider></v-divider>

                    <v-card-actions class="pa-3">
                        <v-text-field
                            label="Введите сообщение"
                            outlined
                            hide-details
                            v-model="message"
                            @keyup.enter="sendMessage"
                        ></v-text-field>
                        <v-btn @click="sendMessage" color="primary" type="button">Отправить</v-btn>
                    </v-card-actions>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>

<style scoped>
.custom-scroll {
    scrollbar-width: none;
    -ms-overflow-style: none;
}

.custom-scroll::-webkit-scrollbar {
    display: none;
}

.custom-height {
    height: 75vh;
    max-height: 75vh;
}
</style>
