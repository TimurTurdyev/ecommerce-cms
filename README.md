# Admin CMS

Админ-панель интернет-магазина на Laravel в стиле OpenCart.

## Функционал

### Каталог
- **Товары** — управление товарами с ценами, количеством, характеристиками
- **Категории** — иерархия категорий
- **Производители** — справочник производителей
- **Опции** — опции товаров (select, radio, checkbox, text, textarea)

### Заказы
- **Заказы** — управление заказами, статусы, история

### Контент
- **Статьи** — информационные страницы

### Настройки
- **Пользователи** — управление пользователями системы
- **Настройки магазина** — через пакет timurturdyev/simple-settings

## Технологии

- Laravel 11
- Tables (адаптер для DataTables)
- Bootstrap 5
- timurturdyev/simple-settings

## Структура БД

- `categories` — категории
- `manufacturers` — производители
- `products` — товары
- `product_images` — изображения товаров
- `product_to_category` — связь товар-категория
- `product_attributes` — характеристики товаров
- `options`, `option_values` — справочник опций
- `product_options`, `product_option_values` — опции товаров
- `informations` — статьи
- `orders`, `order_products`, `order_histories` — заказы

## Установка

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
```

## Язык

По умолчанию используется русский язык (language_id = 1).
