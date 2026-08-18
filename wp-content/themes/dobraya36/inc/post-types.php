<?php
/**
 * Типы записей и таксономии темы «Добрая стоматология».
 *
 * @package dobraya36
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Регистрация CPT «Услуги» и «Врачи» + таксономии «Направления».
 */
function dobraya36_register_post_types() {

	// ------- Услуги (/uslugi/) -------
	register_post_type(
		'service',
		array(
			'labels'       => array(
				'name'               => 'Услуги',
				'singular_name'      => 'Услуга',
				'add_new'            => 'Добавить услугу',
				'add_new_item'       => 'Новая услуга',
				'edit_item'          => 'Редактировать услугу',
				'new_item'           => 'Новая услуга',
				'view_item'          => 'Смотреть услугу',
				'search_items'       => 'Искать услуги',
				'not_found'          => 'Услуги не найдены',
				'all_items'          => 'Все услуги',
				'menu_name'          => 'Услуги',
			),
			'public'       => true,
			'has_archive'  => 'uslugi',
			'menu_icon'    => 'dashicons-heart',
			'menu_position' => 20,
			'supports'     => array( 'title', 'editor', 'thumbnail', 'excerpt', 'page-attributes' ),
			'rewrite'      => array( 'slug' => 'uslugi', 'with_front' => false ),
			'show_in_rest' => true,
			'taxonomies'   => array( 'service_cat' ),
		)
	);

	// ------- Клиники (/kliniki/) -------
	register_post_type(
		'clinic',
		array(
			'labels'        => array(
				'name'          => 'Клиники',
				'singular_name' => 'Клиника',
				'add_new'       => 'Добавить клинику',
				'add_new_item'  => 'Новая клиника',
				'edit_item'     => 'Редактировать клинику',
				'all_items'     => 'Все клиники',
				'menu_name'     => 'Клиники',
			),
			'public'        => true,
			'has_archive'   => 'kliniki',
			'menu_icon'     => 'dashicons-location',
			'menu_position' => 22,
			'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt', 'page-attributes' ),
			'rewrite'       => array( 'slug' => 'kliniki', 'with_front' => false ),
			'show_in_rest'  => true,
		)
	);

	// ------- Врачи (/vrachi/) -------
	register_post_type(
		'doctor',
		array(
			'labels'       => array(
				'name'          => 'Врачи',
				'singular_name' => 'Врач',
				'add_new'       => 'Добавить врача',
				'add_new_item'  => 'Новый врач',
				'edit_item'     => 'Редактировать врача',
				'all_items'     => 'Все врачи',
				'menu_name'     => 'Врачи',
			),
			'public'        => true,
			'has_archive'   => 'vrachi',
			'menu_icon'     => 'dashicons-groups',
			'menu_position' => 21,
			'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt', 'page-attributes' ),
			'rewrite'       => array( 'slug' => 'vrachi', 'with_front' => false ),
			'show_in_rest'  => true,
		)
	);
}
add_action( 'init', 'dobraya36_register_post_types' );

/**
 * Таксономия «Направления» для услуг (/napravlenie/).
 */
function dobraya36_register_taxonomies() {
	register_taxonomy(
		'service_cat',
		array( 'service' ),
		array(
			'labels'            => array(
				'name'          => 'Направления',
				'singular_name' => 'Направление',
				'menu_name'     => 'Направления',
				'all_items'     => 'Все направления',
				'edit_item'     => 'Редактировать направление',
				'add_new_item'  => 'Добавить направление',
			),
			'hierarchical'      => true,
			'public'            => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'rewrite'           => array( 'slug' => 'napravlenie', 'with_front' => false ),
		)
	);
}
add_action( 'init', 'dobraya36_register_taxonomies', 5 );

/**
 * Флаш rewrite-правил при смене версии структуры.
 */
function dobraya36_maybe_flush_rewrites() {
	$ver = '4';
	if ( get_option( 'dobraya36_rewrite_ver' ) !== $ver ) {
		dobraya36_register_taxonomies();
		dobraya36_register_post_types();
		flush_rewrite_rules( false );
		update_option( 'dobraya36_rewrite_ver', $ver );
	}
}
add_action( 'init', 'dobraya36_maybe_flush_rewrites', 99 );

/**
 * Кол-во услуг/врачей на странице архива.
 */
function dobraya36_archive_ppp( $query ) {
	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}
	if ( $query->is_post_type_archive( 'service' ) || $query->is_tax( 'service_cat' ) ) {
		$query->set( 'posts_per_page', -1 );
		$query->set( 'orderby', 'menu_order title' );
		$query->set( 'order', 'ASC' );
	}
	if ( $query->is_post_type_archive( array( 'doctor', 'clinic' ) ) ) {
		$query->set( 'posts_per_page', -1 );
		$query->set( 'orderby', 'menu_order' );
		$query->set( 'order', 'ASC' );
	}
}

/**
 * Врачи, прикреплённые к клинике (обратный запрос по ACF-полю doc_clinics).
 *
 * @param int $clinic_id ID клиники.
 * @return WP_Query
 */
function dobraya36_clinic_doctors( $clinic_id ) {
	return new WP_Query( array(
		'post_type'      => 'doctor',
		'posts_per_page' => -1,
		'orderby'        => 'menu_order',
		'order'          => 'ASC',
		'meta_query'     => array(
			array(
				'key'     => 'doc_clinics',
				'value'   => '"' . $clinic_id . '"',
				'compare' => 'LIKE',
			),
		),
	) );
}
add_action( 'pre_get_posts', 'dobraya36_archive_ppp' );

/**
 * Хелпер: хлебные крошки (Yoast, если доступен).
 */
function dobraya36_breadcrumbs() {
	if ( function_exists( 'yoast_breadcrumb' ) && ! is_front_page() ) {
		echo '<nav class="breadcrumbs" aria-label="' . esc_attr__( 'Хлебные крошки', 'dobraya36' ) . '">';
		yoast_breadcrumb( '<div class="wrap">', '</div>' );
		echo '</nav>';
	}
}
