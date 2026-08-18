<?php
/**
 * Профиль врача.
 *
 * @package dobraya36
 */

get_header();
$arw = '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>';

while ( have_posts() ) :
	the_post();
	$pos   = get_field( 'doc_position' );
	$spec  = get_field( 'doc_specialization' );
	$exp   = get_field( 'doc_experience' );
	$edu   = get_field( 'doc_education' );
	$dirs  = get_field( 'doc_categories' );
	?>

	<header class="page-hero">
		<div class="wrap page-hero__inner">
			<span class="eyebrow page-hero__eyebrow"><?php esc_html_e( 'Врач', 'dobraya36' ); ?></span>
			<h1 class="page-hero__title"><?php the_title(); ?></h1>
			<?php if ( $pos ) : ?><p class="page-hero__lead"><?php echo esc_html( $pos ); ?></p><?php endif; ?>
		</div>
	</header>

	<?php dobraya36_breadcrumbs(); ?>

	<section class="section">
		<div class="wrap doctor-layout">
			<div class="doctor-photo">
				<?php if ( has_post_thumbnail() ) : ?>
					<?php the_post_thumbnail( 'dobraya36_card', array( 'alt' => the_title_attribute( array( 'echo' => false ) ) ) ); ?>
				<?php else : ?>
					<?php echo dobraya36_icon( 'heart', 'ph' ); ?>
				<?php endif; ?>
			</div>
			<div class="doctor-main">
				<div class="doctor-meta">
					<?php if ( $spec ) : ?><span class="doctor-tag"><?php echo esc_html( $spec ); ?></span><?php endif; ?>
					<?php if ( $exp ) : ?><span class="doctor-tag doctor-tag--green"><?php printf( esc_html__( 'Стаж %d лет', 'dobraya36' ), (int) $exp ); ?></span><?php endif; ?>
					<?php
					if ( $dirs ) {
						foreach ( array_filter( array_map( 'trim', explode( ',', $dirs ) ) ) as $d ) {
							echo '<span class="doctor-tag">' . esc_html( $d ) . '</span>';
						}
					}
					?>
				</div>

				<div class="prose">
					<?php the_content(); ?>
					<?php if ( $edu ) : ?>
						<h2><?php esc_html_e( 'Образование', 'dobraya36' ); ?></h2>
						<?php echo wp_kses_post( wpautop( $edu ) ); ?>
					<?php endif; ?>
				</div>

				<?php
				$doc_clinics = get_field( 'doc_clinics' );
				if ( ! empty( $doc_clinics ) ) :
					?>
					<h2 style="margin-top:2rem"><?php esc_html_e( 'Принимает в клиниках', 'dobraya36' ); ?></h2>
					<div class="doctor-clinics">
						<?php foreach ( $doc_clinics as $clinic_id ) :
							$clinic_id = is_object( $clinic_id ) ? $clinic_id->ID : (int) $clinic_id;
							?>
							<a class="doctor-clinic" href="<?php echo esc_url( get_permalink( $clinic_id ) ); ?>">
								<?php echo dobraya36_icon( 'pin' ); ?>
								<span>
									<b><?php echo esc_html( get_the_title( $clinic_id ) ); ?></b>
									<span><?php echo esc_html( get_field( 'clinic_address', $clinic_id ) ); ?></span>
								</span>
							</a>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>

				<div class="post-cta">
					<h3><?php esc_html_e( 'Записаться к врачу', 'dobraya36' ); ?></h3>
					<p><?php esc_html_e( 'Оставьте заявку — администратор подберёт удобное время приёма.', 'dobraya36' ); ?></p>
					<a class="btn btn--grad" href="<?php echo esc_url( home_url( '/kontakty/' ) ); ?>"><?php esc_html_e( 'Записаться на приём', 'dobraya36' ); ?><?php echo $arw; ?></a>
				</div>
			</div>
		</div>
	</section>

<?php endwhile; ?>

<?php get_footer();
