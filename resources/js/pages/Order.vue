<script>
export default {
    name: "Order",
    data() {
        return {
            deals: [],
            headers: [
                {title: 'Дата', value: 'created_at'},
                {title: 'Заказ', value: 'id'},
                {title: 'Название', value: 'offer_title'},
                {title: 'Количество', value: 'quantity'},
                {title: 'Сумма', value: 'price'},
                {title: 'Статус', value: 'status_id'}
            ],
            selectedDeal: null,
            dialog: false,
        }
    },

    methods: {
        getDeals() {
            axios.get('/api/deals')
                .then(r => {
                    this.deals = r.data.data
                })
        },
        confirmDeal(dealId) {
            axios.patch(`/api/deals/${dealId}/confirm`)
                .then(() => {
                    this.getDeals()
                    this.dialog = false
                })
        },
        getStatusText(statusId) {
            switch (statusId) {
                case 1:
                    return 'In Progress';
                case 2:
                    return 'Completed';
                case 3:
                    return 'Canceled';
                case 4:
                    return 'Disputed';
                default:
                    return 'Unknown';
            }
        },
        getStatusColor(statusId) {
            switch (statusId) {
                case 1:
                    return 'blue';
                case 2:
                    return 'green';
                case 3:
                    return 'red';
                case 4:
                    return 'orange';
                default:
                    return 'grey';
            }
        },
        openDialog(_, item) {
            this.selectedDeal = item.item;
            this.dialog = true;
        }
    },

    mounted() {
        this.getDeals()
    }
}
</script>

<template>
    <v-data-table
        :headers="headers"
        :items="deals"
        item-value="id"
        items-per-page="25"
        @click:row="openDialog"
    >
        <template v-slot:item.status_id="{ item }">
            <v-chip
                :color="getStatusColor(item.status_id)"
            >
                {{ getStatusText(item.status_id) }}
            </v-chip>
        </template>

        <template v-slot:item.id="{ item }">
            #{{ item.id }}
        </template>

        <template v-slot:item.price="{ item }">
            <span class="text-red-accent-2">-{{ item.price }}</span>
        </template>
    </v-data-table>

    <v-dialog v-model="dialog" max-width="600px">
        <v-card>
            <v-card-title>
                Заказ #{{ selectedDeal.id }}
            </v-card-title>
            <v-card-text>
                <p><strong>Название оффера:</strong> {{ selectedDeal.offer_title }}</p>
                <p><strong>Описание:</strong> {{ selectedDeal.offer_description }}</p>

                <div v-if="selectedDeal.offer_attributes &&  JSON.parse(selectedDeal.offer_attributes).length">
                    <v-divider class="my-3"></v-divider>
                    <p><strong>Атрибуты:</strong></p>
                    <v-list dense>
                        <v-list-item
                            v-for="attr in JSON.parse(selectedDeal.offer_attributes)"
                            :key="attr.id"
                        >
                            <v-list-item-title>
                                <strong>{{ attr.title }}</strong>: {{ attr.pivot.value }}
                            </v-list-item-title>
                        </v-list-item>
                    </v-list>
                </div>

                <p v-if="selectedDeal.offer_server">
                    <v-divider class="my-3"></v-divider>
                    <strong>Сервер:</strong> {{ JSON.parse(selectedDeal.offer_server).title }}
                </p>

                <p>
                    <v-divider class="my-3"></v-divider>
                    <strong>Сумма: </strong> {{ selectedDeal.price }}
                    <strong>Количество: </strong> {{ selectedDeal.quantity }}
                </p>

                <p>
                    <v-divider class="my-3"></v-divider>
                    <strong>Статус: </strong>
                    <v-chip :color="getStatusColor(selectedDeal.status_id)">{{
                            getStatusText(selectedDeal.status_id)
                        }}
                    </v-chip>
                </p>
            </v-card-text>

            <v-card-actions>
                <v-btn @click="confirmDeal(selectedDeal.id)" v-if="selectedDeal.status_id === 1" color="green">
                    Подтвердить сделку
                </v-btn>
                <v-btn color="primary" @click="dialog = false">Закрыть</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<style scoped>

</style>
