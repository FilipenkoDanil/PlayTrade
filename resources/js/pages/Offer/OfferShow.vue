<script>
import Chat from "@/components/Chat.vue";

export default {
    components: {Chat},
    data() {
        return {
            offer: {
                price: 0
            },
            quantity: 0,
            reviews: [],
            showSnack: false,
            snackOptions: {
                color: 'success',
                text: 'Сделка успешно создана.'
            },
            userBalance: 0,
        };
    },

    methods: {
        getOffer() {
            axios.get(`api/offers/${this.$route.params.id}`)
                .then(res => {
                    this.offer = res.data.data;
                    this.reviews = res.data[0];
                });
        },

        createDeal() {
            if (this.offer.seller_id == localStorage.getItem('userId')) {
                this.snackOptions.text = 'Вы не можете оформить сделку.'
                this.snackOptions.color = 'error'
                this.showSnack = true
                return
            }

            axios.post('api/deals', {
                quantity: this.quantity,
                offer_id: this.offer.id
            })
                .then(() => {
                    this.snackOptions.text = 'Сделка успешно создана.';
                    this.snackOptions.color = 'success';
                    this.showSnack = true;
                    this.getUserBalance()
                    this.quantity = 0
                })
                .catch(err => {
                    this.snackOptions.text = err.response.data.error;
                    this.snackOptions.color = 'red';
                    this.showSnack = true;
                });
        },

        createPayment() {
            if (this.offer.seller_id == localStorage.getItem('userId')) {
                this.snackOptions.text = 'Вы не можете оформить сделку.'
                this.snackOptions.color = 'error'
                this.showSnack = true
                return
            }

            axios.post('api/payment/create', {
                amount: this.missingMoney
            })
                .then(r => {
                    console.log(r)
                    const form = document.createElement("form");
                    form.method = "POST";
                    form.action = r.data.payment_url;
                    form.target = "_blank";

                    for (const [key, value] of Object.entries(r.data.payment_data)) {
                        const input = document.createElement("input");
                        input.type = "hidden";
                        input.name = key;
                        input.value = Array.isArray(value) ? value.join(",") : value;
                        form.appendChild(input);
                    }

                    document.body.appendChild(form);
                    form.submit();
                    document.body.removeChild(form);

                    window.Echo.private(`deposit.${r.data.payment_data.orderReference}`)
                        .listen('.deposit-success', () => {
                            this.getUserBalance()
                        })
                });
        },

        getUserBalance() {
            if (localStorage.getItem('isAuth')) {
                axios.get('api/user')
                    .then(r => this.userBalance = r.data.balance);
            }
        }
    },

    mounted() {
        this.getOffer();
        this.getUserBalance();
    },

    computed: {
        totalPrice() {
            return (this.quantity * this.offer.price).toFixed(2);
        },

        missingMoney() {
            return (this.totalPrice - this.userBalance).toFixed(2);
        }
    },
}
</script>

