<script>
export default {
    data() {
        return {
            offer: {
                price: 0
            },
            quantity: 0,
            reviews: [
                {
                    user: "IvanP",
                    rating: 5,
                    comment: "Отличный аккаунт, продавец быстро ответил!",
                    avatar: "https://i.pravatar.cc/40?img=1"
                },
                {
                    user: "Nikolay87",
                    rating: 4,
                    comment: "Все как в описании, но пришлось ждать ответа.",
                    avatar: "https://i.pravatar.cc/40?img=2"
                },
                {
                    user: "Alex_Gamer",
                    rating: 5,
                    comment: "Сделка прошла гладко, спасибо!",
                    avatar: "https://i.pravatar.cc/40?img=3"
                }
            ],
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
                    this.offer = res.data.data
                })
        },

        createDeal() {
            axios.post('api/deals', {
                quantity: this.quantity,
                offer_id: this.offer.id
            })
                .then(() => {
                    this.snackOptions.text = 'Сделка успешно создана.'
                    this.snackOptions.color = 'success'
                    this.showSnack = true
                })
                .catch(err => {
                    this.snackOptions.text = err.response.data.error
                    this.snackOptions.color = 'red'
                    this.showSnack = true
                })
        },

        createPayment() {
            axios.post('api/payment/create', {
                amount: this.missingMoney
            })
                .then(r => {
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
                });
        },

        getUserBalance() {
            if (localStorage.getItem('isAuth')) {
                axios.get('api/user')
                    .then(r => this.userBalance = r.data.balance)
            }
        }
    },

    mounted() {
        this.getOffer()
        this.getUserBalance()
    },

    computed: {
        totalPrice() {
            return (this.quantity * this.offer.price).toFixed(2)
        },

        missingMoney() {
            return (this.totalPrice - this.userBalance).toFixed(2)
        }
    },
};
</script>

<template>
    <v-card class="offer-details pa-4" elevation="2">
        <v-row>
            <!-- Левая часть: информация об оффере -->
            <v-col cols="12" md="8">
                <v-btn variant="text" color="primary" @click="$router.go(-1)">
                    <v-icon left>mdi-arrow-left</v-icon>
                    Назад
                </v-btn>

                <!-- Заголовок -->
                <h1 class="text-h5 font-weight-bold mb-4">
                    {{ offer.title }}
                </h1>

                <!-- Описание -->
                <p class="text-body-2 mb-4">
                    {{ offer.description }}
                </p>

                <v-divider class="my-4"></v-divider>

                <!-- Атрибуты -->
                <v-row>
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
            </v-col>

            <!-- Правая часть: продавец, цена, покупка -->
            <v-col cols="12" md="4" class="text-center">
                <!-- Аватар продавца -->
                <v-avatar size="80">
                    <v-img src="https://picsum.photos/200" alt="Seller Avatar"></v-img>
                </v-avatar>

                <!-- Имя продавца -->
                <h2 class="text-subtitle-1 font-weight-bold mt-2">
                    MatveyCrueliti
                </h2>

                <!-- Рейтинг и время на сайте -->
                <p class="text-caption text-grey-darken-1">
                    ⭐ 1764 | на сайте 8 лет
                </p>

                <!-- Цена -->
                <h3 class="text-body-1 font-weight-bold mt-4">{{ offer.price }} ₴ за 1{{ offer.category?.unit.title }}</h3>

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
                        ></v-text-field>
                    </v-col>
                </v-row>


                <!-- Кнопка "Купить" -->
                <v-btn v-if="missingMoney <= 0" @click="createDeal" color="indigo-darken-1" class="mt-3" block large :disabled="!quantity">
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

                <!-- Предупреждение -->
                <v-alert type="info" variant="tonal" class="mt-3">
                    Продавец не сможет получить оплату до тех пор, пока вы не подтвердите выполнение им всех обязательств.
                </v-alert>
            </v-col>
        </v-row>
    </v-card>

    <!-- Блок отзывов -->
    <v-card class="offer-details pa-4 mt-6" elevation="2">
        <h3 class="text-subtitle-1 font-weight-bold mb-2">Отзывы покупателей:</h3>
        <v-list lines="three">
            <v-list-item v-for="(review, index) in reviews" :key="index">
                <template v-slot:prepend>
                    <v-avatar size="40">
                        <v-img :src="review.avatar"></v-img>
                    </v-avatar>
                </template>

                <v-list-item-title class="font-weight-bold">{{ review.user }}</v-list-item-title>

                <!-- Рейтинг -->
                <v-rating
                    v-model="review.rating"
                    readonly
                    size="small"
                    color="amber"
                    density="compact"
                    class="mt-1"
                ></v-rating>

                <!-- Текст отзыва -->
                <v-list-item-subtitle class="mt-1">
                    {{ review.comment }}
                </v-list-item-subtitle>

                <v-divider v-if="index < reviews.length - 1" class="my-2"></v-divider>
            </v-list-item>
        </v-list>
    </v-card>

    <v-snackbar
        v-model="showSnack"
        :timeout="2000"
        :color="snackOptions.color"
        variant="outlined"
    >
        {{ snackOptions.text }}
    </v-snackbar>
</template>

<style scoped>
.offer-details {
    max-width: 1200px;
    margin: 0 auto;
}
</style>
