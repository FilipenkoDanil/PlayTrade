<script>
export default {
    name: "Offers",

    data() {
        return {
            offers: [],
            groupBy: { key: 'game.category.title', order: 'asc'},
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

    computed: {
        groupedOffers() {
            const groups = {}
            this.offers.forEach(offer => {
                const key = offer.category?.game?.title || 'Без игры'
                if (!groups[key]) {
                    groups[key] = []
                }
                groups[key].push(offer)
            })
            return groups
        }
    },

    mounted() {
        this.getOffers()
    }
}
</script>

<template>
    <h1 class="mb-4 text-center">🎮 Мои предложения</h1>

    <div v-if="Object.keys(groupedOffers).length > 0">
        <div v-for="(group, gameTitle) in groupedOffers" :key="gameTitle">
            <v-card class="mb-5">
                <v-card-title class="text-h6">{{ gameTitle }}</v-card-title>
                <v-card-text>
                    <v-data-table
                        :items="group"
                        :headers="headers"
                        item-value="id"
                        @click:row="editOffer"
                        hide-default-footer
                        :items-per-page="100"
                        hover
                    >
                        <template v-slot:item.title="{ item }">
                            <span v-if="item.title">{{ item.title }}</span>
                            <span v-else>—</span>
                        </template>

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
                </v-card-text>
            </v-card>
        </div>
    </div>
    <v-alert v-else type="info" variant="tonal" class="mt-4">
        У вас пока нет предложений.
    </v-alert>
</template>

<style scoped>

</style>
