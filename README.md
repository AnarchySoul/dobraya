# dobraya — тема «Добрая стоматология»

Кастомная WordPress-тема **`dobraya36`** для стоматологической клиники «Добрая
стоматология» (Воронеж) + одноразовый setup-плагин.

Репозиторий версионирует **только кастомный код**. Ядро WordPress, `wp-config.php`,
сторонние плагины (ACF Pro, Contact Form 7, Yoast SEO и др.), загрузки
(`uploads/`) и локальные конфиги OSPanel в репозиторий **не входят** (см.
`.gitignore`).

## Что в репозитории

```
wp-content/
├── themes/dobraya36/          # кастомная тема (classic, на ACF)
│   ├── acf-json/              # источник правды для полей ACF
│   ├── assets/                # css / js / img / icons
│   ├── inc/                   # post-types, acf-fields, seo, контент
│   ├── templates/             # шаблоны страниц/CPT
│   ├── template-parts/
│   └── *.php                  # header, footer, front-page, single, ...
└── mu-plugins/
    └── dobraya36-setup.php    # разовое наполнение контентом (idempotent)
```

## Локальное окружение

Сайт работает под **OSPanel** (Windows, nginx + PHP 8.5), проект `dobro.local`.
Тема — classic (не блочная), управляется через **ACF Pro**; источник правды для
полей — каталог `acf-json/` внутри темы.

## Установка темы на другой сайт

1. Скопировать `wp-content/themes/dobraya36` в тему целевого WordPress.
2. Установить и активировать плагины: Advanced Custom Fields **Pro**,
   Contact Form 7, Yoast SEO.
3. Активировать тему — поля ACF подхватятся из `acf-json/` автоматически.
