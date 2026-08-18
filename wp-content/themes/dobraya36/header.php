<?php
/**
 * Шапка сайта (v3 — светлая клиника).
 *
 * @package dobraya36
 */

$phone = dobraya36_opt( 'phone', '+7 (473) 211-30-11' );
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<script>document.documentElement.classList.add('js');</script>
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link screen-reader-text" href="#main"><?php esc_html_e( 'Перейти к содержимому', 'dobraya36' ); ?></a>

<header class="site-header" data-header>
	<div class="wrap">
		<div class="header-bar">
			<a class="brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
				<?php
				$logo_id = get_theme_mod( 'custom_logo' );
				if ( $logo_id ) {
					echo wp_get_attachment_image( $logo_id, 'full', false, array( 'class' => 'brand__img', 'alt' => get_bloginfo( 'name' ), 'fetchpriority' => 'high' ) );
				} else {
					echo '<strong>' . esc_html( get_bloginfo( 'name' ) ) . '</strong>';
				}
				?>
			</a>

			<nav class="main-nav" aria-label="<?php esc_attr_e( 'Главное меню', 'dobraya36' ); ?>">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'primary',
						'container'      => false,
						'menu_class'     => 'menu nav-menu',
						'depth'          => 1,
						'fallback_cb'    => 'dobraya36_primary_menu_fallback',
					)
				);
				?>
			</nav>

			<div class="header-aside">
				<a class="header-phone" href="tel:<?php echo esc_attr( dobraya36_tel( $phone ) ); ?>">
					<?php echo dobraya36_icon( 'phone' ); ?><span><?php echo esc_html( $phone ); ?></span>
				</a>
				<a class="btn btn--grad btn--sm" href="#zapis"><?php esc_html_e( 'Записаться', 'dobraya36' ); ?></a>
				<button class="nav-toggle" type="button" aria-label="<?php esc_attr_e( 'Меню', 'dobraya36' ); ?>" aria-expanded="false" data-nav-toggle>
					<span></span><span></span><span></span>
				</button>
			</div>
		</div>
	</div>
</header>

<div id="content" class="site-content">
	<main id="main" class="site-main" role="main">
