<script>
export default {
    name: "Offers",

    data() {
        return {
            offers: [],
            headers: [
                {title: "Название", value: "title", align: "start"},
                {title: "Кол-во", value: "amount", align: "center"},
                {title: "Цена", value: "price", align: "center"},
                {title: "Игра", value: "game.title", align: "center"},
                {title: "Категория", value: "category.title", align: "center"},
                {title: "Статус", value: "is_active", align: "center"}
            ]
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
    <h1 class="mb-4 text-center">🎮 Мои предложения</h1>

    <v-data-table
        :items="offers"
        :headers="headers"
        item-value="id"
        class="elevation-2 custom-table"
        @click:row="editOffer"
    >

        <template v-slot:item.amount="{ item }">
            <v-chip color="indigo" dark>
                <v-icon start>mdi-package-variant-closed</v-icon>
                {{ item.amount }} {{ item.category.unit.title }}
            </v-chip>
        </template>

        <template v-slot:item.price="{ item }">
            <v-chip color="green" dark>
                <v-icon start>mdi-cash</v-icon>
                {{ item.price }}
            </v-chip>
        </template>

        <template v-slot:item.game.title="{ item }">
            <v-chip color="blue lighten-2" dark>
                🎮 {{ item.category?.game?.title || '—' }}
            </v-chip>
        </template>

        <template v-slot:item.category.title="{ item }">
            <v-chip color="purple lighten-2" dark>
                {{ item.category?.title || '—' }}
            </v-chip>
        </template>

        <template v-slot:item.is_active="{ item }">
            <v-chip :color="item.is_active ? 'green' : 'red'" dark>
                <v-icon start>{{ item.is_active ? 'mdi-check' : 'mdi-close' }}</v-icon>
                {{ item.is_active ? 'Активно' : 'Не активно' }}
            </v-chip>
        </template>
    </v-data-table>
</template>

<style scoped>
h1 {
    font-size: 24px;
    font-weight: bold;
}

.custom-table {
    border-radius: 12px;
    overflow: hidden;
}


.v-data-table >>> tbody tr:hover {
    background-color: rgba(0, 0, 0, 0.03);
    transition: 0.2s;
    cursor: pointer;
}
</style>
