<?php
/**
 * Карточка записи (v3).
 *
 * @package dobraya36
 */
$arw = '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>';
?>
<article <?php post_class( 'post-card' ); ?>>
	<a class="post-card__media" href="<?php the_permalink(); ?>">
		<?php if ( has_post_thumbnail() ) : ?>
			<?php the_post_thumbnail( 'dobraya36_card', array( 'loading' => 'lazy', 'alt' => the_title_attribute( array( 'echo' => false ) ) ) ); ?>
		<?php else : ?>
			<?php echo dobraya36_icon( 'tooth', 'post-card__ph' ); ?>
		<?php endif; ?>
	</a>
	<div class="post-card__body">
		<?php $cats = get_the_category(); if ( ! empty( $cats ) ) : ?>
			<span class="post-card__cat"><?php echo esc_html( $cats[0]->name ); ?></span>
		<?php endif; ?>
		<h3 class="post-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
		<p class="post-card__ex"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 20, '…' ) ); ?></p>
		<a class="post-card__more" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Читать', 'dobraya36' ); ?><?php echo $arw; ?></a>
	</div>
</article>
