<script>
export default {
    data() {
        return {
            quantity: 1, // Количество товара
            pricePerUnit: 926.88, // Цена за единицу
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
            ]
        };
    },
    computed: {
        totalPrice() {
            return (this.quantity * this.pricePerUnit).toFixed(2);
        },
    },
};
</script>

<template>
    <v-container>
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
                        ТОП АККАУНТ НА СТАРТ 🔶 [Авто] T5 Чернокнижник
                    </h1>

                    <!-- Описание -->
                    <p class="text-body-2 mb-4">
                        Т6 (100 мастерство) переработка дерева, ткани, железа или кожи + 30 дней премиум...

                        персонаж с 100 мастерством Т6 переработки делается под ваш выбор
                        персонаж с отдельным аккаунтом делается под заказ, так же можем раскачать T4, Т5, Т6, Т7 и Т8
                        переработку любого ресурса или даже всех сразу
                        так же по вашему заказу можем сделать несколько персонажей на аккаунте под один или разные
                        разогнанные крафты

                        внимание! этот персонаж делается под заказ и будет готов в течении дня
                    </p>

                    <v-divider class="my-4"></v-divider>

                    <!-- Атрибуты -->
                    <v-row>
                        <!-- Колонка 1 -->
                        <v-col cols="12" sm="6">
                            <div class="d-flex align-center py-2">
                                <span class="text-subtitle-2 text-disabled">Феймы:</span>
                                <span class="ml-2">1,000,000</span>
                            </div>
                            <v-divider v-if="!$vuetify.display.smAndUp"/>
                        </v-col>

                        <!-- Колонка 2 -->
                        <v-col cols="12" sm="6">
                            <div class="d-flex  align-center py-2">
                                <span class="text-subtitle-2 text-disabled">Сервер:</span>
                                <span class="ml-2">Европа (Амстердам)</span>
                            </div>
                            <v-divider v-if="!$vuetify.display.smAndUp"/>
                        </v-col>

                        <!-- Колонка 3 -->
                        <v-col cols="12" sm="6">
                            <div class="d-flex align-center py-2">
                                <span class="text-subtitle-2 text-disabled">Класс:</span>
                                <span class="ml-2">Чернокнижник</span>
                            </div>
                            <v-divider v-if="!$vuetify.display.smAndUp"/>
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
                    <h3 class="text-h5 font-weight-bold mt-4">926.88 ₽</h3>

                    <!-- Поле ввода количества -->
                    <v-text-field
                        label="Количество"
                        type="number"
                        min="1"
                        v-model="quantity"
                        class="mt-3"
                    ></v-text-field>

                    <!-- Итоговая стоимость -->
                    <p class="text-body-2 text-grey-darken-1">
                        Получите <strong>{{ quantity }}</strong>, заплатите <strong>{{ totalPrice }}</strong> ₽
                    </p>

                    <!-- Кнопка "Купить" -->
                    <v-btn color="primary" class="mt-3" block large>
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
    </v-container>
</template>


<style scoped>
.offer-details {
    max-width: 1200px;
    margin: 0 auto;
}
</style>
