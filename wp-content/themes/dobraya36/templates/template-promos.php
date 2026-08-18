<?php
/**
 * Template Name: Акции
 *
 * @package dobraya36
 */

get_header();
$intro = function_exists( 'get_field' ) ? get_field( 'promos_page_intro' ) : '';
$promos = function_exists( 'get_field' ) ? get_field( 'promos' ) : array();
$style_map = array( 'accent' => 'grad', 'default' => 'white', 'warm' => 'green' );

while ( have_posts() ) : the_post(); ?>

<header class="page-hero">
	<div class="wrap page-hero__inner">
		<span class="eyebrow eyebrow--green page-hero__eyebrow"><?php esc_html_e( 'Выгодно', 'dobraya36' ); ?></span>
		<h1 class="page-hero__title"><?php the_title(); ?></h1>
		<p class="page-hero__lead"><?php echo $intro ? esc_html( $intro ) : esc_html__( 'Актуальные предложения месяца. Качественная стоматология может быть доступной.', 'dobraya36' ); ?></p>
	</div>
</header>

<?php dobraya36_breadcrumbs(); ?>

<section class="section">
	<div class="wrap">
		<?php if ( ! empty( $promos ) ) : ?>
			<div class="promos" data-stagger>
				<?php foreach ( $promos as $p ) :
					$style = $p['style'] ?? 'default';
					$style = $style_map[ $style ] ?? $style;
					$cls   = 'promo';
					if ( 'grad' === $style ) { $cls .= ' promo--grad'; }
					if ( 'green' === $style ) { $cls .= ' promo--green'; }
					$btn = ( 'grad' === $style || 'green' === $style ) ? 'btn--white' : 'btn--blue';
					?>
					<article class="<?php echo esc_attr( $cls ); ?>">
						<?php if ( ! empty( $p['badge'] ) ) : ?><span class="promo__badge"><?php echo esc_html( $p['badge'] ); ?></span><?php endif; ?>
						<h2 class="promo__title"><?php echo esc_html( $p['title'] ); ?></h2>
						<?php if ( ! empty( $p['text'] ) ) : ?><p class="promo__text"><?php echo esc_html( $p['text'] ); ?></p><?php endif; ?>
						<div class="promo__foot">
							<?php if ( ! empty( $p['price_new'] ) ) : ?>
								<span class="promo__price"><b><?php echo esc_html( $p['price_new'] ); ?> ₽</b><?php if ( ! empty( $p['price_old'] ) ) : ?><span class="old"><?php echo esc_html( $p['price_old'] ); ?> ₽</span><?php endif; ?></span>
							<?php else : ?><span></span><?php endif; ?>
							<a class="btn <?php echo esc_attr( $btn ); ?> btn--sm" href="#" data-booking><?php echo esc_html( $p['cta'] ?: 'Записаться' ); ?></a>
						</div>
					</article>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>

		<?php if ( get_the_content() ) : ?>
			<div class="prose wrap--narrow" style="margin:2.5rem auto 0"><?php the_content(); ?></div>
		<?php endif; ?>
	</div>
</section>

<section class="section" style="padding-top:0">
	<div class="wrap">
		<div class="cta-band" data-anim>
			<div>
				<h2><?php esc_html_e( 'Хотите воспользоваться акцией?', 'dobraya36' ); ?></h2>
				<p><?php esc_html_e( 'Оставьте заявку — администратор расскажет об условиях и подберёт удобное время.', 'dobraya36' ); ?></p>
			</div>
			<a class="btn btn--white btn--lg" href="#" data-booking><?php esc_html_e( 'Записаться', 'dobraya36' ); ?></a>
		</div>
	</div>
</section>

<?php endwhile; get_footer();
