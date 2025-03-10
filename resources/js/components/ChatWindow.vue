<script>
export default {
    name: "ChatWindow",
    props: ['isChatSelected', 'messages', 'companion'],

    data() {
        return {
            message: '',
            showMessages: false
        };
    },

    watch: {
        isChatSelected(newVal) {
            if (newVal) {
                this.showMessages = false
                this.$nextTick(() => {
                    this.scrollToBottom();
                });
            }
        },

        messages: {
            handler() {
                this.$nextTick(() => {
                    this.scrollToBottom();
                });
            },
            deep: true
        }
    },

    methods: {
        emitMessage() {
            if (!this.message.trim()) return;
            this.$emit('sendMessage', this.message);
            this.message = '';

            this.$nextTick(() => {
                this.scrollToBottom();
            });
        },

        scrollToBottom() {
            const container = this.$refs.messageContainer;
            if (container) {
                setTimeout(() => {
                    container.scrollTop = container.scrollHeight;
                    this.showMessages = true; // Показываем сообщения только после скролла
                }, 50);
            }
        }
    },

    mounted() {
        if (this.isChatSelected) {
            this.showMessages = false;
            this.$nextTick(() => {
                this.scrollToBottom();
            });
        }
    }
}
</script>

<template>
    <v-card class="d-flex flex-column fill-height" rounded>
        <v-card-title v-if="isChatSelected">
            <h2 class="text-h5">{{ companion.name }}</h2>
            <v-divider></v-divider>
        </v-card-title>

        <v-card-text v-if="!isChatSelected" class="fill-height d-flex flex-column align-center justify-center">
            <v-icon size="80" color="grey">mdi-message-outline</v-icon>
            <div class="text-h6 mt-2">Выберите диалог слева</div>
        </v-card-text>

        <v-card-text v-else class="flex-grow-1 overflow-y-auto custom-scroll custom-height">
            <div
                class="flex-grow-1 overflow-y-auto custom-scroll custom-height"
                ref="messageContainer"
                v-show="showMessages"
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

        <v-card-actions v-if="isChatSelected" class="pa-3">
            <v-text-field
                label="Введите сообщение"
                outlined
                hide-details
                v-model="message"
                @keyup.enter.prevent="emitMessage"
            ></v-text-field>
            <v-btn @click="emitMessage" color="primary" type="button">Отправить</v-btn>
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
}
</style>
