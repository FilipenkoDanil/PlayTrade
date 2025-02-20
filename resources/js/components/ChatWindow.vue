<script>
export default {
    name: "ChatWindow",
    props: ['isChatSelected', 'messages']
}
</script>

<template>
    <v-card class="d-flex flex-column fill-height" rounded>
        <v-card-title v-if="isChatSelected">
            <h2 class="text-h5">Имя пользователя</h2>
            <v-divider></v-divider>
        </v-card-title>

        <v-card-text v-if="!isChatSelected" class="fill-height d-flex flex-column align-center justify-center">
            <v-icon size="80" color="grey">mdi-message-outline</v-icon>
            <div class="text-h6 mt-2">Выберите диалог слева</div>
        </v-card-text>

        <v-card-text v-else class="flex-grow-1 overflow-y-auto custom-scroll custom-height">
            <div
                v-for="message in messages"
                :key="message.id"
                :class="['d-flex', message.sender === 'user' ? 'justify-end' : 'justify-start']"
            >
                <v-card
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
        </v-card-text>

        <v-divider></v-divider>

        <v-card-actions v-if="isChatSelected" class="pa-3">
            <v-text-field
                label="Введите сообщение"
                outlined
                hide-details
            ></v-text-field>
            <v-btn color="primary">Отправить</v-btn>
        </v-card-actions>
    </v-card>
</template>

<style scoped>

</style>
