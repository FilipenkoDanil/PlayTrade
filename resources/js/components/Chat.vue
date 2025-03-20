<script>
export default {
    name: "Chat",

    props: ['secondUser'],

    data() {
        return {
            messages: [],
            message: '',
            currentUserId: null,
            chatId: null,
            companion: {},
            isAuthenticated: false
        };
    },

    computed: {
        isSelfChat() {
            return this.currentUserId === this.secondUser;
        }
    },

    methods: {
        checkAuth() {
            const userId = localStorage.getItem('userId')
            if (userId) {
                this.currentUserId = parseInt(userId);
                this.isAuthenticated = true
            } else {
                this.isAuthenticated = false
            }
        },

        markMessagesAsRead(chatId) {
            axios.post(`/api/chats/${chatId}/mark-read`, {
                user_id: this.currentUserId,
            })
        },

        getChat() {
            if (!this.isAuthenticated || this.isSelfChat) return

            axios.post('api/chats/find', {
                userFirst: this.currentUserId,
                userSecond: this.secondUser,
            }).then(r => {
                this.chatId = r.data.id;
                this.companion = r.data.users.find(user => user.id !== this.currentUserId);
                axios.get(`api/chats/${r.data.id}`)
                    .then(r => {
                        this.messages = r.data.map(message => ({
                            id: message.id,
                            text: message.message,
                            time: message.created_at,
                            sender: message.type === 'moder' ? 'moder' : (message.user_id === this.currentUserId ? 'user' : 'other'),
                            type: message.type,
                            name: message.user.name,
                        }));

                        this.scrollToBottom()

                        if (this.echoChannel) {
                            this.echoChannel.unsubscribe();
                        }

                        this.echoChannel = window.Echo.private(`chat.${this.chatId}`);
                        this.echoChannel.listen(".chat-message", (data) => {
                            this.messages.push({
                                id: data.message.id,
                                text: data.message.message,
                                time: data.message.created_at,
                                sender:
                                    data.message.user_id === this.currentUserId
                                        ? "user"
                                        : "other",
                                name: data.message.user.name,
                                type: data.message.type,
                            });

                            this.markMessagesAsRead(this.chatId);
                        });
                    });
            });
        },

        sendMessage() {
            if (!this.isAuthenticated || this.isSelfChat) return

            axios.post('api/messages', {chat_id: this.chatId, message: this.message})
                .then(r => {
                    this.messages.push({
                        id: r.data.id,
                        text: r.data.message,
                        time: r.data.created_at,
                        sender: 'user',
                        name: r.data.user.name,
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
        secondUser() {
            if (this.isAuthenticated) {
                this.getChat()
            }
        },

        messages: {
            handler() {
                this.scrollToBottom();
            },
            deep: true,
        }
    },

    mounted() {
        this.checkAuth()
    },

    beforeUnmount() {
        window.Echo.leave(`chat.${this.chatId}`);
    }
}
</script>

<template>
    <template v-if="isAuthenticated">
        <template v-if="isSelfChat">
            <v-card class="d-flex flex-column align-center justify-center pa-6" rounded>
                <v-icon size="64" color="grey">mdi-chat-remove</v-icon>
                <h2 class="text-h5 mt-4">Вы не можете писать самому себе</h2>
                <p class="text-body-1 text-grey mt-2">Выберите другого пользователя для общения.</p>
            </v-card>
        </template>
        <template v-else>
            <v-card class="d-flex flex-column" rounded>
                <v-card-title>
                    <h2 class="text-h5">
                        Чат с
                        <router-link
                            class="text-decoration-none text-white"
                            :to="{ name: 'user.profile', params: { id: companion.id } }"
                        >
                            {{ companion.name }}
                        </router-link>
                    </h2>
                    <v-divider></v-divider>
                </v-card-title>
                <v-card-text class="flex-grow-1 overflow-y-auto custom-scroll custom-height">
                    <div class="flex-grow-1 overflow-y-auto custom-scroll custom-height" ref="messageContainer">
                        <template v-if="messages.length === 0">
                            <div class="d-flex flex-column align-center justify-center text-center h-100">
                                <v-icon size="64" color="grey">mdi-message-outline</v-icon>
                                <h3 class="text-h6 mt-4">Сообщений пока нет</h3>
                                <p class="text-body-2 text-grey">Напишите первое сообщение, чтобы начать беседу.</p>
                            </div>
                        </template>
                        <template v-else>
                            <div
                                v-for="message in messages"
                                :key="message.id"
                                :class="[
                                'd-flex',
                                message.type === 'notify' ? 'justify-center' : (message.sender === 'user' ? 'justify-end' : 'justify-start')
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
                                    :color="message.sender === 'user' ? 'primary' : 'grey-darken-3'"
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
                        </template>
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
        </template>
    </template>
    <template v-else>
        <v-card class="d-flex flex-column align-center justify-center pa-6" rounded>
            <v-icon size="64" color="grey">mdi-chat</v-icon>
            <h2 class="text-h5 mt-4">Войдите, чтобы начать чат</h2>
            <p class="text-body-1 text-grey mt-2">Авторизуйтесь, чтобы общаться с другими пользователями.</p>
            <v-btn :to="{ name: 'login' }" color="primary" class="mt-4">Войти</v-btn>
        </v-card>
    </template>
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
