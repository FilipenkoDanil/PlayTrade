<script>
export default {
    name: "Home"
}
</script>

<template>
    <v-row>
        <v-col cols="12" sm="4" md="2">
            <v-text-field prepend-inner-icon="mdi-magnify" placeholder="Search games"></v-text-field>
        </v-col>
        <v-divider thickness="10"></v-divider>
    </v-row>
    <v-row v-for="i in 10">
        <v-col cols="12">
            <h2 class="text-h4">Game {{ i }}</h2>
        </v-col>
        <v-col v-for="i in 6" :key="i" cols="12" sm="4" md="3" xl="2">
            <v-hover v-slot="{ isHovering, props }">
                <router-link :to="{name: 'category'}">
                    <v-card image="https://picsum.photos/600"
                            height="200"
                            min-width="200"
                            class="mx-auto"
                            v-bind="props"
                            link
                    >
                        <v-overlay
                            :model-value="!!isHovering"
                            class="align-center justify-center"
                            scrim="indigo"
                            contained
                        >
                            <span class="text-uppercase text-h5 text-white">Gold</span>
                        </v-overlay>
                    </v-card>
                </router-link>
            </v-hover>
        </v-col>
        <v-divider></v-divider>
    </v-row>
</template>

<style scoped>

</style>
