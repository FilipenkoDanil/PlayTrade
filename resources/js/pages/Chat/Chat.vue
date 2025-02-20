<script>
import ChatList from "@/components/ChatList.vue";
import ChatWindow from "@/components/ChatWindow.vue";

export default {
    name: "Chat",
    components: {ChatWindow, ChatList},
    data() {
        return {
            isChatSelected: false,
            newMessage: "",
            messages: [
                { id: 1, text: "Привет!", time: "10:45", sender: "user" },
                { id: 2, text: "Как дела?", time: "10:46", sender: "other" },
                { id: 3, text: "Все отлично, спасибо!", time: "10:47", sender: "user" },
                { id: 4, text: "Что нового?", time: "10:48", sender: "other" },
            ]
        };
    },
}
</script>

<template>
    <v-row class="fill-height">
        <v-col cols="3" class="d-flex flex-column">
            <ChatList @click="isChatSelected = !isChatSelected"></ChatList>
        </v-col>


        <v-col cols="9" class="d-flex flex-column">
            <ChatWindow :messages="messages" :isChatSelected="isChatSelected"></ChatWindow>
        </v-col>
    </v-row>
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
