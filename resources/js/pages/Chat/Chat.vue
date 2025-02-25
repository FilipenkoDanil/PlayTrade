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

            currentUserId: null,
            selectedCompanion: []
        }
    },

    methods: {
        getChats() {
            axios.get('api/chats')
                .then(r => {
                    this.chats = r.data
                })
        },

        getCurrentUser() {
            axios.get('/api/user')
                .then(r => this.currentUserId = r.data.id)
        },

        showChat(chatId) {
            this.selectedChatId = chatId;
            axios.get(`api/chats/${chatId}`)
                .then(r => {
                    this.messages = r.data.map(message => ({
                        id: message.id,
                        text: message.message,
                        time: message.created_at,
                        sender: message.user_id === this.currentUserId ? 'user' : 'other',
                        type: message.type
                    }));
                    this.isChatSelected = true;

                    const chat = this.chats.find(chat => chat.id === chatId);
                    if (chat) {
                        this.selectedCompanion = chat.companion;
                    }
                });
        },

        sendMessage(message) {
            axios.post('api/messages', {chat_id: this.selectedChatId, message: message})
                .then(r => {
                    this.messages.push({
                        id: r.data.id,
                        text: r.data.message,
                        time: r.data.created_at,
                        sender: 'user'
                    });
                });
        }
    },

    mounted() {
        this.getChats()
        this.getCurrentUser()
    }
}
</script>

<template>
    <v-row class="fill-height">
        <v-col cols="3" class="d-flex flex-column">
            <ChatList @selectChat="showChat" :chats="chats"></ChatList>
        </v-col>


        <v-col cols="9" class="d-flex flex-column">
            <ChatWindow @sendMessage="sendMessage" :messages="messages" :companion="selectedCompanion"
                        :isChatSelected="isChatSelected"></ChatWindow>
        </v-col>
    </v-row>
</template>

<style scoped>

</style>
