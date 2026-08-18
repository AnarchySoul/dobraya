<?php
/**
 * Наши врачи (архив CPT doctor).
 *
 * @package dobraya36
 */

get_header();
?>

<header class="page-hero">
	<div class="wrap page-hero__inner">
		<span class="eyebrow page-hero__eyebrow"><?php esc_html_e( 'Команда', 'dobraya36' ); ?></span>
		<h1 class="page-hero__title"><?php esc_html_e( 'Наши', 'dobraya36' ); ?> <span class="hl"><?php esc_html_e( 'врачи', 'dobraya36' ); ?></span></h1>
		<p class="page-hero__lead"><?php esc_html_e( 'Внимательные и опытные специалисты, которые любят своё дело и находят подход к каждому пациенту.', 'dobraya36' ); ?></p>
	</div>
</header>

<?php dobraya36_breadcrumbs(); ?>

<section class="section">
	<div class="wrap">
		<?php if ( have_posts() ) : ?>
			<div class="team-grid" data-stagger>
				<?php
				while ( have_posts() ) :
					the_post();
					$pos = get_field( 'doc_position' );
					$exp = get_field( 'doc_experience' );
					?>
					<article class="doc-card">
						<a class="doc-card__photo" href="<?php the_permalink(); ?>">
							<?php if ( has_post_thumbnail() ) : ?>
								<?php the_post_thumbnail( 'dobraya36_card', array( 'loading' => 'lazy', 'alt' => the_title_attribute( array( 'echo' => false ) ) ) ); ?>
							<?php else : ?>
								<?php echo dobraya36_icon( 'heart', 'ph' ); ?>
							<?php endif; ?>
						</a>
						<div class="doc-card__body">
							<h2 class="doc-card__name"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
							<?php if ( $pos ) : ?><div class="doc-card__pos"><?php echo esc_html( $pos ); ?></div><?php endif; ?>
							<?php if ( $exp ) : ?><div class="doc-card__exp"><?php printf( esc_html__( 'Стаж %d лет', 'dobraya36' ), (int) $exp ); ?></div><?php endif; ?>
						</div>
					</article>
				<?php endwhile; ?>
			</div>
		<?php else : ?>
			<p class="no-posts"><?php esc_html_e( 'Информация о врачах скоро появится.', 'dobraya36' ); ?></p>
		<?php endif; ?>
	</div>
</section>

<?php get_footer();
