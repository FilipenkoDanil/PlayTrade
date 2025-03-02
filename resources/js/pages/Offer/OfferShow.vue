<script>
export default {
    data() {
        return {
            offer: {},
            quantity: 1, // Количество товара
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
            }
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
        }
    },

    mounted() {
        this.getOffer()
    },

    computed: {
        totalPrice() {
            return (this.quantity * this.offer.price).toFixed(2);
        },
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
                    <v-col cols="12" sm="6" v-for="attribute in offer.attributes">
                        <div class="d-flex align-center py-2">
                            <span class="text-subtitle-2 text-disabled">{{ attribute.title }}:</span>
                            <span class="ml-2">{{ attribute.value }}</span>
                        </div>
                        <v-divider v-if="!$vuetify.display.smAndUp"/>
                    </v-col>
                </v-row>

                <v-row v-if="offer.server">
                    <v-col>
                        <div class="d-flex align-center py-2">
                            <span class="text-subtitle-2 text-disabled">Сервер:</span>
                            <span class="ml-2">{{ offer.server.title }}</span>
                        </div>
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
                <h3 class="text-h5 font-weight-bold mt-4">{{ offer.price }} ₽</h3>

                <!-- Поле ввода количества -->
                <v-text-field
                    label="Количество"
                    type="number"
                    min="1"
                    :max="offer.amount"
                    v-model="quantity"
                    class="mt-3"
                ></v-text-field>

                <!-- Итоговая стоимость -->
                <p class="text-body-2 text-grey-darken-1">
                    Получите <strong>{{ quantity }}{{ offer.category?.unit.title }} </strong>, заплатите <strong>{{ totalPrice }}</strong> ₽
                </p>

                <!-- Кнопка "Купить" -->
                <v-btn @click="createDeal" color="primary" class="mt-3" block large>
                    Купить
                </v-btn>
                <span class="text-disabled text-body-2">Продавец не сможет получить оплату до тех пор, пока вы не подтвердите выполнение им всех обязательств.</span>
            </v-col>
        </v-row>
    </v-card>

    <!-- Блок отзывов -->
    <v-card class="offer-details pa-4 mt-6" elevation="2">
        <h3 class="text-subtitle-1 font-weight-bold mb-2">Отзывы покупателей:</h3>
        <v-list lines="three">
            <v-list-item v-for="(review, index) in reviews" :key="index">
                <v-avatar size="40">
                    <v-img :src="review.avatar"></v-img>
                </v-avatar>
                <v-list-item-title class="font-weight-bold">{{ review.user }}</v-list-item-title>
                <v-list-item-subtitle class="review-text">
                    ⭐ {{ review.rating }} – {{ review.comment }}
                </v-list-item-subtitle>
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
