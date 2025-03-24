<script>
export default {
    name: "Transactions",

    data() {
        return {
            transactions: [],
            selectedType: null,
            operationTypes: [
                {title: 'Все операции', value: null},
                {title: 'Заказ', value: 'App\\Models\\Deal'},
                {title: 'Пополнение', value: 'App\\Models\\Deposit'},
                {title: 'Вывод', value: 'App\\Models\\Withdrawal'},
            ],
            headers: [
                {title: 'Дата', value: 'created_at'},
                {title: 'Описание', value: 'description'},
                {title: 'Статус', value: 'status'},
                {title: 'Сумма', value: 'amount'},
            ],

            dialog: false,
            selectedTransaction: null,

            withdrawDialog: false,
            userBalance: 0,
            cardNumber: '',
            amount: 0,
            amountToReceive: 0,
            rules: {
                required: value => !!value || 'Обязательное поле',
                cardNumber: value => (value && value.length === 16) || 'Некорректный номер карты',
                minAmount: value => (value && value >= 50) || 'Минимальная сумма 50 ₴',
            },
        }
    },

    methods: {
        getTransactions() {
            axios.get('api/transactions')
                .then(r => this.transactions = r.data.data)
        },

        getUserBalance() {
            axios.get('api/user')
                .then(r => this.userBalance = r.data.balance)
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
                    return 'deep-purple'; // Pending
                case 6:
                    return 'green'; // Completed
                case 7:
                    return 'red'; // Failed
                case 8:
                    return 'red'; // Cancelled
                case 9:
                    return 'blue-grey-lighten-2'; // Refunded
                default:
                    return 'grey'; // Unknown
            }
        },

        openDialog(event, {item}) {
            this.selectedTransaction = item;
            this.dialog = true;
        },

        createWithdrawal() {
            axios.post('api/withdrawals', {
                requested_amount: this.amount,
                card_number: this.cardNumber
            })
                .then(() => {
                    this.getTransactions()
                    this.getUserBalance()
                    this.withdrawDialog = !this.withdrawDialog
                    this.cardNumber = ''
                    this.amount = 0
                    this.amountToReceive = 0
                })
        },

        cancelWithdrawal() {
            axios.post(`api/withdrawals/${this.selectedTransaction.transactable_id}/cancel`)
                .then(() => {
                    this.getTransactions()
                    this.dialog = !this.dialog
                })
        }
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

        isFormValid() {
            return this.cardNumber && this.amount && this.amount >= 50 && this.amount <= this.userBalance;
        },
    },

    watch: {
        amount(newVal) {
            if (newVal) {
                this.amountToReceive = (newVal * 0.95).toFixed(2)
            } else {
                this.amountToReceive = 0
            }
        },
    },

    mounted() {
        this.getTransactions()
        this.getUserBalance()
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
            <v-btn @click="withdrawDialog = !withdrawDialog; this.getUserBalance()" color="primary" variant="tonal">
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

            <span v-if="item.transactable_type === 'App\\Models\\Withdrawal'">
            Вывод #{{ item.transactable.id }}
         </span>

            <span v-if="item.transactable_type === 'App\\Models\\Deposit'">
            Пополнение #{{ item.transactable.deposit_id }}
         </span>
        </template>

        <template v-slot:item.status="{ item }">
            <v-chip :color="getStatusColor(item.transactable.status_id)" small>
                {{ item.transactable.status.title }}
            </v-chip>
        </template>

        <template v-slot:item.amount="{ item }">
              <span class="font-weight-bold">
                {{ item.amount > 0 ? `+${item.amount}` : item.amount }} ₴
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
              <router-link class="text-decoration-none cursor-pointer text-blue"
                           :to="{name: 'user.deal', params: {id: selectedTransaction.transactable.id}}">
                  Заказ #{{ selectedTransaction.transactable.id }}
              </router-link>
            </span>
                    <span v-else-if="selectedTransaction.transactable_type === 'App\\Models\\Deposit'">
              Пополнение
            </span>
                    <span v-else-if="selectedTransaction.transactable_type === 'App\\Models\\Withdrawal'">
              Вывод #{{ selectedTransaction.transactable.id }}
            </span>
                </p>

                <p v-if="selectedTransaction.transactable_type === 'App\\Models\\Withdrawal'"><strong>Карта: </strong>
                    <span>{{ selectedTransaction.transactable.card_number }}</span>
                </p>

                <p><strong>Статус: </strong>
                    <v-chip :color="getStatusColor(selectedTransaction.transactable.status_id)" small>
                        {{ selectedTransaction.transactable.status.title }}
                    </v-chip>
                </p>

                <p><strong>Сумма: </strong>
                    <span
                        :class="{'text-green': selectedTransaction.amount > 0, 'text-red': selectedTransaction.amount < 0}"
                        class="font-weight-bold">
              {{ selectedTransaction.amount > 0 ? `+${selectedTransaction.amount}` : selectedTransaction.amount }} ₴
            </span>
                </p>

                <p v-if="selectedTransaction.transactable_type === 'App\\Models\\Withdrawal'">
                    <strong>Сумма к получению: </strong>
                    <span
                        class="font-weight-bold text-green">
              {{ selectedTransaction.transactable.payout_amount }} ₴
            </span>
                </p>
            </v-card-text>

            <v-card-actions>
                <v-btn v-if="selectedTransaction.transactable_type === 'App\\Models\\Withdrawal' && selectedTransaction.transactable.status_id === 5"
                       color="red"
                       @click="cancelWithdrawal"
                >Отменить вывод</v-btn>
                <v-spacer></v-spacer>
                <v-btn color="primary" @click="dialog = false">Закрыть</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>


    <v-dialog v-model="withdrawDialog" max-width="400" persistent>
        <v-card class="rounded-lg">
            <v-card-title class="headline primary white--text">
                <v-icon left>mdi-bank-transfer-out</v-icon>
                Вывод средств
            </v-card-title>
            <v-card-text class="pt-4">
                <v-alert type="info" class="mb-4">
                    Ваш текущий баланс: <strong>{{ userBalance }} ₴</strong>
                </v-alert>

                <v-text-field
                    v-model="cardNumber"
                    outlined
                    label="Номер карты"
                    placeholder="0000 0000 0000 0000"
                    prepend-icon="mdi-credit-card"
                    :rules="[rules.required, rules.cardNumber]"
                ></v-text-field>

                <v-text-field
                    v-model="amount"
                    outlined
                    label="Сумма"
                    placeholder="Введите сумму"
                    hint="Комиссия 5%"
                    prepend-icon="mdi-cash"
                    :rules="[rules.required, rules.minAmount]"
                    type="number"
                    suffix="₴"
                    min="50"
                ></v-text-field>

                <v-text-field
                    v-model="amountToReceive"
                    outlined
                    disabled
                    label="К получению"
                    placeholder="0"
                    prepend-icon="mdi-wallet"
                    suffix="₴"
                    readonly
                ></v-text-field>
            </v-card-text>
            <v-card-actions class="pa-4">
                <v-spacer></v-spacer>
                <v-btn color="secondary" @click="withdrawDialog = false">Отмена</v-btn>
                <v-btn color="primary" @click="createWithdrawal" :disabled="!isFormValid">
                    Подтвердить
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<style scoped>

</style>
