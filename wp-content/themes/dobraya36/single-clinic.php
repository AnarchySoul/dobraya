<?php
/**
 * Страница клиники.
 *
 * @package dobraya36
 */

get_header();
$arw = '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>';

while ( have_posts() ) :
	the_post();
	$cid   = get_the_ID();
	$addr  = get_field( 'clinic_address' );
	$dist  = get_field( 'clinic_district' );
	$intro = get_field( 'clinic_intro' );
	$ph    = get_field( 'clinic_phone' ) ?: dobraya36_opt( 'phone', '+7 (473) 211-30-11' );
	$hw    = get_field( 'clinic_hours_weekday' );
	$he    = get_field( 'clinic_hours_weekend' );
	$map   = get_field( 'clinic_map_url' );
	$email = dobraya36_opt( 'email', 'info@dobraya36.ru' );
	?>

	<header class="page-hero">
		<div class="wrap page-hero__inner">
			<span class="eyebrow page-hero__eyebrow"><?php echo dobraya36_icon( 'pin' ); ?><?php esc_html_e( 'Клиника', 'dobraya36' ); ?></span>
			<h1 class="page-hero__title"><?php the_title(); ?></h1>
			<?php if ( $intro ) : ?><p class="page-hero__lead"><?php echo esc_html( $intro ); ?></p><?php endif; ?>
		</div>
	</header>

	<?php dobraya36_breadcrumbs(); ?>

	<section class="section">
		<div class="wrap">
			<div class="clinic-single">
				<div class="clinic-single__info">
					<div class="branch__row"><?php echo dobraya36_icon( 'pin' ); ?><span><?php echo esc_html( $addr ); ?></span></div>
					<div class="branch__row"><?php echo dobraya36_icon( 'phone' ); ?><a href="tel:<?php echo esc_attr( dobraya36_tel( $ph ) ); ?>"><?php echo esc_html( $ph ); ?></a></div>
					<div class="branch__row"><?php echo dobraya36_icon( 'clock' ); ?><span><?php echo esc_html( $hw ); ?><br><?php echo esc_html( $he ); ?></span></div>
					<div class="branch__row"><?php echo dobraya36_icon( 'mail' ); ?><a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a></div>
					<a class="btn btn--grad" style="margin-top:1.2rem" href="#" data-booking data-clinic="<?php the_title_attribute(); ?>"><?php esc_html_e( 'Записаться в эту клинику', 'dobraya36' ); ?><?php echo $arw; ?></a>
				</div>
				<div class="clinic-single__map">
					<?php if ( $map ) : ?>
						<iframe src="<?php echo esc_url( $map ); ?>" loading="lazy" title="<?php the_title_attribute(); ?>"></iframe>
					<?php else : ?>
						<div class="branch__map--ph"><?php echo dobraya36_icon( 'pin' ); ?></div>
					<?php endif; ?>
				</div>
			</div>

			<?php if ( get_the_content() ) : ?>
				<div class="prose" style="margin-top:2.5rem;max-width:760px"><?php the_content(); ?></div>
			<?php endif; ?>
		</div>
	</section>

	<?php
	// Врачи этой клиники.
	$docs = dobraya36_clinic_doctors( $cid );
	if ( $docs->have_posts() ) : ?>
		<section class="section section--tint">
			<div class="wrap">
				<div class="section-head section-head--left">
					<span class="eyebrow"><?php esc_html_e( 'Команда', 'dobraya36' ); ?></span>
					<h2 class="section-title"><?php esc_html_e( 'Врачи этой клиники', 'dobraya36' ); ?></h2>
				</div>
				<div class="team-grid" data-stagger>
					<?php while ( $docs->have_posts() ) : $docs->the_post();
						$pos = get_field( 'doc_position' );
						$exp = get_field( 'doc_experience' );
						?>
						<article class="doc-card">
							<a class="doc-card__photo" href="<?php the_permalink(); ?>">
								<?php if ( has_post_thumbnail() ) { the_post_thumbnail( 'dobraya36_card', array( 'loading' => 'lazy' ) ); } else { echo dobraya36_icon( 'heart', 'ph' ); } ?>
							</a>
							<div class="doc-card__body">
								<h3 class="doc-card__name"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
								<?php if ( $pos ) : ?><div class="doc-card__pos"><?php echo esc_html( $pos ); ?></div><?php endif; ?>
								<?php if ( $exp ) : ?><div class="doc-card__exp"><?php printf( esc_html__( 'Стаж %d лет', 'dobraya36' ), (int) $exp ); ?></div><?php endif; ?>
							</div>
						</article>
					<?php endwhile; wp_reset_postdata(); ?>
				</div>
			</div>
		</section>
	<?php endif; ?>

	<section class="section">
		<div class="wrap">
			<div class="cta-band" data-anim>
				<div>
					<h2><?php esc_html_e( 'Запишитесь на приём', 'dobraya36' ); ?></h2>
					<p><?php esc_html_e( 'Бесплатная консультация и честный план лечения в удобной для вас клинике.', 'dobraya36' ); ?></p>
				</div>
				<a class="btn btn--white btn--lg" href="#" data-booking data-clinic="<?php the_title_attribute(); ?>"><?php esc_html_e( 'Записаться', 'dobraya36' ); ?></a>
			</div>
		</div>
	</section>

<?php endwhile; get_footer();
