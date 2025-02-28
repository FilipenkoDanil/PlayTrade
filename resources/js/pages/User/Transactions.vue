<script>
export default {
    name: "Transactions",

    data() {
        return {
            transactions: [],
            selectedType: null,
            operationTypes: [
                { title: 'Все операции', value: null },
                { title: 'Заказ', value: 'App\\Models\\Deal' },
                { title: 'Пополнение', value: 'App\\Models\\Deposit' },
                { title: 'Вывод', value: 'App\\Models\\Withdrawal' },
            ],
            headers: [
                { title: 'Дата', value: 'created_at' },
                { title: 'Описание', value: 'description' },
                { title: 'Статус', value: 'status' },
                { title: 'Сумма', value: 'amount' },
            ],
            loading: false,

        }
    },

    methods: {
        getTransactions() {
            axios.get('api/transactions')
                .then(r => this.transactions = r.data.data)
        },

        getStatusColor(statusId) {
            switch (statusId) {
                case 1:
                    return 'blue'; // In Progress
                case 2:
                    return 'green'; // Completed
                case 3:
                    return 'red'; // Cancelled
                case 4:
                    return 'orange'; // Disputed
                case 5:
                    return 'grey'; // Pending
                case 6:
                    return 'green'; // Completed
                case 7:
                    return 'red'; // Failed
                case 8:
                    return 'red'; // Cancelled
                case 9:
                    return 'blue'; // Refunded
                default:
                    return 'grey'; // Unknown
            }
        },
    },

    computed: {
        filteredTransactions() {
            if (!this.selectedType) {
                return this.transactions
            }
            return this.transactions.filter(
                transaction => transaction.transactable_type === this.selectedType
            );
        },
    },

    mounted() {
        this.getTransactions()
    }
}
</script>

<template>
    <v-select
        v-model="selectedType"
        :items="operationTypes"
        label="Тип операции"
        outlined
        class="mb-4"
    ></v-select>

    <v-data-table
        :headers="headers"
        :items="filteredTransactions"
        :loading="loading"
        :items-per-page="25"
    >
        <template v-slot:item.created_at="{ item }">
            {{ item.created_at }}
        </template>

        <template v-slot:item.description="{ item }">
      <span v-if="item.transactable_type === 'App\\Models\\Deal'">
        Заказ #{{ item.transactable.id }}
      </span>
        </template>

        <template v-slot:item.status="{ item }">
            <v-chip :color="getStatusColor(item.transactable.status_id)" small>
                {{ item.transactable.status.title }}
            </v-chip>
        </template>

        <template v-slot:item.amount="{ item }">
              <span class="font-weight-bold" >
                {{ item.amount > 0 ? `+${item.amount}` : item.amount }}
              </span>
        </template>

    </v-data-table>
</template>

<style scoped>

</style>
