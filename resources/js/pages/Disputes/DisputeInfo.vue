<script>
import ModerChat from "@/components/Moder/ModerChat.vue";

export default {
    name: "DisputeInfo",
    components: {ModerChat},

    data() {
        return {
            selectedDeal: {},

            messages: [],
            chatId: null,

            selectedRefundOption: null,
            refundOptions: [
                { title: 'Вернуть покупателю', value: 'buyer' },
                { title: 'Вернуть продавцу', value: 'seller' },
                { title: 'Разделить 50/50', value: 'fifty' }
            ]
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

        getChat() {
            axios.post('api/moder/chats/find', {
                userFirst: this.selectedDeal.buyer_id,
                userSecond: this.selectedDeal.offer.seller.id
            })
                .then(r => {
                    this.chatId = r.data.id

                    axios.get(`api/moder/chats/${r.data.id}`)
                        .then(res => {
                            this.messages = res.data.map(message => ({
                                id: message.id,
                                text: message.message,
                                time: message.created_at,
                                sender: message.type === 'moder' ? 'moder' : (message.user_id === this.currentUserId ? 'user' : 'other'),
                                senderId: message.user_id,
                                name: message.user.name,
                                type: message.type
                            }))
                        })
                })
        },

        confirmRefund() {
            if (!this.selectedRefundOption) return;

            let url = null;

            switch (this.selectedRefundOption) {
                case 'buyer':
                    url = 'api/moder/disputes/refund-buyer'
                    break
                case 'seller':
                    url = 'api/moder/disputes/refund-seller'
                    break
                case 'fifty':
                    url = 'api/moder/disputes/refund-fifty'
                    break
            }

            if (url) {
                axios.post(url, { deal_id: this.selectedDeal.id })
                    .then(() => {
                        this.selectedRefundOption = null
                        this.getDeal()
                    })
                    .catch(() => {
                        console.error('Ошибка при выполнении возврата');
                    });
            }
        }
    },

    mounted() {
        this.getDeal()
    }
}
</script>

<template>
    <v-row>
        <v-col cols="6">
            <v-card>
                <v-card-title>Заказ #{{ selectedDeal.id }}</v-card-title>
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
                </v-card-text>

                <v-card-actions v-if="selectedDeal.status_id == 4">
                    <v-select
                        v-model="selectedRefundOption"
                        :items="refundOptions"
                        label="Выберите, кому вернуть деньги"
                        outlined
                        dense
                    ></v-select>

                    <v-btn :disabled="!selectedRefundOption" @click="confirmRefund" color="primary">
                        Подтвердить
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-col>

        <v-col cols="6" v-if="selectedDeal.offer">
            <ModerChat
                :messages="messages"
                :buyerId="selectedDeal.buyer_id"
                :sellerId="selectedDeal.offer.seller.id"
                :buyerName="selectedDeal.buyer.name"
                :sellerName="selectedDeal.offer.seller.name"
                :chatId="chatId"
            ></ModerChat>
        </v-col>
    </v-row>
</template>

<style scoped>

</style>
