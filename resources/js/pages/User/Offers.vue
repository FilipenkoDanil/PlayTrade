<script>
import axios from "axios";

export default {
    name: "Offers",

    data() {
        return {
            offers: []
        }
    },

    methods: {
        getOffers() {
            axios.get('api/offers')
                .then(r => this.offers = r.data.data)
        },

        editOffer(_, item) {
            this.$router.push({name: 'offer.edit', params: {id: item.item.id}})
        }
    },

    mounted() {
        this.getOffers()
    }
}
</script>

<template>
    <h1>My offers</h1>
    <v-data-table :items="offers" @click:row="editOffer"></v-data-table>
</template>

<style scoped>

</style>
