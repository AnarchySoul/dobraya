<?php
/**
 * Подвал сайта (v3.2 — клиники-ссылки, часы в 2 строки, модалка записи).
 *
 * @package dobraya36
 */

$phone      = dobraya36_opt( 'phone', '+7 (473) 211-30-11' );
$email      = dobraya36_opt( 'email', 'info@dobraya36.ru' );
$vk         = dobraya36_opt( 'vk_url', '#' );
$hours      = dobraya36_opt( 'hours_short', 'Пн–Пт 9:00–20:00 · Сб–Вс 10:00–16:00' );
$hours_rows = array_map( 'trim', preg_split( '/\s*·\s*/u', $hours ) );
$copyright  = dobraya36_opt( 'copyright', 'Беликова Ю.К., 2013–' . date( 'Y' ) );
$disclaimer = dobraya36_opt( 'disclaimer', 'Имеются противопоказания. Необходима консультация специалиста.' );
$logo_id    = get_theme_mod( 'custom_logo' );

$footer_clinics = get_posts( array( 'post_type' => 'clinic', 'numberposts' => -1, 'orderby' => 'menu_order', 'order' => 'ASC' ) );
?>
	</main><!-- #main -->
</div><!-- #content -->

<footer class="site-footer" role="contentinfo">
	<div class="wrap">
		<div class="footer__grid">
			<div class="footer__col">
				<?php if ( $logo_id ) : ?>
					<?php echo wp_get_attachment_image( $logo_id, 'full', false, array( 'class' => 'footer__brand-img', 'alt' => get_bloginfo( 'name' ) ) ); ?>
				<?php else : ?>
					<p class="footer__h" style="font-size:1.4rem"><?php bloginfo( 'name' ); ?></p>
				<?php endif; ?>
				<p class="footer__about"><?php echo esc_html( get_bloginfo( 'description' ) ); ?></p>
				<div class="footer__socials">
					<?php if ( $vk ) : ?><a href="<?php echo esc_url( $vk ); ?>" target="_blank" rel="noopener">ВКонтакте</a><?php endif; ?>
					<a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a>
				</div>
			</div>

			<div class="footer__col">
				<p class="footer__h"><?php esc_html_e( 'Навигация', 'dobraya36' ); ?></p>
				<?php
				wp_nav_menu(
					array(
						'theme_location' => has_nav_menu( 'footer' ) ? 'footer' : 'primary',
						'container'      => false,
						'menu_class'     => 'footer__menu',
						'depth'          => 1,
						'fallback_cb'    => 'dobraya36_primary_menu_fallback',
					)
				);
				?>
			</div>

			<div class="footer__col">
				<p class="footer__h"><?php esc_html_e( 'Клиники', 'dobraya36' ); ?></p>
				<ul class="footer__branches">
					<?php if ( ! empty( $footer_clinics ) ) : ?>
						<?php foreach ( $footer_clinics as $fc ) : ?>
							<li><a href="<?php echo esc_url( get_permalink( $fc ) ); ?>"><?php echo esc_html( get_field( 'clinic_address', $fc->ID ) ?: $fc->post_title ); ?></a></li>
						<?php endforeach; ?>
						<li><a href="<?php echo esc_url( get_post_type_archive_link( 'clinic' ) ); ?>"><strong><?php esc_html_e( 'Все клиники', 'dobraya36' ); ?></strong></a></li>
					<?php else : ?>
						<li>ул. Старых Большевиков, 2</li><li>Ленинский пр., 151</li><li>ул. Ф. Тютчева, 99а</li>
					<?php endif; ?>
				</ul>
			</div>

			<div class="footer__col">
				<p class="footer__h"><?php esc_html_e( 'Контакты', 'dobraya36' ); ?></p>
				<a class="footer__phone" href="tel:<?php echo esc_attr( dobraya36_tel( $phone ) ); ?>"><?php echo esc_html( $phone ); ?></a>
				<p class="footer__hours">
					<?php foreach ( $hours_rows as $hr ) : ?>
						<span class="footer__hours-line"><?php echo esc_html( $hr ); ?></span>
					<?php endforeach; ?>
				</p>
				<a class="btn btn--green btn--sm" href="#" data-booking><?php esc_html_e( 'Записаться на приём', 'dobraya36' ); ?></a>
			</div>
		</div>

		<div class="footer__bottom">
			<p>© <?php echo esc_html( $copyright ); ?></p>
			<p><?php echo esc_html( $disclaimer ); ?></p>
		</div>
	</div>
</footer>

<?php
// ------- Модальное окно записи -------
$modal_sc = function_exists( 'get_field' ) ? get_field( 'zapis_form_shortcode', (int) get_option( 'page_on_front' ) ) : '';
if ( ! $modal_sc ) {
	$modal_sc = '[contact-form-7 id="18" title="Запись на приём"]';
}
?>
<div class="modal" id="booking-modal" aria-hidden="true" role="dialog" aria-modal="true" aria-labelledby="booking-modal-title" data-booking-modal>
	<div class="modal__overlay" data-modal-close></div>
	<div class="modal__box">
		<button class="modal__close" type="button" aria-label="<?php esc_attr_e( 'Закрыть', 'dobraya36' ); ?>" data-modal-close>&times;</button>
		<h2 class="modal__title" id="booking-modal-title"><?php esc_html_e( 'Запись на приём', 'dobraya36' ); ?></h2>
		<p class="modal__sub" data-modal-sub><?php esc_html_e( 'Оставьте заявку — администратор перезвонит и подберёт удобное время.', 'dobraya36' ); ?></p>
		<?php echo do_shortcode( $modal_sc ); ?>
	</div>
</div>

<?php wp_footer(); ?>
</body>
</html>
