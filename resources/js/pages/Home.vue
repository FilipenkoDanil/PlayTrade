<script>
export default {
    name: "Home",
    data() {
        return {
            search: "",
        }
    },
}
</script>

<template>
    <v-row justify="center">
        <v-col cols="12" sm="6" md="4">
            <v-text-field
                v-model="search"
                prepend-inner-icon="mdi-magnify"
                placeholder="Search games"
            ></v-text-field>
        </v-col>
    </v-row>

    <!-- Список игр карточками -->
    <v-row>
        <v-col v-for="i in 10" cols="12" sm="6" md="4" lg="3">
            <v-card class="mx-auto" hover>
                <v-img src="https://picsum.photos/800" height="200px" cover></v-img>

                <v-card-title>Игра {{ i }}</v-card-title>

                <!-- Кликабельные категории -->
                <v-card-text>
                    <v-btn
                        rounded
                        size="small"
                        variant="outlined"
                        class="mr-1 mb-1"
                    >
                        категория
                    </v-btn>
                </v-card-text>
            </v-card>
        </v-col>
    </v-row>
</template>

<style scoped>

</style>
