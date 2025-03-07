<script>
export default {
    name: "ChatList",

    props: ['chats']
}
</script>

<template>
    <v-list class="overflow-y-auto custom-scroll custom-height" rounded>
        <template
            v-for="chat in chats"
            :key="chat.id">
            <v-list-item
                class="pa-4"
                link
                @click="$emit('selectChat', chat.id)"
                v-if="chat.last_message"
            >
                <template v-slot:prepend>
                    <v-avatar color="primary" size="40">
                        <v-icon dark>mdi-account</v-icon>
                    </v-avatar>
                </template>

                <v-list-item-title class="d-flex align-center">
                    <span class="font-weight-bold">{{ chat.companion?.name }}</span>
                    <span class="text-caption text-grey ml-2">{{ chat.last_message?.date }}</span>
                </v-list-item-title>

                <v-list-item-subtitle class="text-truncate">
                    {{ chat.last_message.text }}
                </v-list-item-subtitle>

                <template v-slot:append>
                    <v-badge v-if="chat.unread_count > 0" :content="chat.unread_count" inline></v-badge>
                </template>
            </v-list-item>
        </template>
    </v-list>
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
    height: 90vh;
}
</style>
