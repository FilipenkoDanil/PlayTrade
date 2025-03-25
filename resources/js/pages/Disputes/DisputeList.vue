<script>
import axios from "axios";

export default {
    name: "DisputeList",
    data() {
        return {
            disputeDeals: [],
            headers: [
                { title: 'Заказ', value: 'id' },
                { title: 'Статус', value: 'status_id' },
                { title: 'Дата', value: 'updated_at' },
            ]
        }
    },

    methods: {
        getDisputeDeals() {
            axios.get('api/disputes')
                .then(r => this.disputeDeals = r.data.data)
        },

        goToDispute(_, item) {
            this.$router.push({name: 'dispute.info', params: {id: item.item.id}})
        }
    },

    mounted() {
        this.getDisputeDeals()
    }
}
</script>

<template>
    <v-row>
        <v-col cols="12">
            <v-data-table @click:row="goToDispute" :items="disputeDeals" :headers="headers" hover>
                <template v-slot:item.id="{ item }">
                    #{{ item.id }}
                </template>

                <template v-slot:item.status_id>
                    <v-chip color="orange">Disputed</v-chip>
                </template>
            </v-data-table>
        </v-col>
    </v-row>
</template>

<style scoped>

</style>
