<?php
/**
 * Каталог услуг (архив CPT service), сгруппированный по направлениям.
 *
 * @package dobraya36
 */

get_header();
$arw = '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>';

/**
 * Отрисовать карточку услуги.
 */
function dobraya36_service_card( $arw ) {
	$id    = get_the_ID();
	$icon  = function_exists( 'get_field' ) ? get_field( 'service_icon', $id ) : 'tooth';
	$price = function_exists( 'get_field' ) ? get_field( 'service_price_from', $id ) : '';
	$sub   = function_exists( 'get_field' ) ? get_field( 'service_subtitle', $id ) : '';
	$intro = function_exists( 'get_field' ) ? get_field( 'service_intro', $id ) : '';
	if ( ! $intro ) { $intro = wp_trim_words( get_the_excerpt(), 16, '…' ); }
	?>
	<article class="cat-card">
		<div class="cat-card__ico"><?php echo dobraya36_icon( $icon ?: 'tooth' ); ?></div>
		<h3 class="cat-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
		<p class="cat-card__text"><?php echo esc_html( $intro ); ?></p>
		<div class="cat-card__foot">
			<?php if ( $price ) : ?>
				<span class="cat-card__price"><span>от</span> <?php echo esc_html( $price ); ?> ₽</span>
			<?php else : ?><span></span><?php endif; ?>
			<a class="cat-card__more" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Подробнее', 'dobraya36' ); ?><?php echo $arw; ?></a>
		</div>
	</article>
	<?php
}
?>

<header class="page-hero">
	<div class="wrap page-hero__inner">
		<span class="eyebrow page-hero__eyebrow"><?php esc_html_e( 'Направления', 'dobraya36' ); ?></span>
		<h1 class="page-hero__title"><?php esc_html_e( 'Услуги', 'dobraya36' ); ?> <span class="hl"><?php esc_html_e( 'клиники', 'dobraya36' ); ?></span></h1>
		<p class="page-hero__lead"><?php esc_html_e( 'Полный спектр стоматологических услуг для взрослых и детей — от профилактики и лечения до имплантации и протезирования. Честные цены и удвоенная гарантия.', 'dobraya36' ); ?></p>
	</div>
</header>

<?php dobraya36_breadcrumbs(); ?>

<section class="section">
	<div class="wrap">
		<?php
		$cats = get_terms( array( 'taxonomy' => 'service_cat', 'hide_empty' => true ) );
		// Логический порядок направлений (медицинский, а не алфавитный).
		$order = array( 'terapiya', 'parodontologiya', 'hirurgiya', 'implantologiya', 'ortopediya', 'ortodontiya', 'estetika', 'beremennym' );
		if ( ! empty( $cats ) && ! is_wp_error( $cats ) ) :
			usort( $cats, function ( $a, $b ) use ( $order ) {
				$ia = array_search( $a->slug, $order, true );
				$ib = array_search( $b->slug, $order, true );
				$ia = ( false === $ia ) ? 99 : $ia;
				$ib = ( false === $ib ) ? 99 : $ib;
				return $ia <=> $ib;
			} );
			?>
			<nav class="catalog-nav" aria-label="<?php esc_attr_e( 'Направления', 'dobraya36' ); ?>">
				<?php foreach ( $cats as $cat ) : ?>
					<a class="catalog-nav__chip" href="#dir-<?php echo esc_attr( $cat->slug ); ?>"><?php echo esc_html( $cat->name ); ?><span class="catalog-nav__count"><?php echo (int) $cat->count; ?></span></a>
				<?php endforeach; ?>
			</nav>
			<?php
			foreach ( $cats as $cat ) :
				$q = new WP_Query( array(
					'post_type'      => 'service',
					'posts_per_page' => -1,
					'orderby'        => array( 'menu_order' => 'ASC', 'title' => 'ASC' ),
					'tax_query'      => array( array( 'taxonomy' => 'service_cat', 'field' => 'term_id', 'terms' => $cat->term_id ) ),
				) );
				if ( ! $q->have_posts() ) { continue; }
				?>
				<div class="catalog__group" id="dir-<?php echo esc_attr( $cat->slug ); ?>" data-stagger>
					<h2 class="catalog__group-title"><a href="<?php echo esc_url( get_term_link( $cat ) ); ?>" style="color:inherit"><?php echo esc_html( $cat->name ); ?></a></h2>
					<div class="catalog-grid">
						<?php while ( $q->have_posts() ) : $q->the_post(); dobraya36_service_card( $arw ); endwhile; ?>
					</div>
				</div>
				<?php
				wp_reset_postdata();
			endforeach;
		else :
			// Без направлений — просто все услуги.
			?>
			<div class="catalog-grid" data-stagger>
				<?php while ( have_posts() ) : the_post(); dobraya36_service_card( $arw ); endwhile; ?>
			</div>
		<?php endif; ?>
	</div>
</section>

<section class="section" style="padding-top:0">
	<div class="wrap">
		<div class="cta-band" data-anim>
			<div>
				<h2><?php esc_html_e( 'Не знаете, какая услуга нужна?', 'dobraya36' ); ?></h2>
				<p><?php esc_html_e( 'Запишитесь на бесплатную консультацию — врач осмотрит и составит план лечения.', 'dobraya36' ); ?></p>
			</div>
			<a class="btn btn--white btn--lg" href="#" data-booking><?php esc_html_e( 'Записаться на приём', 'dobraya36' ); ?></a>
		</div>
	</div>
</section>

<?php
get_footer();
