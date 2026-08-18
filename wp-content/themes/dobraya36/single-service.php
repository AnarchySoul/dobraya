<?php
/**
 * Отдельная услуга.
 *
 * @package dobraya36
 */

get_header();
$arw   = '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>';
$check = '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="m4 12.5 5 5 11-11" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/></svg>';

while ( have_posts() ) :
	the_post();
	$id         = get_the_ID();
	$icon       = get_field( 'service_icon' ) ?: 'tooth';
	$sub        = get_field( 'service_subtitle' );
	$intro      = get_field( 'service_intro' );
	$price      = get_field( 'service_price_from' );
	$duration   = get_field( 'service_duration' );
	$advantages = get_field( 'service_advantages' );
	$steps      = get_field( 'service_steps' );
	$faq        = get_field( 'service_faq' );
	$svc_docs   = get_field( 'service_doctors' );
	$svc_clin   = get_field( 'service_clinics' );
	$terms      = get_the_terms( $id, 'service_cat' );
	$cat        = ( $terms && ! is_wp_error( $terms ) ) ? $terms[0] : null;
	?>

	<header class="page-hero">
		<div class="wrap page-hero__inner page-hero--split">
			<div>
				<?php if ( $cat ) : ?>
					<a class="eyebrow page-hero__eyebrow" href="<?php echo esc_url( get_term_link( $cat ) ); ?>"><?php echo esc_html( $cat->name ); ?></a>
				<?php else : ?>
					<span class="eyebrow page-hero__eyebrow"><?php esc_html_e( 'Услуга', 'dobraya36' ); ?></span>
				<?php endif; ?>
				<h1 class="page-hero__title"><?php the_title(); ?></h1>
				<?php if ( $sub || $intro ) : ?>
					<p class="page-hero__lead"><?php echo esc_html( $sub ?: $intro ); ?></p>
				<?php endif; ?>
			</div>
			<div class="page-hero__aside">
				<span class="svc-badge"><?php echo dobraya36_icon( $icon ); ?></span>
			</div>
		</div>
	</header>

	<?php dobraya36_breadcrumbs(); ?>

	<section class="section">
		<div class="wrap service-layout">
			<div class="service-main">
				<div class="prose">
					<?php
					if ( get_the_content() ) {
						the_content();
					} elseif ( $intro ) {
						echo '<p>' . esc_html( $intro ) . '</p>';
					}
					?>
				</div>

				<?php if ( ! empty( $steps ) ) : ?>
					<h2><?php esc_html_e( 'Как проходит лечение', 'dobraya36' ); ?></h2>
					<div class="steps">
						<?php foreach ( $steps as $st ) : ?>
							<div class="step">
								<span class="step__n"></span>
								<div>
									<div class="step__title"><?php echo esc_html( $st['title'] ); ?></div>
									<p class="step__text"><?php echo esc_html( $st['text'] ); ?></p>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>

				<?php if ( ! empty( $faq ) ) : ?>
					<h2><?php esc_html_e( 'Частые вопросы', 'dobraya36' ); ?></h2>
					<div class="faq">
						<?php foreach ( $faq as $f ) : if ( empty( $f['question'] ) ) { continue; } ?>
							<details class="faq__item">
								<summary class="faq__q"><?php echo esc_html( $f['question'] ); ?></summary>
								<div class="faq__a"><?php echo wp_kses_post( wpautop( $f['answer'] ) ); ?></div>
							</details>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>

			<aside class="service-aside">
				<?php if ( $price || $duration ) : ?>
					<div class="aside-card">
						<?php if ( $price ) : ?>
							<div class="aside-card__price"><small><?php esc_html_e( 'Стоимость', 'dobraya36' ); ?></small>от <?php echo esc_html( $price ); ?> ₽</div>
						<?php endif; ?>
						<?php if ( $duration ) : ?>
							<p style="margin:.8rem 0 0;color:var(--ink-60)"><?php echo esc_html( $duration ); ?></p>
						<?php endif; ?>
					</div>
				<?php endif; ?>

				<?php if ( ! empty( $advantages ) ) : ?>
					<div class="aside-card">
						<h3 style="font-size:1.1rem;margin-bottom:1rem"><?php esc_html_e( 'Почему к нам', 'dobraya36' ); ?></h3>
						<ul class="aside-list">
							<?php foreach ( $advantages as $a ) : ?>
								<li><?php echo $check; ?><span><?php echo esc_html( is_array( $a ) ? $a['text'] : $a ); ?></span></li>
							<?php endforeach; ?>
						</ul>
					</div>
				<?php endif; ?>

				<?php if ( ! empty( $svc_clin ) ) : ?>
					<div class="aside-card">
						<h3 style="font-size:1.1rem;margin-bottom:1rem"><?php esc_html_e( 'Где получить услугу', 'dobraya36' ); ?></h3>
						<ul class="aside-list">
							<?php foreach ( $svc_clin as $clid ) : $clid = is_object( $clid ) ? $clid->ID : (int) $clid; ?>
								<li><?php echo dobraya36_icon( 'pin' ); ?><span><a href="<?php echo esc_url( get_permalink( $clid ) ); ?>" style="color:inherit"><?php echo esc_html( get_field( 'clinic_address', $clid ) ?: get_the_title( $clid ) ); ?></a></span></li>
							<?php endforeach; ?>
						</ul>
					</div>
				<?php endif; ?>

				<div class="aside-card aside-card--cta">
					<h3><?php esc_html_e( 'Запишитесь на приём', 'dobraya36' ); ?></h3>
					<p><?php esc_html_e( 'Бесплатная консультация и честный план лечения.', 'dobraya36' ); ?></p>
					<a class="btn btn--white" style="margin-top:1rem" href="<?php echo esc_url( home_url( '/kontakty/' ) ); ?>"><?php esc_html_e( 'Записаться', 'dobraya36' ); ?><?php echo $arw; ?></a>
				</div>
			</aside>
		</div>
	</section>

	<?php
	// Врачи, оказывающие услугу.
	if ( ! empty( $svc_docs ) ) : ?>
		<section class="section section--tint">
			<div class="wrap">
				<div class="section-head section-head--left">
					<span class="eyebrow"><?php esc_html_e( 'Специалисты', 'dobraya36' ); ?></span>
					<h2 class="section-title"><?php esc_html_e( 'Врачи, которые оказывают услугу', 'dobraya36' ); ?></h2>
				</div>
				<div class="team-grid" data-stagger>
					<?php foreach ( $svc_docs as $doc ) :
						$doc_id = is_object( $doc ) ? $doc->ID : (int) $doc;
						$dpos   = get_field( 'doc_position', $doc_id );
						$dexp   = get_field( 'doc_experience', $doc_id );
						?>
						<article class="doc-card">
							<a class="doc-card__photo" href="<?php echo esc_url( get_permalink( $doc_id ) ); ?>">
								<?php if ( has_post_thumbnail( $doc_id ) ) { echo get_the_post_thumbnail( $doc_id, 'dobraya36_card', array( 'loading' => 'lazy' ) ); } else { echo dobraya36_icon( 'heart', 'ph' ); } ?>
							</a>
							<div class="doc-card__body">
								<h3 class="doc-card__name"><a href="<?php echo esc_url( get_permalink( $doc_id ) ); ?>"><?php echo esc_html( get_the_title( $doc_id ) ); ?></a></h3>
								<?php if ( $dpos ) : ?><div class="doc-card__pos"><?php echo esc_html( $dpos ); ?></div><?php endif; ?>
								<?php if ( $dexp ) : ?><div class="doc-card__exp"><?php printf( esc_html__( 'Стаж %d лет', 'dobraya36' ), (int) $dexp ); ?></div><?php endif; ?>
							</div>
						</article>
					<?php endforeach; ?>
				</div>
			</div>
		</section>
	<?php endif; ?>

	<?php
	// Похожие услуги.
	if ( $cat ) :
		$rel = new WP_Query( array(
			'post_type'      => 'service',
			'posts_per_page' => 3,
			'post__not_in'   => array( $id ),
			'orderby'        => 'rand',
			'tax_query'      => array( array( 'taxonomy' => 'service_cat', 'field' => 'term_id', 'terms' => $cat->term_id ) ),
		) );
		if ( $rel->have_posts() ) :
			?>
			<section class="section section--tint">
				<div class="wrap">
					<div class="section-head section-head--left"><h2 class="section-title"><?php esc_html_e( 'Похожие услуги', 'dobraya36' ); ?></h2></div>
					<div class="catalog-grid" data-stagger>
						<?php while ( $rel->have_posts() ) : $rel->the_post();
							$ricon = get_field( 'service_icon' ) ?: 'tooth';
							$rprice = get_field( 'service_price_from' );
							$rintro = get_field( 'service_intro' ) ?: wp_trim_words( get_the_excerpt(), 14, '…' );
							?>
							<article class="cat-card">
								<div class="cat-card__ico"><?php echo dobraya36_icon( $ricon ); ?></div>
								<h3 class="cat-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
								<p class="cat-card__text"><?php echo esc_html( $rintro ); ?></p>
								<div class="cat-card__foot">
									<?php if ( $rprice ) : ?><span class="cat-card__price"><span>от</span> <?php echo esc_html( $rprice ); ?> ₽</span><?php else : ?><span></span><?php endif; ?>
									<a class="cat-card__more" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Подробнее', 'dobraya36' ); ?><?php echo $arw; ?></a>
								</div>
							</article>
						<?php endwhile; ?>
					</div>
				</div>
			</section>
			<?php
			wp_reset_postdata();
		endif;
	endif;
	?>

<?php endwhile; ?>

<?php
get_footer();
