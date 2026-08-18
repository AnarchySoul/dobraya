<?php
/**
 * Шаблон страницы.
 *
 * @package dobraya36
 */

get_header();

while ( have_posts() ) :
	the_post();
	?>
	<section class="page-head">
		<div class="wrap">
			<h1 class="page-head__title"><?php the_title(); ?></h1>
		</div>
	</section>

	<?php dobraya36_breadcrumbs(); ?>

	<section class="section">
		<div class="wrap wrap--narrow">
			<div class="prose">
				<?php the_content(); ?>
				<?php
				wp_link_pages(
					array(
						'before' => '<div class="page-links">' . esc_html__( 'Страницы:', 'dobraya36' ),
						'after'  => '</div>',
					)
				);
				?>
			</div>
		</div>
	</section>
	<?php
endwhile;

get_footer();
