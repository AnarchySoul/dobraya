<?php
/**
 * Страница 404.
 *
 * @package dobraya36
 */

get_header();
?>
<section class="section">
	<div class="wrap">
		<div class="error404-box">
			<div class="code" aria-hidden="true">404</div>
			<h1><?php esc_html_e( 'Страница не найдена', 'dobraya36' ); ?></h1>
			<p><?php esc_html_e( 'Возможно, страница была перемещена или удалена. Давайте вернёмся к здоровой улыбке.', 'dobraya36' ); ?></p>
			<div class="error404-links">
				<a class="btn btn--grad" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'На главную', 'dobraya36' ); ?></a>
				<a class="btn btn--ghost" href="<?php echo esc_url( get_post_type_archive_link( 'service' ) ?: home_url( '/uslugi/' ) ); ?>"><?php esc_html_e( 'Услуги', 'dobraya36' ); ?></a>
				<a class="btn btn--ghost" href="<?php echo esc_url( home_url( '/kontakty/' ) ); ?>"><?php esc_html_e( 'Контакты', 'dobraya36' ); ?></a>
			</div>
		</div>
	</div>
</section>
<?php
get_footer();
