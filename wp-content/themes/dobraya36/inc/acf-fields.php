<?php
/**
 * Определения ACF-групп полей темы «Добрая стоматология».
 *
 * Источник истины — этот файл. При первом запуске (если acf-json ещё нет)
 * группы регистрируются программно и сразу выгружаются в /acf-json.
 * На последующих запросах ACF подхватывает готовые JSON-файлы автоматически
 * (см. фильтр acf/settings/load_json в functions.php), а этот бутстрап
 * самоотключается — так acf-json становится единственным источником.
 *
 * @package dobraya36
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Возвращает определения двух групп полей.
 *
 * @return array [ $home_group, $settings_group ]
 */
function dobraya36_acf_group_defs() {

	// -------- Хелперы --------
	$text = function ( $name, $label, $args = array() ) {
		return array_merge( array( 'key' => 'field_' . $name, 'label' => $label, 'name' => $name, 'type' => 'text' ), $args );
	};
	$textarea = function ( $name, $label, $rows = 3 ) {
		return array( 'key' => 'field_' . $name, 'label' => $label, 'name' => $name, 'type' => 'textarea', 'rows' => $rows, 'new_lines' => '' );
	};
	$number = function ( $name, $label, $default = '' ) {
		return array( 'key' => 'field_' . $name, 'label' => $label, 'name' => $name, 'type' => 'number', 'default_value' => $default );
	};
	$url = function ( $name, $label ) {
		return array( 'key' => 'field_' . $name, 'label' => $label, 'name' => $name, 'type' => 'url' );
	};
	$wysiwyg = function ( $name, $label ) {
		return array( 'key' => 'field_' . $name, 'label' => $label, 'name' => $name, 'type' => 'wysiwyg', 'tabs' => 'all', 'toolbar' => 'basic', 'media_upload' => 0 );
	};
	$icons = array(
		'tooth' => 'Зуб', 'crown' => 'Коронка', 'implant' => 'Имплант', 'braces' => 'Брекеты',
		'heart' => 'Сердце', 'scalpel' => 'Скальпель', 'sparkle' => 'Блеск', 'baby' => 'Ребёнок',
		'spark' => 'Искра', 'shield' => 'Щит', 'gift' => 'Подарок', 'card' => 'Карта',
		'percent' => 'Процент', 'users' => 'Команда', 'star' => 'Звезда', 'check' => 'Галочка',
		'pin' => 'Метка', 'phone' => 'Телефон', 'clock' => 'Часы',
	);
	$tab = function ( $name, $label ) {
		return array( 'key' => 'field_tab_' . $name, 'label' => $label, 'name' => '', 'type' => 'tab', 'placement' => 'top' );
	};
	$repeater = function ( $name, $label, $sub, $args = array() ) {
		return array_merge( array(
			'key' => 'field_' . $name, 'label' => $label, 'name' => $name, 'type' => 'repeater',
			'layout' => 'block', 'button_label' => 'Добавить', 'sub_fields' => $sub,
		), $args );
	};
	// Подполя используют префикс field_row_<repeater>_<name>, чтобы ключи
	// никогда не пересекались с ключами полей верхнего уровня.
	$sf = function ( $rep, $name, $label, $type = 'text', $extra = array() ) {
		return array_merge( array( 'key' => 'field_row_' . $rep . '_' . $name, 'label' => $label, 'name' => $name, 'type' => $type ), $extra );
	};
	$sf_icon = function ( $rep, $default = 'tooth' ) use ( $icons ) {
		return array( 'key' => 'field_row_' . $rep . '_icon', 'label' => 'Иконка', 'name' => 'icon', 'type' => 'select', 'choices' => $icons, 'default_value' => $default, 'ui' => 1 );
	};

	// ================= ГЛАВНАЯ =================
	$f = array();

	$f[] = $tab( 'hero', 'Первый экран' );
	$f[] = $text( 'hero_eyebrow', 'Надзаголовок' );
	$f[] = $text( 'hero_title', 'Заголовок' );
	$f[] = $text( 'hero_title_accent', 'Заголовок (выделение)' );
	$f[] = $textarea( 'hero_sub', 'Подзаголовок', 3 );
	$f[] = $text( 'hero_cta_primary', 'Кнопка (основная)' );
	$f[] = $text( 'hero_cta_secondary', 'Кнопка (вторая)' );
	$f[] = $text( 'hero_note', 'Примечание под кнопками' );
	$f[] = $repeater( 'hero_stats', 'Показатели', array(
		$sf( 'hero_stats', 'value', 'Значение' ),
		$sf( 'hero_stats', 'label', 'Подпись' ),
	) );
	$f[] = $text( 'offer_ribbon', 'Оффер: лента (выгода)' );
	$f[] = $text( 'offer_label', 'Оффер: метка' );
	$f[] = $text( 'offer_title', 'Оффер: заголовок' );
	$f[] = $text( 'offer_price_new', 'Оффер: цена' );
	$f[] = $text( 'offer_price_old', 'Оффер: старая цена' );
	$f[] = $text( 'offer_note', 'Оффер: примечание (HTML)' );
	$f[] = $text( 'offer_cta', 'Оффер: кнопка' );
	$f[] = $text( 'hero_badge_title', 'Бейдж: заголовок' );
	$f[] = $text( 'hero_badge_text', 'Бейдж: текст' );

	$f[] = $tab( 'adv', 'Преимущества' );
	$f[] = $repeater( 'advantages', 'Преимущества', array(
		$sf_icon( 'advantages', 'check' ),
		$sf( 'advantages', 'title', 'Заголовок' ),
		$sf( 'advantages', 'text', 'Текст', 'textarea', array( 'rows' => 2, 'new_lines' => '' ) ),
	) );

	$f[] = $tab( 'services', 'Услуги' );
	$f[] = $text( 'services_eyebrow', 'Надзаголовок' );
	$f[] = $text( 'services_title', 'Заголовок' );
	$f[] = $textarea( 'services_sub', 'Подзаголовок', 2 );
	$f[] = $repeater( 'services', 'Услуги', array(
		$sf_icon( 'services', 'tooth' ),
		$sf( 'services', 'title', 'Название' ),
		$sf( 'services', 'text', 'Описание', 'textarea', array( 'rows' => 2, 'new_lines' => '' ) ),
		$sf( 'services', 'link', 'Ссылка', 'url' ),
	) );

	$f[] = $tab( 'tech', 'Технологии' );
	$f[] = $text( 'tech_eyebrow', 'Надзаголовок' );
	$f[] = $text( 'tech_title', 'Заголовок' );
	$f[] = $textarea( 'tech_sub', 'Подзаголовок', 2 );
	$f[] = $repeater( 'tech', 'Блоки', array(
		$sf_icon( 'tech', 'spark' ),
		$sf( 'tech', 'title', 'Заголовок' ),
		$sf( 'tech', 'text', 'Текст', 'textarea', array( 'rows' => 2, 'new_lines' => '' ) ),
	) );

	$f[] = $tab( 'promos', 'Акции' );
	$f[] = $text( 'promos_eyebrow', 'Надзаголовок' );
	$f[] = $text( 'promos_title', 'Заголовок' );
	$f[] = $textarea( 'promos_sub', 'Подзаголовок', 2 );
	$f[] = $repeater( 'promos', 'Акции', array(
		array( 'key' => 'field_row_promos_style', 'label' => 'Стиль', 'name' => 'style', 'type' => 'select', 'choices' => array( 'default' => 'Обычный', 'accent' => 'Акцентный (тёмный)', 'warm' => 'Тёплый' ), 'default_value' => 'default', 'ui' => 1 ),
		$sf( 'promos', 'badge', 'Метка' ),
		$sf( 'promos', 'title', 'Заголовок' ),
		$sf( 'promos', 'text', 'Текст', 'textarea', array( 'rows' => 2, 'new_lines' => '' ) ),
		$sf( 'promos', 'price_new', 'Цена' ),
		$sf( 'promos', 'price_old', 'Старая цена' ),
		$sf( 'promos', 'cta', 'Кнопка' ),
	) );

	$f[] = $tab( 'about', 'О клинике' );
	$f[] = $text( 'about_eyebrow', 'Надзаголовок' );
	$f[] = $text( 'about_title', 'Заголовок' );
	$f[] = $wysiwyg( 'about_text', 'Текст' );
	$f[] = $text( 'director_name', 'Имя врача/директора' );
	$f[] = $text( 'director_role', 'Должность' );
	$f[] = $repeater( 'about_stats', 'Показатели', array(
		$sf( 'about_stats', 'value', 'Значение' ),
		$sf( 'about_stats', 'label', 'Подпись' ),
	) );
	$f[] = $repeater( 'about_hours', 'Часы работы', array(
		$sf( 'about_hours', 'days', 'Дни' ),
		$sf( 'about_hours', 'time', 'Время' ),
	) );

	$f[] = $tab( 'articles', 'Статьи' );
	$f[] = $text( 'articles_eyebrow', 'Надзаголовок' );
	$f[] = $text( 'articles_title', 'Заголовок' );
	$f[] = $textarea( 'articles_sub', 'Подзаголовок', 2 );
	$f[] = $number( 'articles_count', 'Сколько статей показывать', 3 );

	$f[] = $tab( 'reviews', 'Отзывы' );
	$f[] = $text( 'reviews_eyebrow', 'Надзаголовок' );
	$f[] = $text( 'reviews_title', 'Заголовок' );
	$f[] = $repeater( 'reviews', 'Отзывы', array(
		$sf( 'reviews', 'name', 'Имя' ),
		$sf( 'reviews', 'meta', 'Услуга/подпись' ),
		$sf( 'reviews', 'rating', 'Оценка (1-5)', 'number', array( 'default_value' => 5, 'min' => 1, 'max' => 5 ) ),
		$sf( 'reviews', 'text', 'Текст отзыва', 'textarea', array( 'rows' => 3, 'new_lines' => '' ) ),
	) );

	$f[] = $tab( 'contacts', 'Контакты' );
	$f[] = $text( 'contacts_eyebrow', 'Надзаголовок' );
	$f[] = $text( 'contacts_title', 'Заголовок' );
	$f[] = $textarea( 'contacts_sub', 'Подзаголовок', 2 );
	$f[] = $repeater( 'contacts', 'Клиники', array(
		$sf( 'contacts', 'title', 'Название клиники' ),
		$sf( 'contacts', 'address', 'Адрес' ),
		$sf( 'contacts', 'hours', 'Часы работы' ),
		$sf( 'contacts', 'phone', 'Телефон' ),
	) );

	$f[] = $tab( 'zapis', 'Запись' );
	$f[] = $text( 'zapis_title', 'Заголовок' );
	$f[] = $textarea( 'zapis_text', 'Текст', 2 );
	$f[] = $repeater( 'zapis_benefits', 'Преимущества (список)', array(
		$sf( 'zapis_benefits', 'text', 'Текст' ),
	) );
	$f[] = $text( 'zapis_form_title', 'Заголовок формы' );
	$f[] = $text( 'zapis_form_shortcode', 'Шорткод Contact Form 7' );

	$home_group = array(
		'key'      => 'group_dobraya36_home',
		'title'    => 'Главная страница',
		'fields'   => $f,
		'location' => array(
			array( array( 'param' => 'page_type', 'operator' => '==', 'value' => 'front_page' ) ),
		),
		'menu_order'      => 0,
		'position'        => 'normal',
		'style'           => 'default',
		'label_placement' => 'top',
		'active'          => true,
		'description'     => 'Контент главной страницы «Добрая стоматология».',
		'hide_on_screen'  => array( 'the_content' ),
	);

	// ================= НАСТРОЙКИ САЙТА =================
	$sf_addr = array( 'key' => 'field_row_branches_address', 'label' => 'Адрес', 'name' => 'address', 'type' => 'text' );
	$settings_fields = array(
		$text( 'phone', 'Телефон' ),
		$text( 'email', 'E-mail' ),
		$url( 'vk_url', 'Ссылка ВКонтакте' ),
		$text( 'hours_short', 'Часы работы (кратко)' ),
		$text( 'topbar_promo_1', 'Верхняя плашка: акция 1' ),
		$text( 'topbar_promo_2', 'Верхняя плашка: акция 2' ),
		$repeater( 'branches', 'Клиники (для подвала)', array( $sf_addr ) ),
		$text( 'copyright', 'Копирайт' ),
		$textarea( 'disclaimer', 'Дисклеймер', 2 ),
	);

	$settings_group = array(
		'key'      => 'group_dobraya36_settings',
		'title'    => 'Настройки сайта',
		'fields'   => $settings_fields,
		'location' => array(
			array( array( 'param' => 'options_page', 'operator' => '==', 'value' => 'nastrojki-sajta' ) ),
		),
		'menu_order'      => 0,
		'style'           => 'default',
		'label_placement' => 'top',
		'active'          => true,
	);

	return array( $home_group, $settings_group );
}

/**
 * Бутстрап: при отсутствии acf-json регистрируем группы и выгружаем их в файлы.
 * На последующих запросах ACF грузит уже готовый acf-json автоматически.
 */
function dobraya36_acf_bootstrap() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	$json_dir  = DOBRAYA36_DIR . '/acf-json';
	$home_json = $json_dir . '/group_dobraya36_home.json';

	// Если JSON уже создан — ничего не делаем: источник истины теперь acf-json.
	if ( file_exists( $home_json ) ) {
		return;
	}

	list( $home_group, $settings_group ) = dobraya36_acf_group_defs();
	acf_add_local_field_group( $home_group );
	acf_add_local_field_group( $settings_group );

	// Пытаемся выгрузить в acf-json (папка должна быть доступна для записи).
	if ( function_exists( 'acf_write_json_field_group' ) && wp_is_writable( $json_dir ) ) {
		foreach ( array( 'group_dobraya36_home', 'group_dobraya36_settings' ) as $gk ) {
			$group = acf_get_local_field_group( $gk );
			if ( $group ) {
				$group['fields'] = acf_get_fields( $group );
				acf_write_json_field_group( $group );
			}
		}
	}
}
add_action( 'acf/init', 'dobraya36_acf_bootstrap' );
