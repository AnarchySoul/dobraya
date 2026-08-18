<?php
/**
 * Основной шаблон / блог.
 *
 * @package dobraya36
 */

get_header();
?>

<section class="page-head">
	<div class="wrap">
		<h1 class="page-head__title">
			<?php
			if ( is_home() && ! is_front_page() ) {
				single_post_title();
			} elseif ( is_archive() ) {
				the_archive_title();
			} elseif ( is_search() ) {
				printf( esc_html__( 'Результаты поиска: %s', 'dobraya36' ), '<span>' . esc_html( get_search_query() ) . '</span>' );
			} else {
				esc_html_e( 'Статьи', 'dobraya36' );
			}
			?>
		</h1>
		<?php the_archive_description( '<p class="page-head__desc">', '</p>' ); ?>
	</div>
</section>

<?php dobraya36_breadcrumbs(); ?>

<section class="section">
	<div class="wrap">
		<?php if ( have_posts() ) : ?>
			<div class="posts-grid" data-stagger>
				<?php
				while ( have_posts() ) :
					the_post();
					get_template_part( 'template-parts/content', 'card' );
				endwhile;
				?>
			</div>

			<div class="pagination">
				<?php
				the_posts_pagination(
					array(
						'mid_size'  => 1,
						'prev_text' => __( 'Назад', 'dobraya36' ),
						'next_text' => __( 'Вперёд', 'dobraya36' ),
					)
				);
				?>
			</div>
		<?php else : ?>
			<p class="no-posts"><?php esc_html_e( 'Записи не найдены.', 'dobraya36' ); ?></p>
		<?php endif; ?>
	</div>
</section>

<?php
get_footer();
