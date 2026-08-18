<?php
/**
 * SEO-слой: JSON-LD разметка, микро-оптимизации, интеграция с Yoast.
 *
 * @package dobraya36
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * preconnect к Google Fonts + theme-color.
 */
function dobraya36_head_perf() {
	echo '<link rel="preconnect" href="https://fonts.googleapis.com">' . "\n";
	echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
	echo '<meta name="theme-color" content="#1fa7e0">' . "\n";
}
add_action( 'wp_head', 'dobraya36_head_perf', 1 );

/**
 * Общая организация (Dentist / LocalBusiness) — на всех страницах.
 */
function dobraya36_schema_organization() {
	$phone    = dobraya36_tel( dobraya36_opt( 'phone', '+7 (473) 211-30-11' ) );
	$email    = dobraya36_opt( 'email', 'info@dobraya36.ru' );
	$vk       = dobraya36_opt( 'vk_url', '' );
	$branches = dobraya36_opt( 'branches', array() );
	$logo_id  = get_theme_mod( 'custom_logo' );
	$logo     = $logo_id ? wp_get_attachment_image_url( $logo_id, 'full' ) : '';

	$main_address = ! empty( $branches[0]['address'] ) ? $branches[0]['address'] : 'ул. Старых Большевиков, д. 2';

	$hours = array(
		array(
			'@type'     => 'OpeningHoursSpecification',
			'dayOfWeek' => array( 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday' ),
			'opens'     => '09:00',
			'closes'    => '20:00',
		),
		array(
			'@type'     => 'OpeningHoursSpecification',
			'dayOfWeek' => array( 'Saturday', 'Sunday' ),
			'opens'     => '10:00',
			'closes'    => '16:00',
		),
	);

	$schema = array(
		'@context'    => 'https://schema.org',
		'@type'       => 'Dentist',
		'@id'         => home_url( '/#dentist' ),
		'name'        => get_bloginfo( 'name' ),
		'description' => get_bloginfo( 'description' ),
		'url'         => home_url( '/' ),
		'telephone'   => $phone,
		'email'       => $email,
		'priceRange'  => '₽₽',
		'currenciesAccepted' => 'RUB',
		'address'     => array(
			'@type'           => 'PostalAddress',
			'streetAddress'   => $main_address,
			'addressLocality' => 'Воронеж',
			'addressRegion'   => 'Воронежская область',
			'addressCountry'  => 'RU',
		),
		'areaServed'  => array( '@type' => 'City', 'name' => 'Воронеж' ),
		'openingHoursSpecification' => $hours,
		'medicalSpecialty' => 'Dentistry',
	);

	if ( $logo ) {
		$schema['image'] = $logo;
		$schema['logo']  = $logo;
	}
	if ( $vk ) {
		$schema['sameAs'] = array( $vk );
	}
	if ( ! empty( $branches ) ) {
		$depts = array();
		foreach ( $branches as $b ) {
			if ( empty( $b['address'] ) ) {
				continue;
			}
			$depts[] = array(
				'@type'     => 'Dentist',
				'name'      => get_bloginfo( 'name' ) . ' — ' . $b['address'],
				'telephone' => $phone,
				'address'   => array(
					'@type'           => 'PostalAddress',
					'streetAddress'   => $b['address'],
					'addressLocality' => 'Воронеж',
					'addressCountry'  => 'RU',
				),
			);
		}
		if ( $depts ) {
			$schema['department'] = $depts;
		}
	}

	dobraya36_print_jsonld( $schema );
}
add_action( 'wp_head', 'dobraya36_schema_organization', 20 );

/**
 * Schema для отдельной услуги + FAQ.
 */
function dobraya36_schema_service() {
	if ( ! is_singular( 'service' ) ) {
		return;
	}
	$id    = get_the_ID();
	$price = function_exists( 'get_field' ) ? get_field( 'service_price_from', $id ) : '';
	$desc  = function_exists( 'get_field' ) ? get_field( 'service_intro', $id ) : '';
	if ( ! $desc ) {
		$desc = wp_strip_all_tags( get_the_excerpt( $id ) );
	}

	$service = array(
		'@context'    => 'https://schema.org',
		'@type'       => 'MedicalProcedure',
		'name'        => get_the_title( $id ),
		'description' => $desc,
		'url'         => get_permalink( $id ),
		'provider'    => array( '@type' => 'Dentist', '@id' => home_url( '/#dentist' ), 'name' => get_bloginfo( 'name' ) ),
	);
	if ( has_post_thumbnail( $id ) ) {
		$service['image'] = get_the_post_thumbnail_url( $id, 'dobraya36_wide' );
	}
	if ( $price ) {
		$service['offers'] = array(
			'@type'         => 'Offer',
			'price'         => preg_replace( '/[^\d]/', '', $price ),
			'priceCurrency' => 'RUB',
			'availability'  => 'https://schema.org/InStock',
			'url'           => get_permalink( $id ),
		);
	}
	dobraya36_print_jsonld( $service );

	// FAQ
	$faq = function_exists( 'get_field' ) ? get_field( 'service_faq', $id ) : array();
	if ( ! empty( $faq ) ) {
		$items = array();
		foreach ( $faq as $f ) {
			if ( empty( $f['question'] ) ) {
				continue;
			}
			$items[] = array(
				'@type'          => 'Question',
				'name'           => $f['question'],
				'acceptedAnswer' => array( '@type' => 'Answer', 'text' => wp_strip_all_tags( $f['answer'] ) ),
			);
		}
		if ( $items ) {
			dobraya36_print_jsonld( array( '@context' => 'https://schema.org', '@type' => 'FAQPage', 'mainEntity' => $items ) );
		}
	}
}
add_action( 'wp_head', 'dobraya36_schema_service', 21 );

/**
 * Schema для врача (Person).
 */
function dobraya36_schema_doctor() {
	if ( ! is_singular( 'doctor' ) ) {
		return;
	}
	$id  = get_the_ID();
	$pos = function_exists( 'get_field' ) ? get_field( 'doc_position', $id ) : '';

	$person = array(
		'@context'  => 'https://schema.org',
		'@type'     => 'Person',
		'name'      => get_the_title( $id ),
		'jobTitle'  => $pos ? $pos : 'Врач-стоматолог',
		'url'       => get_permalink( $id ),
		'worksFor'  => array( '@type' => 'Dentist', '@id' => home_url( '/#dentist' ), 'name' => get_bloginfo( 'name' ) ),
	);
	if ( has_post_thumbnail( $id ) ) {
		$person['image'] = get_the_post_thumbnail_url( $id, 'dobraya36_card' );
	}
	$desc = wp_strip_all_tags( get_the_excerpt( $id ) );
	if ( $desc ) {
		$person['description'] = $desc;
	}
	dobraya36_print_jsonld( $person );
}
add_action( 'wp_head', 'dobraya36_schema_doctor', 21 );

/**
 * Schema для отдельной клиники (Dentist / филиал).
 */
function dobraya36_schema_clinic() {
	if ( ! is_singular( 'clinic' ) ) {
		return;
	}
	$id    = get_the_ID();
	$addr  = function_exists( 'get_field' ) ? get_field( 'clinic_address', $id ) : '';
	$phone = function_exists( 'get_field' ) ? get_field( 'clinic_phone', $id ) : '';
	$lat   = function_exists( 'get_field' ) ? get_field( 'clinic_lat', $id ) : '';
	$lng   = function_exists( 'get_field' ) ? get_field( 'clinic_lng', $id ) : '';

	$schema = array(
		'@context'    => 'https://schema.org',
		'@type'       => 'Dentist',
		'name'        => get_bloginfo( 'name' ) . ' — ' . get_the_title( $id ),
		'url'         => get_permalink( $id ),
		'telephone'   => dobraya36_tel( $phone ?: dobraya36_opt( 'phone' ) ),
		'parentOrganization' => array( '@type' => 'Dentist', '@id' => home_url( '/#dentist' ), 'name' => get_bloginfo( 'name' ) ),
		'address'     => array(
			'@type'           => 'PostalAddress',
			'streetAddress'   => $addr,
			'addressLocality' => 'Воронеж',
			'addressCountry'  => 'RU',
		),
		'openingHoursSpecification' => array(
			array( '@type' => 'OpeningHoursSpecification', 'dayOfWeek' => array( 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday' ), 'opens' => '09:00', 'closes' => '20:00' ),
			array( '@type' => 'OpeningHoursSpecification', 'dayOfWeek' => array( 'Saturday', 'Sunday' ), 'opens' => '10:00', 'closes' => '16:00' ),
		),
	);
	if ( $lat && $lng ) {
		$schema['geo'] = array( '@type' => 'GeoCoordinates', 'latitude' => $lat, 'longitude' => $lng );
	}
	dobraya36_print_jsonld( $schema );
}
add_action( 'wp_head', 'dobraya36_schema_clinic', 21 );

/**
 * Вывод JSON-LD.
 */
function dobraya36_print_jsonld( $data ) {
	echo "\n" . '<script type="application/ld+json">' . wp_json_encode( $data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) . '</script>' . "\n";
}

/**
 * Мета-описание услуги/врача для Yoast (fallback), если не задано вручную.
 */
function dobraya36_meta_description( $desc ) {
	if ( $desc ) {
		return $desc;
	}
	if ( is_singular( 'service' ) && function_exists( 'get_field' ) ) {
		$intro = get_field( 'service_intro', get_the_ID() );
		if ( $intro ) {
			return wp_strip_all_tags( $intro );
		}
	}
	return $desc;
}
add_filter( 'wpseo_metadesc', 'dobraya36_meta_description' );

/**
 * Robots: гарантированно индексируем публичные типы (страховка).
 */
function dobraya36_robots( $robots ) {
	if ( is_singular( array( 'service', 'doctor', 'clinic', 'post', 'page' ) ) || is_post_type_archive( array( 'service', 'doctor', 'clinic' ) ) ) {
		$robots['index'] = 'index';
	}
	return $robots;
}
add_filter( 'wpseo_robots_array', 'dobraya36_robots' );

/**
 * Хлебные крошки для Yoast — русские подписи по умолчанию берутся из настроек Yoast.
 * Классический футер-помощник вывода вынесен в inc/post-types.php (dobraya36_breadcrumbs()).
 */
