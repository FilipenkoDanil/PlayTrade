<script>
export default {
    name: "ModerChat",

    props: {
        messages: Array,
        buyerId: Number,
        sellerId: Number,
        buyerName: String,
        sellerName: String,
        chatId: Number,
    },

    data() {
        return {
            message: ''
        }
    },


    methods: {
        sendMessage() {
            axios.post('api/moder/messages/', {chat_id: this.chatId, message: this.message})
                .then(r => {
                    this.messages.push({
                        id: r.data.id,
                        text: r.data.message,
                        time: r.data.created_at,
                        name: r.data.user.name,
                        sender: 'moder',
                        type: 'moder'
                    });
                    this.message = '';

                    this.scrollToBottom()
                });
        },

        scrollToBottom() {
            this.$nextTick(() => {
                const container = this.$refs.messageContainer;
                if (container) {
                    container.scrollTop = container.scrollHeight;
                }
            });
        },
    },

    watch: {
        messages() {
            this.scrollToBottom()
        },

        chatId() {
            window.Echo.private(`chat.${this.chatId}`)
                .listen('.chat-message', r => {
                    this.messages.push({
                        id: r.message.id,
                        text: r.message.message,
                        time: r.message.created_at,
                        name: r.message.user.name,
                        sender: 'user',
                        type: r.message.type
                    })
                    this.scrollToBottom()
                })
        }
    },

    beforeUnmount() {
        window.Echo.leave(`chat.${this.chatId}`)
    }

}
</script>

<template>
    <v-card class="d-flex flex-column" rounded color="grey-darken-4">
        <v-card-title class="text-white">
            <h2 class="text-h5">Чат с {{ sellerName }} и {{ buyerName }}</h2>
            <v-divider class="border-grey"></v-divider>
        </v-card-title>
        <v-card-text class="flex-grow-1 overflow-y-auto custom-scroll custom-height">
            <div class="flex-grow-1 overflow-y-auto custom-scroll custom-height" ref="messageContainer">
                <div
                    v-for="message in messages"
                    :key="message.id"
                    :class="[
                    'd-flex',
                    message.type === 'notify' ? 'justify-center' :
                    message.type === 'moder' ? 'justify-end' : 'justify-start'
                ]"
                >
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
                    <v-card
                        v-else
                        class="pa-3 mb-2"
                        :color="message.sender === 'moder' ? 'primary' : 'grey-darken-3'"
                    >
                        <v-card-text class="pa-0 text-white">
                            <strong>{{ message.name }}</strong>
                            <strong v-if="message.type === 'moder'">
                                <v-chip color="red-darken-2" variant="text" size="small">Moder</v-chip>
                            </strong>
                            <br>
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
                v-model="message"
                label="Введите сообщение"
                outlined
                hide-details
                class="text-white"
                color="white"
                @keyup.enter="sendMessage"
            ></v-text-field>
            <v-btn @click="sendMessage" color="primary" type="button">Отправить</v-btn>
        </v-card-actions>
    </v-card>
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
