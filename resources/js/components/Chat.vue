<template>
    <template v-if="isAuthenticated">
        <!-- Если пользователь пытается написать сам себе -->
        <template v-if="isSelfChat">
            <v-card class="d-flex flex-column align-center justify-center pa-6" rounded>
                <v-icon size="64" color="grey">mdi-chat-remove</v-icon>
                <h2 class="text-h5 mt-4">Вы не можете писать самому себе</h2>
                <p class="text-body-1 text-grey mt-2">Выберите другого пользователя для общения.</p>
            </v-card>
        </template>

        <!-- Если нормальный чат -->
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

                <!-- Блок с сообщениями -->
                <v-card-text class="flex-grow-1 overflow-y-auto custom-scroll custom-height">
                    <div
                        class="flex-grow-1 overflow-y-auto custom-scroll custom-height"
                        ref="messageContainer"
                    >
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

                <!-- Блок ввода сообщения -->
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

    <!-- Если пользователь не авторизован -->
    <template v-else>
        <v-card class="d-flex flex-column align-center justify-center pa-6" rounded>
            <v-icon size="64" color="grey">mdi-chat</v-icon>
            <h2 class="text-h5 mt-4">Войдите, чтобы начать чат</h2>
            <p class="text-body-1 text-grey mt-2">Авторизуйтесь, чтобы общаться с другими пользователями.</p>
            <v-btn :to="{ name: 'login' }" color="primary" class="mt-4">Войти</v-btn>
        </v-card>
    </template>
</template>

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
        // Проверка, не пишет ли пользователь сам себе
        isSelfChat() {
            return this.currentUserId === this.secondUser;
        }
    },

    methods: {
        // Проверка авторизации
        checkAuth() {
            const userId = localStorage.getItem('userId');
            if (userId) {
                this.currentUserId = parseInt(userId);
                this.isAuthenticated = true;
            } else {
                this.isAuthenticated = false;
            }
        },

        getChat() {
            if (!this.isAuthenticated || this.isSelfChat) return; // Проверка на самого себя

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
                            sender: message.user_id === this.currentUserId ? 'user' : 'other',
                            type: message.type
                        }));

                        this.$nextTick(() => {
                            this.scrollToBottom();
                        });
                    });
            });
        },

        sendMessage() {
            if (!this.isAuthenticated || this.isSelfChat) return; // Проверка на самого себя

            axios.post('api/messages', {chat_id: this.chatId, message: this.message})
                .then(r => {
                    this.messages.push({
                        id: r.data.id,
                        text: r.data.message,
                        time: r.data.created_at,
                        sender: 'user'
                    });
                    this.message = '';

                    this.scrollToBottom();
                });
        },

        scrollToBottom() {
            const container = this.$refs.messageContainer;
            if (container) {
                setTimeout(() => {
                    container.scrollTop = container.scrollHeight;
                }, 50);
            }
        },
    },

    watch: {
        secondUser() {
            if (this.isAuthenticated) {
                this.getChat();
            }
        }
    },

    mounted() {
        this.checkAuth();
    }
};
</script>

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
