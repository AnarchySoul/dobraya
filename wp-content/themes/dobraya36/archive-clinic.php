<?php
/**
 * Список клиник (архив CPT clinic).
 *
 * @package dobraya36
 */

get_header();
$arw = '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>';
?>

<header class="page-hero">
	<div class="wrap page-hero__inner">
		<span class="eyebrow page-hero__eyebrow"><?php esc_html_e( 'Адреса', 'dobraya36' ); ?></span>
		<h1 class="page-hero__title"><?php esc_html_e( 'Наши', 'dobraya36' ); ?> <span class="hl"><?php esc_html_e( 'клиники', 'dobraya36' ); ?></span></h1>
		<p class="page-hero__lead"><?php esc_html_e( 'Три клиники «Добрая стоматология» в удобных районах Воронежа. Выберите ближайшую — мы ждём вас в удобное время.', 'dobraya36' ); ?></p>
	</div>
</header>

<?php dobraya36_breadcrumbs(); ?>

<section class="section">
	<div class="wrap">
		<?php if ( have_posts() ) : ?>
			<div class="clinic-grid" data-stagger>
				<?php
				while ( have_posts() ) :
					the_post();
					$addr = get_field( 'clinic_address' );
					$dist = get_field( 'clinic_district' );
					$ph   = get_field( 'clinic_phone' ) ?: dobraya36_opt( 'phone', '+7 (473) 211-30-11' );
					$hw   = get_field( 'clinic_hours_weekday' );
					$he   = get_field( 'clinic_hours_weekend' );
					?>
					<article class="clinic-card">
						<div class="clinic-card__body">
							<span class="clinic-card__badge"><?php echo dobraya36_icon( 'pin' ); ?><?php echo esc_html( $dist ?: 'Воронеж' ); ?></span>
							<h2 class="clinic-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
							<div class="clinic-card__row"><?php echo dobraya36_icon( 'pin' ); ?><span><?php echo esc_html( $addr ); ?></span></div>
							<div class="clinic-card__row"><?php echo dobraya36_icon( 'clock' ); ?><span><?php echo esc_html( trim( $hw . ' · ' . $he, ' ·' ) ); ?></span></div>
							<div class="clinic-card__row"><?php echo dobraya36_icon( 'phone' ); ?><a href="tel:<?php echo esc_attr( dobraya36_tel( $ph ) ); ?>"><?php echo esc_html( $ph ); ?></a></div>
						</div>
						<div class="clinic-card__foot">
							<a class="btn btn--grad btn--sm" href="#" data-booking data-clinic="<?php the_title_attribute(); ?>"><?php esc_html_e( 'Записаться', 'dobraya36' ); ?></a>
							<a class="cat-card__more" href="<?php the_permalink(); ?>"><?php esc_html_e( 'О клинике', 'dobraya36' ); ?><?php echo $arw; ?></a>
						</div>
					</article>
				<?php endwhile; ?>
			</div>
		<?php endif; ?>
	</div>
</section>

<?php get_footer();
