<script>
export default {
    name: "Orders",
    data() {
        return {
            deals: [],
            headers: [
                {title: 'Дата', value: 'created_at'},
                {title: 'Заказ', value: 'id'},
                {title: 'Название', value: 'offer_title'},
                {title: 'Продавец', value: 'seller.name'},
                {title: 'Количество', value: 'quantity'},
                {title: 'Сумма', value: 'price'},
                {title: 'Статус', value: 'status_id'}
            ],
            selectedDeal: null,
            dialog: false,
        }
    },

    methods: {
        getOrders() {
            axios.get('/api/orders')
                .then(r => {
                    this.deals = r.data.data
                })
        },

        goToDeal(id) {
            this.$router.push({name: 'user.deal', params: {id: id}})
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
        this.getOrders()
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

        <template v-slot:item.offer_title="{ item }">
            {{ item.offer_title }}
            <br>
            <span class="text-disabled">{{ item.offer_game }} - {{ item.offer_category }}</span>
        </template>

        <template v-slot:item.quantity="{ item }">
            {{ item.quantity }}{{ item.offer_unit }}
        </template>

        <template v-slot:item.price="{ item }">
            <span class="text-red-accent-2">-{{ item.price }} ₴</span>
        </template>
    </v-data-table>

    <v-dialog v-model="dialog" max-width="600px">
        <v-card>
            <v-card-title>
                Заказ #{{ selectedDeal.id }}
            </v-card-title>
            <v-card-text>
                <p><strong>Игра:</strong> {{ selectedDeal.offer_game }}</p>
                <p><strong>Категория:</strong> {{ selectedDeal.offer_category }}</p>
                <v-divider class="my-3"></v-divider>
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
                    <strong>Сумма: </strong> {{ selectedDeal.price }} ₴
                    <strong>Количество: </strong> {{ selectedDeal.quantity }}{{ selectedDeal.offer_unit }}
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
                <v-btn @click="goToDeal(selectedDeal.id)">
                    Подробнее
                </v-btn>
                <v-btn color="primary" @click="dialog = false">Закрыть</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<style scoped>

</style>
