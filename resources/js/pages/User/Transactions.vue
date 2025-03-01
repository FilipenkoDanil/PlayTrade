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
            dialog: false, // Открытие/закрытие диалогового окна
            selectedTransaction: null, // Выбранная транзакция для отображения в диалоге
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

        openDialog(event, { item }) {
            this.selectedTransaction = item;
            this.dialog = true;
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

    <v-row class="d-flex justify-space-between">
        <v-col cols="auto">
            <v-select
                v-model="selectedType"
                :items="operationTypes"
                density="compact"
                class="mb-4"
                hide-details
            ></v-select>
        </v-col>

        <v-col cols="auto">
            <v-btn color="primary" variant="tonal">
                Вывести деньги
            </v-btn>
        </v-col>
    </v-row>

    <v-data-table
        :headers="headers"
        :items="filteredTransactions"
        :items-per-page="25"
        @click:row="openDialog"
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

    <!-- Диалоговое окно -->
    <v-dialog v-model="dialog" max-width="600">
        <v-card v-if="selectedTransaction">
            <v-card-title>Информация об операции</v-card-title>
            <v-card-text>
                <p><strong>Дата:</strong> {{ selectedTransaction.created_at }}</p>

                <p><strong>Описание:</strong>
                    <span v-if="selectedTransaction.transactable_type === 'App\\Models\\Deal'">
              <router-link class="text-decoration-none cursor-pointer text-blue" :to="{name: 'user.deal', params: {id: selectedTransaction.transactable.id}}">
                  Заказ #{{ selectedTransaction.transactable.id }}
              </router-link>
            </span>
                    <span v-else-if="selectedTransaction.transactable_type === 'App\\Models\\Deposit'">
              Пополнение
            </span>
                    <span v-else-if="selectedTransaction.transactable_type === 'App\\Models\\Withdrawal'">
              Вывод
            </span>
                </p>

                <p><strong>Статус: </strong>
                    <v-chip :color="getStatusColor(selectedTransaction.transactable.status_id)" small>
                        {{ selectedTransaction.transactable.status.title }}
                    </v-chip>
                </p>

                <p><strong>Сумма: </strong>
                    <span :class="{'text-green': selectedTransaction.amount > 0, 'text-red': selectedTransaction.amount < 0}" class="font-weight-bold">
              {{ selectedTransaction.amount > 0 ? `+${selectedTransaction.amount}` : selectedTransaction.amount }}
            </span>
                </p>
            </v-card-text>

            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="primary" @click="dialog = false">Закрыть</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<style scoped>

</style>
