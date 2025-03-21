<script>
import ChatList from "@/components/ChatList.vue";
import ChatWindow from "@/components/ChatWindow.vue";

export default {
    name: "Chat",
    components: {ChatWindow, ChatList},
    data() {
        return {
            isChatSelected: false,
            selectedChatId: null,

            newMessage: "",
            messages: [],
            chats: [],

            currentUserId: parseInt(localStorage.getItem("userId")),
            selectedCompanion: [],
            echoChannel: null,
        };
    },

    methods: {
        getChats() {
            axios.get("api/chats").then((r) => {
                this.chats = r.data;
            });
        },

        markMessagesAsRead(chatId) {
            axios.post(`/api/chats/${chatId}/mark-read`, {
                user_id: this.currentUserId,
            }).then(() => {
                const chat = this.chats.find(chat => chat.id === chatId);
                if (chat) {
                    chat.unread_count = 0;
                }
            });
        },

        showChat(chatId) {
            this.selectedChatId = chatId;

            axios.get(`api/chats/${chatId}`).then((r) => {
                this.messages = r.data.map((message) => ({
                    id: message.id,
                    text: message.message,
                    time: message.created_at,
                    sender:
                        message.type === "moder"
                            ? "moder"
                            : message.user_id === this.currentUserId
                                ? "user"
                                : "other",
                    name: message.user.name,
                    type: message.type,
                }));
                this.isChatSelected = true;

                const chat = this.chats.find((chat) => chat.id === chatId);
                if (chat) {
                    this.markMessagesAsRead(chatId);
                    this.selectedCompanion = chat.companion;
                }
            });
        },

        sendMessage(message) {
            axios
                .post("api/messages", {
                    chat_id: this.selectedChatId,
                    message: message,
                })
                .then((r) => {
                    this.messages.push({
                        id: r.data.id,
                        text: r.data.message,
                        time: r.data.created_at,
                        sender: "user",
                        name: r.data.user.name,
                    });

                    const chatIndex = this.chats.findIndex(chat => chat.id === this.selectedChatId);
                    if (chatIndex !== -1) {
                        const updatedChat = {
                            ...this.chats[chatIndex],
                            last_message: {
                                text: r.data.message,
                                date: r.data.created_at,
                            },
                        };

                        this.chats.splice(chatIndex, 1);
                        this.chats.unshift(updatedChat);
                    }
                });
        },

        subscribeToGlobalUserChannel() {
            window.Echo.private(`user.${this.currentUserId}`).listen(
                '.chat-message', () => {
                    this.getChats()
                });
        },
    },

    watch: {
        selectedChatId(newVal, oldVal) {
            window.Echo.leave(`chat.${oldVal}`)

            window.Echo.private(`chat.${newVal}`)
                .listen(".chat-message", (data) => {
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

                    this.markMessagesAsRead(this.selectedChatId);
                });
        }
    },

    mounted() {
        this.getChats();
        this.subscribeToGlobalUserChannel();
    },

    beforeUnmount() {
        window.Echo.leave(`user.${this.currentUserId}`);
        window.Echo.leave(`chat.${this.selectedChatId}`);
    }
};
</script>


<template>
    <v-row class="fill-height">
        <v-col cols="3" class="d-flex flex-column">
            <ChatList @selectChat="showChat" :selectedChatId="selectedChatId" :chats="chats"></ChatList>
        </v-col>


        <v-col cols="9" class="d-flex flex-column">
            <ChatWindow @sendMessage="sendMessage" :messages="messages" :companion="selectedCompanion"
                        :isChatSelected="isChatSelected"></ChatWindow>
        </v-col>
    </v-row>
</template>

<style scoped>

</style>