<template>
    <v-row justify="center">
        <v-col cols="12" md="5">
            <v-card class="pa-4 mb-6" elevation="2">
                <v-btn class="mb-1" variant="text" color="primary" @click="$router.push({name: 'category', params: {id: offer.category.id}})">
                    <v-icon left>mdi-arrow-left</v-icon>
                    {{ offer.category?.title }}
                </v-btn>

                <v-row class="my-2">
                    <v-divider></v-divider>
                    <v-col cols="12">
                        <router-link class="text-decoration-none text-white"
                                     :to="{name: 'user.profile', params: {id: offer.seller?.id}}">
                            <v-avatar
                                size="50"
                                class="mr-2"
                                :class="offer.seller?.is_online ? 'online-avatar' : 'offline-avatar'"
                            >
                                <v-img src="https://picsum.photos/500" alt="Avatar"></v-img>
                            </v-avatar>

                            <span class="ml-2 text-subtitle-1 font-weight-bold mt-2">
                                {{ offer.seller?.name }}
                            </span>
                        </router-link>
                    </v-col>
                    <v-divider></v-divider>
                </v-row>

                <v-row  v-if="offer.category?.type === 1">
                    <v-col>
                        <h1 class="text-h5 font-weight-bold mb-4">
                            {{ offer.title }}
                        </h1>

                        <p class="text-body-2 mb-4">
                            {{ offer.description }}
                        </p>
                    </v-col>
                    <v-divider></v-divider>
                </v-row>


                <v-row>
                    <v-col>
                        <v-chip class="ma-1" color="deep-purple-accent-1" variant="outlined">
                            Игра:
                            {{ offer.category?.game.title }}
                        </v-chip>
                    </v-col>
                </v-row>

                <v-row v-if="offer.category?.type === 1">
                    <v-col cols="12" sm="6" v-for="attribute in offer.attributes" :key="attribute.title">
                        <v-chip class="ma-1" color="primary" variant="outlined">
                            {{ attribute.title }}: {{ attribute.value }}
                        </v-chip>
                    </v-col>
                </v-row>

                <v-row v-if="offer.server">
                    <v-col>
                        <v-chip class="ma-1" color="secondary" variant="outlined">
                            Сервер: {{ offer.server.title }}
                        </v-chip>
                    </v-col>
                </v-row>

                <v-row>
                    <v-col>
                        <v-chip class="ma-1" color="warning" variant="outlined">
                            Наличие: {{ offer.amount }}{{ offer.category?.unit.title }}
                        </v-chip>
                    </v-col>
                </v-row>


                <v-row class="mt-4">
                    <v-divider></v-divider>
                    <v-col cols="12" class="text-center">
                        <h3 class="text-body-1 font-weight-bold my-4">{{ offer.price }} ₴ за 1{{
                                offer.category?.unit.title
                            }}</h3>

                        <v-row align="center" justify="center">
                            <v-col cols="5" class="text-center">
                                <v-text-field
                                    v-model="quantity"
                                    type="number"
                                    min="1"
                                    :max="offer.amount"
                                    variant="outlined"
                                    hide-details
                                    density="compact"
                                    :suffix="offer.category?.unit.title"
                                    label="Получу"
                                ></v-text-field>
                            </v-col>

                            <v-col cols="2" class="text-center">
                                <v-icon size="24">mdi-swap-horizontal</v-icon>
                            </v-col>

                            <v-col cols="5" class="text-center">
                                <v-text-field
                                    :model-value="totalPrice"
                                    readonly
                                    variant="outlined"
                                    hide-details
                                    density="compact"
                                    suffix="₴"
                                    class="text-end"
                                    label="Заплачу"
                                ></v-text-field>
                            </v-col>
                        </v-row>

                        <v-btn v-if="missingMoney <= 0" @click="createDeal" color="indigo-darken-1" class="mt-3" block
                               large
                               :disabled="!quantity">
                            <v-icon left>mdi-cart</v-icon>
                            Купить
                        </v-btn>

                        <template v-else>
                            <v-alert type="error" variant="tonal" class="mt-3" icon="mdi-alert">
                                На балансе не хватает {{ missingMoney }} ₴
                            </v-alert>
                            <v-btn @click="createPayment" color="green-darken-2" class="mt-3" block large>
                                <v-icon left>mdi-wallet</v-icon>
                                Пополнить баланс
                            </v-btn>
                        </template>

                        <v-alert type="info" variant="tonal" class="mt-3">
                            Продавец не сможет получить оплату до тех пор, пока вы не подтвердите выполнение им всех
                            обязательств.
                        </v-alert>
                    </v-col>
                </v-row>
            </v-card>

            <v-card class="pa-4" elevation="2">
                <template v-if="reviews.length === 0">
                    <v-alert type="info" variant="tonal">
                        Нет отзывов.
                    </v-alert>
                </template>

                <template v-else>
                    <h3 class="text-h5 font-weight-bold mb-4">Отзывы</h3>
                    <v-list>
                        <template v-for="(review, index) in reviews" :key="index">
                            <v-list-item class="pa-3">
                                <template v-slot:prepend>
                                    <v-avatar size="56" class="mr-4">
                                        <v-img src="https://picsum.photos/200" alt="Аватар"></v-img>
                                    </v-avatar>
                                </template>

                                <v-list-item-title class="d-flex justify-space-between font-weight-bold">
                                    <div>
                                        <router-link
                                            class="text-decoration-none text-white"
                                            :to="{ name: 'user.profile', params: { id: review.user.id } }"
                                        >
                                            {{ review.user.name }}
                                        </router-link>
                                        <span class="ml-2 text-medium-emphasis text-subtitle-2">
                        {{ review.deal.offer_game }}, {{ Math.round(review.deal.price) }} ₴
                      </span>
                                    </div>
                                    <span class="text-medium-emphasis text-subtitle-2">{{ review.created_at }}</span>
                                </v-list-item-title>

                                <v-rating
                                    v-model="review.rating"
                                    color="amber"
                                    half-increments
                                    readonly
                                    size="24"
                                ></v-rating>

                                <v-list-item-subtitle>
                                    {{ review.comment }}
                                </v-list-item-subtitle>
                            </v-list-item>

                            <v-divider v-if="index < reviews.length - 1"></v-divider>
                        </template>
                    </v-list>
                </template>
            </v-card>
        </v-col>

        <v-col cols="12" md="4">
            <Chat :secondUser="offer.seller?.id"/>
        </v-col>
    </v-row>

    <v-snackbar
        v-model="showSnack"
        :timeout="2000"
        :color="snackOptions.color"
    >
        {{ snackOptions.text }}
    </v-snackbar>
</template>

<style scoped>
.online-avatar {
    border: 2px solid green;
}

.offline-avatar {
    border: 2px solid red;
}
</style>
