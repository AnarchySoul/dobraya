<?php
/**
 * Template Name: Цены и акции
 *
 * @package dobraya36
 */

get_header();
$intro  = function_exists( 'get_field' ) ? get_field( 'prices_intro' ) : '';
$groups = function_exists( 'get_field' ) ? get_field( 'price_groups' ) : array();

while ( have_posts() ) : the_post(); ?>

<header class="page-hero">
	<div class="wrap page-hero__inner">
		<span class="eyebrow page-hero__eyebrow"><?php esc_html_e( 'Прозрачные цены', 'dobraya36' ); ?></span>
		<h1 class="page-hero__title"><?php the_title(); ?></h1>
		<p class="page-hero__lead"><?php echo $intro ? esc_html( $intro ) : esc_html__( 'Честные и понятные цены без скрытых доплат. Итоговую стоимость фиксируем в плане лечения после бесплатной консультации.', 'dobraya36' ); ?></p>
	</div>
</header>

<?php dobraya36_breadcrumbs(); ?>

<section class="section">
	<div class="wrap">
		<?php if ( ! empty( $groups ) ) : ?>
			<?php foreach ( $groups as $g ) : ?>
				<table class="price-table">
					<caption><?php echo esc_html( $g['title'] ); ?></caption>
					<thead>
						<tr><th><?php esc_html_e( 'Услуга', 'dobraya36' ); ?></th><th><?php esc_html_e( 'Цена', 'dobraya36' ); ?></th></tr>
					</thead>
					<tbody>
						<?php foreach ( (array) ( $g['items'] ?? array() ) as $it ) : ?>
							<tr>
								<td>
									<?php echo esc_html( $it['name'] ); ?>
									<?php if ( ! empty( $it['note'] ) ) : ?><div class="price-note"><?php echo esc_html( $it['note'] ); ?></div><?php endif; ?>
								</td>
								<td class="price"><?php echo esc_html( $it['price'] ); ?> ₽</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			<?php endforeach; ?>
		<?php else : ?>
			<div class="prose"><?php the_content(); ?></div>
		<?php endif; ?>

		<p class="price-note" style="margin-top:1rem"><?php esc_html_e( 'Цены указаны в рублях и носят справочный характер. Точную стоимость врач называет на консультации.', 'dobraya36' ); ?></p>

		<div class="cta-band" data-anim style="margin-top:2.5rem">
			<div>
				<h2><?php esc_html_e( 'Рассрочка 0% на лечение', 'dobraya36' ); ?></h2>
				<p><?php esc_html_e( 'Оплачивайте лечение частями без процентов, банка и справок о доходах.', 'dobraya36' ); ?></p>
			</div>
			<a class="btn btn--white btn--lg" href="#" data-booking><?php esc_html_e( 'Записаться на приём', 'dobraya36' ); ?></a>
		</div>
	</div>
</section>

<?php endwhile; get_footer();
