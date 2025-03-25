<script>
import axios from "axios";

export default {
    name: "WithdrawalList",

    data() {
        return {
            withdrawals: []
        }
    },

    methods: {
        getWithdrawals() {
            axios.get('api/moder/withdrawals')
                .then(r => this.withdrawals = r.data)
        },

        approveWithdrawal(id) {
            axios.post(`api/moder/withdrawals/${id}/approve`)
                .then(() => this.getWithdrawals())
        }
    },

    mounted() {
        this.getWithdrawals()
    }
}
</script>

<template>
    <v-data-table
        :items="withdrawals"
        :headers="[
            { title: 'ID', value: 'id' },
            { title: 'Пользователь', value: 'user.name' },
            { title: 'Сумма к выводу', value: 'payout_amount' },
            { title: 'Карта', value: 'card_number' },
            { title: 'Создана', value: 'created_at' },
            { title: 'Действия', value: 'actions' }
        ]">

        <template v-slot:item.user.name="{ item }">
            <router-link :to="{name: 'user.profile', params:{id: item.user.id}}" style="text-decoration: none; color: inherit;">
                {{ item.user.name }}
            </router-link>
        </template>

        <template v-slot:item.actions="{ item }">
            <v-btn color="primary" @click="approveWithdrawal(item.id)">
                Выполнено
            </v-btn>
        </template>
    </v-data-table>
</template>

<style scoped>

</style>
