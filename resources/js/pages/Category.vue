<script>
export default {
    name: "Category",
}
</script>

<template>
    <v-row>
        <v-col cols="12" md="8">
            <h1>Category name + Game name</h1>
            <p>Хотите купить аккаунт Albion Online по разумной цене? Биржа FunPay - это уникальная площадка,
                позволяющая геймерам торговать напрямую и заключать безопасные сделки. Стоимость аккаунтов Albion
                Online регулируют сами продавцы. Нашим пользователям разрешено продавать аккаунты, полученные только
                легальным путем.</p>

            <v-btn rounded size="large" class="mr-2 my-2">Серебро <span class="text-medium-emphasis text-subtitle-1">320</span>
            </v-btn>
            <v-btn rounded size="large" class="mr-2 my-2" active-color="indigo-darken-4" :to="{name: 'category'}">Серебро <span class="text-medium-emphasis text-subtitle-1">320</span>
            </v-btn>

            <v-card class="offer-card px-4 mt-2 py-2" v-for="i in 15" hover>
                <v-row align="center" no-gutters>
                    <!-- Левая часть: сервер, заголовок предложения -->
                    <v-col cols="12" sm="9" class="d-flex align-center">
                        <div class="text-caption text-grey-darken-1">
                            Европа (Амстердам)
                        </div>
                        <div class="ml-3 text-wrap">
                                <span class="text-body-2 ">
                                  🔶🔶 ТОП АККАУНТ НА СТАРТ 🔶 [Авто] Т5 Чернокнижник 🔶🔶, Маги ческое оружие, 1к фейма
                                </span>
                        </div>
                    </v-col>

                    <!-- Правая часть: аватар продавца, имя, рейтинг, цена -->
                    <v-col cols="12" sm="3" class="d-flex align-center justify-end">
                        <v-avatar size="40" class="mr-2">
                            <v-img src="https://picsum.photos/200" alt="Seller Avatar"></v-img>
                        </v-avatar>
                        <div class="">
                            <div class="">darkholme777</div>
                            <div class="text-caption text-grey-darken-1">
                                ⭐ 1764
                            </div>
                        </div>
                        <div class="ml-4 font-weight-bold">
                            926.88 ₽
                        </div>
                    </v-col>
                </v-row>
            </v-card>
        </v-col>

        <v-col cols="12" md="4">
            <v-card class="mb-4">
                <v-card-title>Топ продавцы</v-card-title>
                <v-list>
                    <v-list-item>SilverMaster (500+ сделок)</v-list-item>
                    <v-list-item>FastGold (300+ сделок)</v-list-item>
                </v-list>
            </v-card>

            <v-expansion-panels>
                <v-expansion-panel>
                    <v-expansion-panel-title>Как купить?</v-expansion-panel-title>
                    <v-expansion-panel-text>
                        1. Выберите продавца.<br>
                        2. Оформите сделку.<br>
                        3. Дождитесь подтверждения.
                    </v-expansion-panel-text>
                </v-expansion-panel>
            </v-expansion-panels>
        </v-col>
    </v-row>

</template>

<style scoped>

</style>
