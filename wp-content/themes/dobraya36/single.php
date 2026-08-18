<?php
/**
 * Отдельная статья (v3.2 — переработанный шаблон).
 *
 * @package dobraya36
 */

get_header();
$arw = '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>';

while ( have_posts() ) :
	the_post();
	$id    = get_the_ID();
	$cats  = get_the_category();
	$cat   = ! empty( $cats ) ? $cats[0] : null;
	$words = count( preg_split( '/\s+/u', trim( wp_strip_all_tags( get_the_content() ) ) ) );
	$rt    = max( 1, (int) ceil( $words / 160 ) );
	?>

	<header class="page-hero article-hero">
		<div class="wrap wrap--narrow page-hero__inner">
			<?php if ( $cat ) : ?>
				<a class="page-head__cat" href="<?php echo esc_url( get_category_link( $cat ) ); ?>"><?php echo esc_html( $cat->name ); ?></a>
			<?php endif; ?>
			<h1 class="page-hero__title article-title"><?php the_title(); ?></h1>
			<div class="article-meta">
				<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
				<span class="dot">·</span>
				<span><?php printf( esc_html__( '%d мин чтения', 'dobraya36' ), $rt ); ?></span>
			</div>
		</div>
	</header>

	<?php dobraya36_breadcrumbs(); ?>

	<section class="section">
		<div class="wrap article-layout">
			<article class="article-main">
				<?php if ( has_post_thumbnail() ) : ?>
					<figure class="post-hero-img"><?php the_post_thumbnail( 'dobraya36_wide', array( 'alt' => the_title_attribute( array( 'echo' => false ) ) ) ); ?></figure>
				<?php endif; ?>
				<div class="prose">
					<?php the_content(); ?>
					<?php wp_link_pages( array( 'before' => '<div class="page-links">' . esc_html__( 'Страницы:', 'dobraya36' ), 'after' => '</div>' ) ); ?>
				</div>

				<div class="article-foot">
					<div class="article-share">
						<span><?php esc_html_e( 'Поделиться:', 'dobraya36' ); ?></span>
						<a href="https://vk.com/share.php?url=<?php echo rawurlencode( get_permalink() ); ?>" target="_blank" rel="noopener nofollow" aria-label="ВКонтакте">VK</a>
						<a href="https://t.me/share/url?url=<?php echo rawurlencode( get_permalink() ); ?>&text=<?php echo rawurlencode( get_the_title() ); ?>" target="_blank" rel="noopener nofollow" aria-label="Telegram">TG</a>
						<a href="https://api.whatsapp.com/send?text=<?php echo rawurlencode( get_the_title() . ' ' . get_permalink() ); ?>" target="_blank" rel="noopener nofollow" aria-label="WhatsApp">WA</a>
					</div>
					<div class="article-nav">
						<?php previous_post_link( '%link', '← ' . esc_html__( 'Предыдущая', 'dobraya36' ) ); ?>
						<?php next_post_link( '%link', esc_html__( 'Следующая', 'dobraya36' ) . ' →' ); ?>
					</div>
				</div>
			</article>

			<aside class="article-aside">
				<div class="aside-card aside-card--cta">
					<h3><?php esc_html_e( 'Остались вопросы?', 'dobraya36' ); ?></h3>
					<p><?php esc_html_e( 'Запишитесь на бесплатную консультацию — врач ответит на все вопросы и составит план лечения.', 'dobraya36' ); ?></p>
					<a class="btn btn--white" style="margin-top:1rem" href="#" data-booking><?php esc_html_e( 'Записаться', 'dobraya36' ); ?><?php echo $arw; ?></a>
				</div>

				<?php
				$recent = new WP_Query( array( 'post_type' => 'post', 'posts_per_page' => 4, 'post__not_in' => array( $id ), 'ignore_sticky_posts' => 1 ) );
				if ( $recent->have_posts() ) :
					?>
					<div class="aside-card">
						<h3 class="aside-title"><?php esc_html_e( 'Читайте также', 'dobraya36' ); ?></h3>
						<ul class="aside-posts">
							<?php while ( $recent->have_posts() ) : $recent->the_post(); ?>
								<li>
									<a class="aside-post" href="<?php the_permalink(); ?>">
										<span class="aside-post__thumb">
											<?php if ( has_post_thumbnail() ) { the_post_thumbnail( 'thumbnail' ); } else { echo dobraya36_icon( 'tooth', 'aside-post__ico' ); } ?>
										</span>
										<span class="aside-post__title"><?php the_title(); ?></span>
									</a>
								</li>
							<?php endwhile; wp_reset_postdata(); ?>
						</ul>
					</div>
				<?php endif; ?>
			</aside>
		</div>
	</section>

	<?php
	// Похожие статьи.
	if ( $cat ) :
		$rel = new WP_Query( array(
			'post_type'      => 'post',
			'posts_per_page' => 3,
			'post__not_in'   => array( $id ),
			'ignore_sticky_posts' => 1,
			'category__in'   => array( $cat->term_id ),
		) );
		if ( $rel->have_posts() ) :
			?>
			<section class="section section--tint">
				<div class="wrap">
					<div class="section-head section-head--left"><h2 class="section-title"><?php esc_html_e( 'Похожие статьи', 'dobraya36' ); ?></h2></div>
					<div class="posts-grid" data-stagger>
						<?php while ( $rel->have_posts() ) : $rel->the_post(); get_template_part( 'template-parts/content', 'card' ); endwhile; wp_reset_postdata(); ?>
					</div>
				</div>
			</section>
			<?php
		endif;
	endif;
	?>

<?php endwhile; get_footer();
