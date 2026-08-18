<?php
/**
 * ACF-поля для услуг, врачей и служебных страниц (контакты, цены).
 * Дублируются в acf-json (авто-выгрузка при отсутствии файла).
 *
 * @package dobraya36
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Определения групп полей.
 *
 * @return array список групп для acf_add_local_field_group().
 */
function dobraya36_content_field_groups() {

	$text     = function ( $name, $label, $extra = array() ) {
		return array_merge( array( 'key' => 'field_' . $name, 'label' => $label, 'name' => $name, 'type' => 'text' ), $extra );
	};
	$textarea = function ( $name, $label, $rows = 3 ) {
		return array( 'key' => 'field_' . $name, 'label' => $label, 'name' => $name, 'type' => 'textarea', 'rows' => $rows, 'new_lines' => '' );
	};
	$number   = function ( $name, $label ) {
		return array( 'key' => 'field_' . $name, 'label' => $label, 'name' => $name, 'type' => 'number' );
	};
	$url      = function ( $name, $label ) {
		return array( 'key' => 'field_' . $name, 'label' => $label, 'name' => $name, 'type' => 'url' );
	};
	$sf       = function ( $rep, $name, $label, $type = 'text', $extra = array() ) {
		return array_merge( array( 'key' => 'field_row_' . $rep . '_' . $name, 'label' => $label, 'name' => $name, 'type' => $type ), $extra );
	};
	$repeater = function ( $name, $label, $sub, $btn = 'Добавить' ) {
		return array( 'key' => 'field_' . $name, 'label' => $label, 'name' => $name, 'type' => 'repeater', 'layout' => 'block', 'button_label' => $btn, 'sub_fields' => $sub );
	};
	$icons = array(
		'tooth' => 'Зуб', 'crown' => 'Коронка', 'implant' => 'Имплант', 'braces' => 'Брекеты',
		'heart' => 'Сердце', 'scalpel' => 'Скальпель', 'sparkle' => 'Блеск', 'baby' => 'Ребёнок',
		'spark' => 'Искра', 'shield' => 'Щит', 'star' => 'Звезда', 'check' => 'Галочка',
	);

	// ============ УСЛУГА ============
	$service = array(
		'key'      => 'group_dobraya36_service',
		'title'    => 'Параметры услуги',
		'fields'   => array(
			$text( 'service_subtitle', 'Подзаголовок' ),
			array( 'key' => 'field_service_icon', 'label' => 'Иконка', 'name' => 'service_icon', 'type' => 'select', 'choices' => $icons, 'default_value' => 'tooth', 'ui' => 1 ),
			$textarea( 'service_intro', 'Краткое описание (для каталога и мета)', 2 ),
			$text( 'service_price_from', 'Цена от, ₽' ),
			$text( 'service_duration', 'Длительность / срок' ),
			$repeater( 'service_advantages', 'Преимущества', array(
				$sf( 'service_advantages', 'text', 'Текст' ),
			) ),
			$repeater( 'service_steps', 'Этапы лечения', array(
				$sf( 'service_steps', 'title', 'Название этапа' ),
				$sf( 'service_steps', 'text', 'Описание', 'textarea', array( 'rows' => 2, 'new_lines' => '' ) ),
			) ),
			$repeater( 'service_faq', 'Вопрос-ответ (FAQ)', array(
				$sf( 'service_faq', 'question', 'Вопрос' ),
				$sf( 'service_faq', 'answer', 'Ответ', 'textarea', array( 'rows' => 3, 'new_lines' => 'br' ) ),
			) ),
		),
		'location' => array( array( array( 'param' => 'post_type', 'operator' => '==', 'value' => 'service' ) ) ),
		'menu_order' => 0,
		'position'   => 'normal',
		'active'     => true,
	);

	// ============ ВРАЧ ============
	$doctor = array(
		'key'      => 'group_dobraya36_doctor',
		'title'    => 'Параметры врача',
		'fields'   => array(
			$text( 'doc_position', 'Должность' ),
			$text( 'doc_specialization', 'Специализация' ),
			$number( 'doc_experience', 'Стаж, лет' ),
			$textarea( 'doc_education', 'Образование и повышение квалификации', 4 ),
			$text( 'doc_categories', 'Направления (через запятую)' ),
			array( 'key' => 'field_doc_clinics', 'label' => 'Клиники (где принимает)', 'name' => 'doc_clinics', 'type' => 'relationship', 'post_type' => array( 'clinic' ), 'filters' => array( 'search' ), 'return_format' => 'id', 'instructions' => 'Один врач может принимать в нескольких клиниках.' ),
		),
		'location' => array( array( array( 'param' => 'post_type', 'operator' => '==', 'value' => 'doctor' ) ) ),
		'menu_order' => 0,
		'active'     => true,
	);

	// ============ КЛИНИКА ============
	$clinic = array(
		'key'      => 'group_dobraya36_clinic',
		'title'    => 'Параметры клиники',
		'fields'   => array(
			$textarea( 'clinic_intro', 'Краткое описание', 2 ),
			$text( 'clinic_address', 'Адрес' ),
			$text( 'clinic_district', 'Район / ориентир' ),
			$text( 'clinic_phone', 'Телефон' ),
			$text( 'clinic_hours_weekday', 'Часы: будни', array( 'default_value' => 'Пн–Пт 9:00–20:00' ) ),
			$text( 'clinic_hours_weekend', 'Часы: выходные', array( 'default_value' => 'Сб–Вс 10:00–16:00' ) ),
			$url( 'clinic_map_url', 'Карта (iframe src)' ),
			$text( 'clinic_lat', 'Широта' ),
			$text( 'clinic_lng', 'Долгота' ),
		),
		'location' => array( array( array( 'param' => 'post_type', 'operator' => '==', 'value' => 'clinic' ) ) ),
		'menu_order' => 0,
		'active'     => true,
	);

	// ============ СТРАНИЦА КОНТАКТОВ ============
	$contacts = array(
		'key'      => 'group_dobraya36_contacts',
		'title'    => 'Контакты (клиники)',
		'fields'   => array(
			$textarea( 'contacts_intro', 'Вводный текст', 2 ),
			$repeater( 'contact_branches', 'Клиники', array(
				$sf( 'contact_branches', 'title', 'Название' ),
				$sf( 'contact_branches', 'address', 'Адрес' ),
				$sf( 'contact_branches', 'phone', 'Телефон' ),
				$sf( 'contact_branches', 'hours', 'Часы работы' ),
				$sf( 'contact_branches', 'map_url', 'Ссылка на карту (iframe src)', 'url' ),
				$sf( 'contact_branches', 'lat', 'Широта' ),
				$sf( 'contact_branches', 'lng', 'Долгота' ),
			) ),
		),
		'location' => array( array( array( 'param' => 'page_template', 'operator' => '==', 'value' => 'templates/template-contacts.php' ) ) ),
		'active'   => true,
	);

	// ============ СТРАНИЦА ЦЕН И АКЦИЙ ============
	$prices = array(
		'key'      => 'group_dobraya36_prices',
		'title'    => 'Цены и акции',
		'fields'   => array(
			$textarea( 'prices_intro', 'Вводный текст', 2 ),
			$repeater( 'price_groups', 'Группы цен', array(
				$sf( 'price_groups', 'title', 'Название группы' ),
				array(
					'key' => 'field_row_price_items', 'label' => 'Позиции', 'name' => 'items', 'type' => 'repeater', 'layout' => 'table', 'button_label' => 'Добавить позицию',
					'sub_fields' => array(
						$sf( 'price_items', 'name', 'Наименование' ),
						$sf( 'price_items', 'price', 'Цена, ₽' ),
						$sf( 'price_items', 'note', 'Примечание' ),
					),
				),
			) ),
		),
		'location' => array( array( array( 'param' => 'page_template', 'operator' => '==', 'value' => 'templates/template-prices.php' ) ) ),
		'active'   => true,
	);

	return array( $service, $doctor, $clinic, $contacts, $prices );
}

/**
 * Регистрация групп.
 */
function dobraya36_register_content_fields() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}
	foreach ( dobraya36_content_field_groups() as $group ) {
		acf_add_local_field_group( $group );
	}
}
add_action( 'acf/init', 'dobraya36_register_content_fields' );

/**
 * Разовая выгрузка в acf-json, если файлов ещё нет.
 */
function dobraya36_content_fields_bootstrap() {
	if ( ! function_exists( 'acf_write_json_field_group' ) ) {
		return;
	}
	$json_dir = DOBRAYA36_DIR . '/acf-json';
	if ( file_exists( $json_dir . '/group_dobraya36_clinic.json' ) || ! wp_is_writable( $json_dir ) ) {
		return;
	}
	foreach ( array( 'group_dobraya36_service', 'group_dobraya36_doctor', 'group_dobraya36_clinic', 'group_dobraya36_contacts', 'group_dobraya36_prices' ) as $gk ) {
		$group = acf_get_local_field_group( $gk );
		if ( $group ) {
			$group['fields'] = acf_get_fields( $group );
			acf_write_json_field_group( $group );
		}
	}
}
add_action( 'acf/init', 'dobraya36_content_fields_bootstrap', 99 );
