<?php
/**
 * Добрая стоматология — functions.php
 *
 * @package dobraya36
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'DOBRAYA36_VERSION', '3.2.0' );
define( 'DOBRAYA36_DIR', get_template_directory() );
define( 'DOBRAYA36_URI', get_template_directory_uri() );

/**
 * Базовая настройка темы.
 */
function dobraya36_setup() {
	load_theme_textdomain( 'dobraya36', DOBRAYA36_DIR . '/languages' );

	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 77,
			'width'       => 240,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);
	add_theme_support( 'customize-selective-refresh-widgets' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support(
		'html5',
		array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' )
	);

	add_image_size( 'dobraya36_card', 720, 480, true );
	add_image_size( 'dobraya36_wide', 1280, 720, true );

	register_nav_menus(
		array(
			'primary' => __( 'Главное меню', 'dobraya36' ),
			'footer'  => __( 'Меню в подвале', 'dobraya36' ),
		)
	);
}
add_action( 'after_setup_theme', 'dobraya36_setup' );

/**
 * Ширина контента.
 */
function dobraya36_content_width() {
	$GLOBALS['content_width'] = 1200;
}
add_action( 'after_setup_theme', 'dobraya36_content_width', 0 );

/**
 * Подключение стилей и скриптов.
 */
function dobraya36_assets() {
	// Google Fonts — Nunito (заголовки, круглый гротеск) + Nunito Sans (текст), кириллица.
	wp_enqueue_style(
		'dobraya36-fonts',
		'https://fonts.googleapis.com/css2?family=Nunito:wght@600;700;800;900&family=Nunito+Sans:wght@400;600;700&display=swap',
		array(),
		null
	);

	wp_enqueue_style( 'dobraya36-style', get_stylesheet_uri(), array(), DOBRAYA36_VERSION );
	wp_enqueue_style( 'dobraya36-main', DOBRAYA36_URI . '/assets/css/main.css', array( 'dobraya36-style' ), DOBRAYA36_VERSION );
	wp_enqueue_style( 'dobraya36-pages', DOBRAYA36_URI . '/assets/css/pages.css', array( 'dobraya36-main' ), DOBRAYA36_VERSION );

	wp_enqueue_script( 'dobraya36-main', DOBRAYA36_URI . '/assets/js/main.js', array(), DOBRAYA36_VERSION, true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'dobraya36_assets' );

/**
 * Место для acf-json (чтение и запись).
 */
function dobraya36_acf_json_save( $path ) {
	return DOBRAYA36_DIR . '/acf-json';
}
add_filter( 'acf/settings/save_json', 'dobraya36_acf_json_save' );

function dobraya36_acf_json_load( $paths ) {
	$paths[] = DOBRAYA36_DIR . '/acf-json';
	return $paths;
}
add_filter( 'acf/settings/load_json', 'dobraya36_acf_json_load' );

/**
 * Определения ACF-полей (регистрация + первичная выгрузка в acf-json).
 */
require_once DOBRAYA36_DIR . '/inc/acf-fields.php';

/**
 * Типы записей, таксономии, ACF для услуг/врачей, SEO-разметка.
 */
require_once DOBRAYA36_DIR . '/inc/post-types.php';
require_once DOBRAYA36_DIR . '/inc/acf-content.php';
require_once DOBRAYA36_DIR . '/inc/seo.php';

/**
 * Страница настроек темы (ACF Options).
 */
if ( function_exists( 'acf_add_options_page' ) ) {
	acf_add_options_page(
		array(
			'page_title' => 'Настройки сайта',
			'menu_title' => 'Настройки сайта',
			'menu_slug'  => 'nastrojki-sajta',
			'capability' => 'manage_options',
			'position'   => 2,
			'icon_url'   => 'dashicons-admin-generic',
			'redirect'   => false,
		)
	);
}

/**
 * Хелпер для получения опций темы с запасным значением.
 */
function dobraya36_opt( $name, $default = '' ) {
	if ( function_exists( 'get_field' ) ) {
		$value = get_field( $name, 'option' );
		if ( ! empty( $value ) ) {
			return $value;
		}
	}
	return $default;
}

/**
 * Телефон в «чистом» виде для tel:.
 */
function dobraya36_tel( $phone ) {
	return preg_replace( '/[^\d+]/', '', (string) $phone );
}

/**
 * Регистрация областей виджетов подвала.
 */
function dobraya36_widgets_init() {
	register_sidebar(
		array(
			'name'          => __( 'Подвал', 'dobraya36' ),
			'id'            => 'footer-1',
			'description'   => __( 'Виджеты в подвале сайта', 'dobraya36' ),
			'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h4 class="footer-widget__title">',
			'after_title'   => '</h4>',
		)
	);
}
add_action( 'widgets_init', 'dobraya36_widgets_init' );

/**
 * Аккуратная длина выдержки.
 */
function dobraya36_excerpt_length() {
	return 24;
}
add_filter( 'excerpt_length', 'dobraya36_excerpt_length' );

function dobraya36_excerpt_more() {
	return '…';
}
add_filter( 'excerpt_more', 'dobraya36_excerpt_more' );

/**
 * Fallback-меню, если основное меню не назначено.
 */
function dobraya36_primary_menu_fallback() {
	echo '<ul class="menu nav-menu">';
	wp_list_pages( array( 'title_li' => '', 'depth' => 1 ) );
	echo '</ul>';
}

/**
 * Подсветить последние N слов заголовка акцентным цветом (без дублирования).
 *
 * @param string $text  Текст заголовка.
 * @param int    $words Сколько последних слов подсветить.
 * @param string $class CSS-класс акцента (hl — голубой, hl-g — зелёный).
 * @return string Готовый HTML.
 */
function dobraya36_accent_last( $text, $words = 1, $class = 'hl' ) {
	$text = trim( (string) $text );
	if ( '' === $text ) {
		return '';
	}
	$parts = preg_split( '/\s+/u', $text );
	if ( count( $parts ) <= $words ) {
		return '<span class="' . esc_attr( $class ) . '">' . esc_html( $text ) . '</span>';
	}
	$tail = array_splice( $parts, -$words );
	return esc_html( implode( ' ', $parts ) ) . ' <span class="' . esc_attr( $class ) . '">' . esc_html( implode( ' ', $tail ) ) . '</span>';
}

/**
 * SVG-иконки из спрайта.
 */
function dobraya36_icon( $name, $class = 'icon' ) {
	return sprintf(
		'<svg class="%1$s" aria-hidden="true" focusable="false"><use xlink:href="%2$s#icon-%3$s"></use></svg>',
		esc_attr( $class ),
		esc_url( DOBRAYA36_URI . '/assets/icons/sprite.svg' ),
		esc_attr( $name )
	);
}
