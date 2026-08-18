<?php
/**
 * Услуги одного направления.
 *
 * @package dobraya36
 */

get_header();
$arw  = '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>';
$term = get_queried_object();
?>

<header class="page-hero">
	<div class="wrap page-hero__inner">
		<span class="eyebrow page-hero__eyebrow"><?php esc_html_e( 'Направление', 'dobraya36' ); ?></span>
		<h1 class="page-hero__title"><?php echo esc_html( single_term_title( '', false ) ); ?></h1>
		<?php if ( $term && $term->description ) : ?>
			<p class="page-hero__lead"><?php echo esc_html( $term->description ); ?></p>
		<?php endif; ?>
	</div>
</header>

<?php dobraya36_breadcrumbs(); ?>

<section class="section">
	<div class="wrap">
		<?php if ( have_posts() ) : ?>
			<div class="catalog-grid" data-stagger>
				<?php
				while ( have_posts() ) :
					the_post();
					$id    = get_the_ID();
					$icon  = get_field( 'service_icon' ) ?: 'tooth';
					$price = get_field( 'service_price_from' );
					$intro = get_field( 'service_intro' ) ?: wp_trim_words( get_the_excerpt(), 16, '…' );
					?>
					<article class="cat-card">
						<div class="cat-card__ico"><?php echo dobraya36_icon( $icon ); ?></div>
						<h3 class="cat-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
						<p class="cat-card__text"><?php echo esc_html( $intro ); ?></p>
						<div class="cat-card__foot">
							<?php if ( $price ) : ?><span class="cat-card__price"><span>от</span> <?php echo esc_html( $price ); ?> ₽</span><?php else : ?><span></span><?php endif; ?>
							<a class="cat-card__more" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Подробнее', 'dobraya36' ); ?><?php echo $arw; ?></a>
						</div>
					</article>
				<?php endwhile; ?>
			</div>
		<?php else : ?>
			<p class="no-posts"><?php esc_html_e( 'В этом направлении пока нет услуг.', 'dobraya36' ); ?></p>
		<?php endif; ?>
		<div class="section-actions"><a class="btn btn--ghost" href="<?php echo esc_url( get_post_type_archive_link( 'service' ) ); ?>"><?php esc_html_e( 'Все услуги', 'dobraya36' ); ?></a></div>
	</div>
</section>

<?php get_footer();
