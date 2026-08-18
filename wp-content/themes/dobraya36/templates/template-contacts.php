<?php
/**
 * Template Name: Контакты
 *
 * @package dobraya36
 */

get_header();
$arw       = '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>';
$phone_opt = dobraya36_opt( 'phone', '+7 (473) 211-30-11' );
$email_opt = dobraya36_opt( 'email', 'info@dobraya36.ru' );
$intro     = function_exists( 'get_field' ) ? get_field( 'contacts_intro' ) : '';

$clinics = get_posts( array( 'post_type' => 'clinic', 'numberposts' => -1, 'orderby' => 'menu_order', 'order' => 'ASC' ) );

while ( have_posts() ) : the_post(); ?>

<header class="page-hero">
	<div class="wrap page-hero__inner">
		<span class="eyebrow page-hero__eyebrow"><?php esc_html_e( 'Контакты', 'dobraya36' ); ?></span>
		<h1 class="page-hero__title"><?php the_title(); ?></h1>
		<p class="page-hero__lead"><?php echo $intro ? esc_html( $intro ) : esc_html__( 'Три клиники в удобных районах Воронежа. Позвоните или запишитесь онлайн — мы подберём удобное время приёма.', 'dobraya36' ); ?></p>
	</div>
</header>

<?php dobraya36_breadcrumbs(); ?>

<section class="section">
	<div class="wrap branches-grid" data-stagger>
		<?php foreach ( $clinics as $c ) :
			$addr = get_field( 'clinic_address', $c->ID );
			$dist = get_field( 'clinic_district', $c->ID );
			$ph   = get_field( 'clinic_phone', $c->ID ) ?: $phone_opt;
			$hw   = get_field( 'clinic_hours_weekday', $c->ID );
			$he   = get_field( 'clinic_hours_weekend', $c->ID );
			$map  = get_field( 'clinic_map_url', $c->ID );
			$link = get_permalink( $c );
			?>
			<div class="branch">
				<div class="branch__info">
					<h2 class="branch__title"><a href="<?php echo esc_url( $link ); ?>" style="color:inherit"><?php echo esc_html( $c->post_title ); ?></a></h2>
					<div class="branch__row"><?php echo dobraya36_icon( 'pin' ); ?><span><?php echo esc_html( $addr ); ?></span></div>
					<div class="branch__row"><?php echo dobraya36_icon( 'phone' ); ?><a href="tel:<?php echo esc_attr( dobraya36_tel( $ph ) ); ?>"><?php echo esc_html( $ph ); ?></a></div>
					<div class="branch__row"><?php echo dobraya36_icon( 'clock' ); ?><span><?php echo esc_html( $hw ); ?><br><?php echo esc_html( $he ); ?></span></div>
					<div class="branch__row"><?php echo dobraya36_icon( 'mail' ); ?><a href="mailto:<?php echo esc_attr( $email_opt ); ?>"><?php echo esc_html( $email_opt ); ?></a></div>
					<div class="branch__actions">
						<a class="btn btn--grad btn--sm" href="#" data-booking data-clinic="<?php echo esc_attr( $c->post_title ); ?>"><?php esc_html_e( 'Записаться сюда', 'dobraya36' ); ?></a>
						<a class="cat-card__more" href="<?php echo esc_url( $link ); ?>"><?php esc_html_e( 'О клинике', 'dobraya36' ); ?><?php echo $arw; ?></a>
					</div>
				</div>
				<?php if ( $map ) : ?>
					<div class="branch__map"><iframe src="<?php echo esc_url( $map ); ?>" loading="lazy" title="<?php echo esc_attr( $c->post_title ); ?>"></iframe></div>
				<?php else : ?>
					<div class="branch__map branch__map--ph"><?php echo dobraya36_icon( 'pin' ); ?></div>
				<?php endif; ?>
			</div>
		<?php endforeach; ?>
	</div>
</section>

<?php if ( get_the_content() ) : ?>
	<section class="section" style="padding-top:0">
		<div class="wrap wrap--narrow prose"><?php the_content(); ?></div>
	</section>
<?php endif; ?>

<section class="section" style="padding-top:0">
	<div class="wrap">
		<div class="cta-band" data-anim>
			<div>
				<h2><?php esc_html_e( 'Запишитесь на приём онлайн', 'dobraya36' ); ?></h2>
				<p><?php esc_html_e( 'Оставьте заявку — администратор перезвонит в течение 15 минут и подберёт удобное время.', 'dobraya36' ); ?></p>
			</div>
			<a class="btn btn--white btn--lg" href="#" data-booking><?php esc_html_e( 'Записаться', 'dobraya36' ); ?></a>
		</div>
	</div>
</section>

<?php endwhile; get_footer();
