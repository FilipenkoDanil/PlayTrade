<script>
export default {
    name: "ChatList"
}
</script>

<template>
    <v-list class="overflow-y-auto custom-scroll" style="height: 90vh;" rounded>
        <v-list-item
            class="pa-4"
            v-for="i in 1"
            :key="i"
            link
            @click="isChatSelected = true"
        >
            <template v-slot:prepend>
                <v-avatar color="primary" size="40">
                    <v-icon dark>mdi-account</v-icon>
                </v-avatar>
            </template>

            <v-list-item-title class="d-flex align-center">
                <span class="font-weight-bold">Имя пользователя {{ i }}</span>
                <span class="text-caption text-grey ml-2">10:4{{ i }}</span>
            </v-list-item-title>

            <v-list-item-subtitle class="text-truncate">
                Это текст сообщения, который может быть очень длинным и должен обрезаться...
            </v-list-item-subtitle>

            <template v-slot:append>
                <v-badge v-if="i % 2" :content="i" inline></v-badge>
            </template>
        </v-list-item>
    </v-list>
</template>

<style scoped>

</style>
