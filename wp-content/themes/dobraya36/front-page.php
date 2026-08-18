<?php
/**
 * Главная страница (v3 — светлая клиника, цвета логотипа).
 *
 * @package dobraya36
 */

get_header();

$F = function ( $name, $fallback = '' ) {
	$v = function_exists( 'get_field' ) ? get_field( $name ) : '';
	return ( $v === '' || $v === null || $v === false ) ? $fallback : $v;
};
$arw = '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>';
$star = '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="m12 3 2.6 5.3 5.9.9-4.3 4.1 1 5.8L12 17l-5.2 2.6 1-5.8L3.5 9.2l5.9-.9L12 3Z"/></svg>';
$check = '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="m4 12.5 5 5 11-11" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/></svg>';
$mark = DOBRAYA36_URI . '/assets/img/icon-512.png';
?>

<?php /* ================= HERO ================= */ ?>
<section class="hero">
	<div class="wrap">
		<div class="hero__grid">
			<div class="hero__content" data-anim>
				<span class="eyebrow hero__eyebrow"><?php echo dobraya36_icon( 'heart' ); ?><?php echo esc_html( $F( 'hero_eyebrow', 'Стоматология в Воронеже с 2013 года' ) ); ?></span>
				<h1 class="hero__title">
					<?php echo esc_html( $F( 'hero_title', 'Заботливая стоматология для всей семьи' ) ); ?>
					<?php if ( $acc = $F( 'hero_title_accent', 'без страха и боли' ) ) : ?><span class="hl-g"><?php echo esc_html( $acc ); ?></span><?php endif; ?>
				</h1>
				<p class="hero__sub"><?php echo esc_html( $F( 'hero_sub', 'Лечим, восстанавливаем и сохраняем здоровье зубов на современном оборудовании. Честные цены, рассрочка 0% и удвоенная гарантия.' ) ); ?></p>
				<div class="hero__actions">
					<a class="btn btn--grad btn--lg" href="#zapis"><?php echo esc_html( $F( 'hero_cta_primary', 'Записаться на приём' ) ); ?><?php echo $arw; ?></a>
					<a class="btn btn--ghost btn--lg" href="#uslugi"><?php echo esc_html( $F( 'hero_cta_secondary', 'Услуги и цены' ) ); ?></a>
				</div>
				<div class="hero__chips">
					<?php
					$hero_stats = $F( 'hero_stats', array(
						array( 'value' => '12 лет', 'label' => 'опыта' ),
						array( 'value' => '3', 'label' => 'клиники' ),
						array( 'value' => '2 года', 'label' => 'гарантии' ),
					) );
					$ci = 0;
					foreach ( array_slice( $hero_stats, 0, 3 ) as $s ) :
						$ci++; ?>
						<span class="chip <?php echo $ci % 2 ? 'chip--blue' : ''; ?>"><?php echo $check; ?><b data-count><?php echo esc_html( $s['value'] ); ?></b>&nbsp;<?php echo esc_html( $s['label'] ); ?></span>
					<?php endforeach; ?>
				</div>
			</div>

			<div class="hero__visual" data-anim>
				<div class="hero__panel">
					<img class="hero__mark" src="<?php echo esc_url( $mark ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" width="512" height="512">
				</div>
				<div class="hero__float hero__float--offer float-a">
					<span class="ico"><?php echo dobraya36_icon( 'implant' ); ?></span>
					<div>
						<b><?php echo esc_html( $F( 'offer_price_new', '27 900' ) ); ?> ₽</b>
						<span><?php echo esc_html( $F( 'offer_label', 'Имплант под ключ' ) ); ?></span>
					</div>
				</div>
				<div class="hero__float hero__float--rating float-b">
					<span class="ico"><?php echo dobraya36_icon( 'heart' ); ?></span>
					<div>
						<span class="stars"><?php echo str_repeat( $star, 5 ); ?></span>
						<span>Любят пациенты</span>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<?php /* ================= ПРЕИМУЩЕСТВА ================= */ ?>
<section class="section">
	<div class="wrap">
		<div class="features" data-stagger>
			<?php
			$advantages = $F( 'advantages', array(
				array( 'icon' => 'card', 'title' => 'Рассрочка 0%', 'text' => 'Оплата частями без процентов, банка и справок.' ),
				array( 'icon' => 'heart', 'title' => 'Внимательные врачи', 'text' => 'Команда, которая любит своё дело и находит подход.' ),
				array( 'icon' => 'shield', 'title' => 'Гарантия 2 года', 'text' => 'Удвоенная гарантия на все виды работ.' ),
				array( 'icon' => 'gift', 'title' => 'Акции и бонусы', 'text' => 'Выгодные предложения и подарки каждый месяц.' ),
			) );
			foreach ( array_slice( $advantages, 0, 4 ) as $a ) : ?>
				<div class="feature">
					<div class="feature__ico"><?php echo dobraya36_icon( $a['icon'] ?: 'check' ); ?></div>
					<h3 class="feature__title"><?php echo esc_html( $a['title'] ); ?></h3>
					<p class="feature__text"><?php echo esc_html( $a['text'] ); ?></p>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<?php /* ================= УСЛУГИ (bento) ================= */ ?>
<section class="section section--tint" id="uslugi">
	<div class="wrap">
		<div class="section-head">
			<span class="eyebrow"><?php echo esc_html( $F( 'services_eyebrow', 'Направления' ) ); ?></span>
			<h2 class="section-title"><?php echo dobraya36_accent_last( $F( 'services_title', 'Всё для здоровья ваших зубов' ), 2, 'hl' ); ?></h2>
			<p class="section-sub"><?php echo esc_html( $F( 'services_sub', 'Полный спектр стоматологических услуг для взрослых и детей — от профилактики до сложной имплантации.' ) ); ?></p>
		</div>
		<?php
		// Источник — реальные услуги (CPT service). Фолбэк — статичный список.
		$archive_url = get_post_type_archive_link( 'service' ) ?: home_url( '/uslugi/' );
		$services    = array();
		$svc_q       = new WP_Query( array( 'post_type' => 'service', 'posts_per_page' => 8, 'orderby' => array( 'menu_order' => 'ASC', 'title' => 'ASC' ), 'no_found_rows' => true ) );
		if ( $svc_q->have_posts() ) {
			while ( $svc_q->have_posts() ) {
				$svc_q->the_post();
				$services[] = array(
					'icon'  => ( function_exists( 'get_field' ) ? get_field( 'service_icon' ) : '' ) ?: 'tooth',
					'title' => get_the_title(),
					'text'  => ( function_exists( 'get_field' ) ? get_field( 'service_intro' ) : '' ) ?: wp_trim_words( get_the_excerpt(), 12, '…' ),
					'link'  => get_permalink(),
				);
			}
			wp_reset_postdata();
		} else {
			$services = array(
				array( 'icon' => 'tooth', 'title' => 'Лечение зубов', 'text' => 'Кариес, пульпит, периодонтит без боли.', 'link' => $archive_url ),
				array( 'icon' => 'crown', 'title' => 'Протезирование', 'text' => 'Коронки, виниры и мосты как свои.', 'link' => $archive_url ),
				array( 'icon' => 'implant', 'title' => 'Имплантология', 'text' => 'Восстановление зубов имплантами премиум-систем.', 'link' => $archive_url ),
				array( 'icon' => 'braces', 'title' => 'Ортодонтия', 'text' => 'Брекеты и элайнеры для ровной улыбки.', 'link' => $archive_url ),
				array( 'icon' => 'heart', 'title' => 'Пародонтология', 'text' => 'Лечение и профилактика дёсен.', 'link' => $archive_url ),
				array( 'icon' => 'scalpel', 'title' => 'Хирургия', 'text' => 'Бережное удаление любой сложности.', 'link' => $archive_url ),
				array( 'icon' => 'sparkle', 'title' => 'Эстетика', 'text' => 'Отбеливание и реставрация зубов.', 'link' => $archive_url ),
				array( 'icon' => 'baby', 'title' => 'Детям и мамам', 'text' => 'Безопасное лечение с заботой.', 'link' => $archive_url ),
			);
		}
		// Фичевую плитку — услуга с «имплант» в названии, иначе первая.
		$feat_idx = 0;
		foreach ( $services as $k => $s ) {
			if ( false !== mb_stripos( $s['title'], 'мплант' ) ) { $feat_idx = $k; break; }
		}
		$feat      = $services[ $feat_idx ];
		$feat_link = ! empty( $feat['link'] ) ? $feat['link'] : $archive_url;
		?>
		<div class="bento" data-stagger>
			<a class="tile tile--feature" href="<?php echo esc_url( $feat_link ); ?>">
				<div class="tile__ico"><?php echo dobraya36_icon( $feat['icon'] ?: 'implant' ); ?></div>
				<h3 class="tile__title"><?php echo esc_html( $feat['title'] ); ?></h3>
				<p class="tile__text"><?php echo esc_html( $feat['text'] ); ?></p>
				<span class="tile__link"><?php esc_html_e( 'Подробнее об услуге', 'dobraya36' ); ?><?php echo $arw; ?></span>
			</a>
			<?php
			foreach ( $services as $k => $s ) :
				if ( $k === $feat_idx ) { continue; }
				$link = ! empty( $s['link'] ) ? $s['link'] : $archive_url;
				?>
				<a class="tile" href="<?php echo esc_url( $link ); ?>">
					<span class="tile__arrow"><?php echo $arw; ?></span>
					<div class="tile__ico"><?php echo dobraya36_icon( $s['icon'] ?: 'tooth' ); ?></div>
					<h3 class="tile__title"><?php echo esc_html( $s['title'] ); ?></h3>
					<p class="tile__text"><?php echo esc_html( $s['text'] ); ?></p>
				</a>
			<?php endforeach; ?>
			<a class="tile tile--cta" href="<?php echo esc_url( $archive_url ); ?>">
				<h3 class="tile__title"><?php esc_html_e( 'Все услуги клиники', 'dobraya36' ); ?></h3>
				<span class="btn btn--white btn--sm"><?php esc_html_e( 'Открыть каталог', 'dobraya36' ); ?></span>
			</a>
		</div>
	</div>
</section>

<?php /* ================= АКЦИИ ================= */ ?>
<section class="section" id="akcii">
	<div class="wrap">
		<div class="section-head">
			<span class="eyebrow eyebrow--green"><?php echo esc_html( $F( 'promos_eyebrow', 'Выгодно' ) ); ?></span>
			<h2 class="section-title"><?php echo dobraya36_accent_last( $F( 'promos_title', 'Акции этого месяца' ), 2, 'hl-g' ); ?></h2>
			<p class="section-sub"><?php echo esc_html( $F( 'promos_sub', 'Качественная стоматология может быть доступной.' ) ); ?></p>
		</div>
		<div class="promos" data-stagger>
			<?php
			$akcii_page = get_page_by_path( 'akcii' );
				$promos_src = ( $akcii_page && function_exists( 'get_field' ) ) ? get_field( 'promos', $akcii_page->ID ) : array();
				$promos = ! empty( $promos_src ) ? array_slice( (array) $promos_src, 0, 3 ) : ( array(
				array( 'style' => 'grad', 'badge' => 'Имплантация', 'title' => 'Имплант OSSTEM под ключ', 'text' => 'Надёжная система с пожизненной гарантией.', 'price_new' => '27 900', 'price_old' => '34 000', 'cta' => 'Записаться' ),
				array( 'style' => 'white', 'badge' => 'Протезирование', 'title' => 'Коронка из диоксида циркония', 'text' => 'Прочная и эстетичная, как свой зуб.', 'price_new' => '15 000', 'price_old' => '', 'cta' => 'Записаться' ),
				array( 'style' => 'green', 'badge' => 'Подарок', 'title' => 'Скидка в честь дня рождения', 'text' => 'Приятная скидка в течение недели до и после праздника.', 'price_new' => '', 'price_old' => '', 'cta' => 'Узнать условия' ),
			) );
			$style_map = array( 'accent' => 'grad', 'default' => 'white', 'warm' => 'green' );
			foreach ( $promos as $p ) :
				$style = $p['style'] ?? 'white';
				$style = $style_map[ $style ] ?? $style;
				$cls   = 'promo';
				if ( 'grad' === $style ) { $cls .= ' promo--grad'; }
				if ( 'green' === $style ) { $cls .= ' promo--green'; }
				$btn = ( 'grad' === $style || 'green' === $style ) ? 'btn--white' : 'btn--blue';
				?>
				<article class="<?php echo esc_attr( $cls ); ?>">
					<span class="promo__badge"><?php echo esc_html( $p['badge'] ); ?></span>
					<h3 class="promo__title"><?php echo esc_html( $p['title'] ); ?></h3>
					<p class="promo__text"><?php echo esc_html( $p['text'] ); ?></p>
					<div class="promo__foot">
						<?php if ( ! empty( $p['price_new'] ) ) : ?>
							<span class="promo__price"><b><?php echo esc_html( $p['price_new'] ); ?> ₽</b><?php if ( ! empty( $p['price_old'] ) ) : ?><span class="old"><?php echo esc_html( $p['price_old'] ); ?> ₽</span><?php endif; ?></span>
						<?php else : ?><span></span><?php endif; ?>
						<a class="btn <?php echo esc_attr( $btn ); ?> btn--sm" href="#zapis"><?php echo esc_html( $p['cta'] ?: 'Записаться' ); ?></a>
					</div>
				</article>
			<?php endforeach; ?>
		</div>
		<?php if ( $akcii_page ) : ?>
			<div class="section-foot" style="text-align:center;margin-top:2rem">
				<a class="btn btn--ghost" href="<?php echo esc_url( get_permalink( $akcii_page->ID ) ); ?>"><?php esc_html_e( 'Все акции', 'dobraya36' ); ?><?php echo $arw; ?></a>
			</div>
		<?php endif; ?>
	</div>
</section>

<?php /* ================= ТЕХНОЛОГИИ ================= */ ?>
<section class="section section--tint">
	<div class="wrap">
		<div class="section-head">
			<span class="eyebrow"><?php echo esc_html( $F( 'tech_eyebrow', 'Технологии и безопасность' ) ); ?></span>
			<h2 class="section-title"><?php echo dobraya36_accent_last( $F( 'tech_title', 'Современно, безопасно, комфортно' ), 1, 'hl' ); ?></h2>
		</div>
		<div class="tech-grid" data-stagger>
			<?php
			$tech = $F( 'tech', array(
				array( 'icon' => 'spark', 'title' => 'Plasmolifting', 'text' => 'Плазмотерапия ускоряет заживление и укрепляет дёсны.' ),
				array( 'icon' => 'sparkle', 'title' => 'Отбеливание', 'text' => 'Осветление эмали за один визит без вреда для зубов.' ),
				array( 'icon' => 'shield', 'title' => 'Стерильность', 'text' => 'Коффердам, одноразовые инструменты, полная стерилизация.' ),
			) );
			foreach ( array_slice( $tech, 0, 3 ) as $t ) : ?>
				<div class="tech">
					<div class="tech__ico"><?php echo dobraya36_icon( $t['icon'] ?: 'spark' ); ?></div>
					<div>
						<h3 class="tech__title"><?php echo esc_html( $t['title'] ); ?></h3>
						<p class="tech__text"><?php echo esc_html( $t['text'] ); ?></p>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<?php /* ================= О КЛИНИКЕ ================= */ ?>
<section class="section" id="o-nas">
	<div class="wrap">
		<div class="about">
			<div class="about__media" data-anim>
				<div class="about__blob"><?php echo dobraya36_icon( 'tooth', 'ph' ); ?></div>
				<div class="about__doc">
					<b><?php echo esc_html( $F( 'director_name', 'Беликова Юлия Константиновна' ) ); ?></b>
					<span><?php echo esc_html( $F( 'director_role', 'Главный врач и основатель' ) ); ?></span>
				</div>
			</div>
			<div class="about__content" data-anim>
				<span class="eyebrow eyebrow--green"><?php echo esc_html( $F( 'about_eyebrow', 'О клинике' ) ); ?></span>
				<h2 class="section-title section-head--left" style="margin:.9rem 0 1rem"><?php echo dobraya36_accent_last( $F( 'about_title', 'Добрая стоматология — это про заботу' ), 1, 'hl-g' ); ?></h2>
				<div class="about__text"><?php echo wp_kses_post( $F( 'about_text', '<p>Мы работаем с 2013 года и всё это время относимся к каждому пациенту так, как хотели бы, чтобы относились к нам. Помогаем справиться со страхом и честно объясняем план лечения.</p>' ) ); ?></div>
				<div class="about__stats" data-stagger>
					<?php
					$about_stats = $F( 'about_stats', array(
						array( 'value' => '2013', 'label' => 'год основания' ),
						array( 'value' => '3', 'label' => 'клиники' ),
						array( 'value' => '10 000+', 'label' => 'пациентов' ),
					) );
					foreach ( array_slice( $about_stats, 0, 3 ) as $st ) : ?>
						<div class="about__stat"><b data-count><?php echo esc_html( $st['value'] ); ?></b><span><?php echo esc_html( $st['label'] ); ?></span></div>
					<?php endforeach; ?>
				</div>
				<div class="about__hours">
					<?php
					$about_hours = $F( 'about_hours', array(
						array( 'days' => 'Пн – Пт', 'time' => '9:00 – 20:00' ),
						array( 'days' => 'Сб – Вс', 'time' => '10:00 – 16:00' ),
					) );
					foreach ( $about_hours as $h ) : ?>
						<div class="about__hours-row"><?php echo dobraya36_icon( 'clock' ); ?><b><?php echo esc_html( $h['days'] ); ?></b> — <?php echo esc_html( $h['time'] ); ?></div>
					<?php endforeach; ?>
				</div>
				<a class="btn btn--grad" href="#zapis"><?php esc_html_e( 'Записаться на приём', 'dobraya36' ); ?><?php echo $arw; ?></a>
			</div>
		</div>
	</div>
</section>

<?php /* ================= СТАТЬИ ================= */ ?>
<?php
$art_q = new WP_Query( array( 'post_type' => 'post', 'posts_per_page' => (int) $F( 'articles_count', 3 ), 'ignore_sticky_posts' => 1 ) );
if ( $art_q->have_posts() ) : ?>
<section class="section section--tint" id="stati">
	<div class="wrap">
		<div class="section-head">
			<span class="eyebrow"><?php echo esc_html( $F( 'articles_eyebrow', 'Полезное' ) ); ?></span>
			<h2 class="section-title"><?php echo dobraya36_accent_last( $F( 'articles_title', 'Статьи о здоровье зубов' ), 1, 'hl' ); ?></h2>
		</div>
		<div class="posts-grid" data-stagger>
			<?php while ( $art_q->have_posts() ) : $art_q->the_post(); get_template_part( 'template-parts/content', 'card' ); endwhile; wp_reset_postdata(); ?>
		</div>
		<div class="section-actions">
			<a class="btn btn--ghost" href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ?: home_url( '/stati/' ) ); ?>"><?php esc_html_e( 'Все статьи', 'dobraya36' ); ?></a>
		</div>
	</div>
</section>
<?php endif; ?>

<?php /* ================= ОТЗЫВЫ ================= */ ?>
<section class="section" id="otzyvy">
	<div class="wrap">
		<div class="section-head">
			<span class="eyebrow eyebrow--green"><?php echo esc_html( $F( 'reviews_eyebrow', 'Отзывы' ) ); ?></span>
			<h2 class="section-title"><?php echo dobraya36_accent_last( $F( 'reviews_title', 'Что говорят наши пациенты' ), 1, 'hl-g' ); ?></h2>
		</div>
		<div class="reviews" data-stagger>
			<?php
			$reviews = $F( 'reviews', array(
				array( 'name' => 'Ольга', 'meta' => 'Лечение и протезирование', 'rating' => 5, 'text' => 'Прекрасная клиника и внимательные врачи. Всё объяснили, лечение прошло безболезненно, а цены приятно удивили.' ),
				array( 'name' => 'Марина', 'meta' => 'Детская стоматология', 'rating' => 5, 'text' => 'Приводим сюда всей семьёй. Врач нашёл подход к ребёнку — теперь дочка лечит зубы без слёз.' ),
				array( 'name' => 'Дмитрий', 'meta' => 'Имплантация', 'rating' => 5, 'text' => 'Поставил импланты — всё на высшем уровне. Профессионально, честная цена и отличный результат.' ),
			) );
			foreach ( array_slice( $reviews, 0, 3 ) as $r ) :
				$rating = (int) ( $r['rating'] ?: 5 );
				$initial = function_exists( 'mb_substr' ) ? mb_substr( $r['name'], 0, 1, 'UTF-8' ) : substr( $r['name'], 0, 1 );
				?>
				<article class="review">
					<div class="review__stars" aria-label="<?php echo esc_attr( $rating ); ?> из 5"><?php echo str_repeat( $star, $rating ); ?></div>
					<p class="review__text"><?php echo esc_html( $r['text'] ); ?></p>
					<div class="review__author">
						<span class="review__avatar"><?php echo esc_html( $initial ); ?></span>
						<div>
							<div class="review__name"><?php echo esc_html( $r['name'] ); ?></div>
							<div class="review__meta"><?php echo esc_html( $r['meta'] ); ?></div>
						</div>
					</div>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<?php /* ================= КОНТАКТЫ ================= */ ?>
<section class="section section--tint" id="kontakty">
	<div class="wrap">
		<div class="section-head">
			<span class="eyebrow"><?php echo esc_html( $F( 'contacts_eyebrow', 'Контакты' ) ); ?></span>
			<h2 class="section-title"><?php echo dobraya36_accent_last( $F( 'contacts_title', 'Три клиники в удобных районах' ), 1, 'hl' ); ?></h2>
		</div>
		<div class="contacts" data-stagger>
			<?php
			$phone_main = dobraya36_opt( 'phone', '+7 (473) 211-30-11' );
			$home_clinics = get_posts( array( 'post_type' => 'clinic', 'numberposts' => 3, 'orderby' => 'menu_order', 'order' => 'ASC' ) );
			foreach ( $home_clinics as $c ) :
				$addr = get_field( 'clinic_address', $c->ID );
				$ph   = get_field( 'clinic_phone', $c->ID ) ?: $phone_main;
				$hw   = get_field( 'clinic_hours_weekday', $c->ID );
				$he   = get_field( 'clinic_hours_weekend', $c->ID );
				$link = get_permalink( $c );
				?>
				<div class="contact">
					<h3 class="contact__title"><span class="pin"><?php echo dobraya36_icon( 'pin' ); ?></span><a href="<?php echo esc_url( $link ); ?>" style="color:inherit"><?php echo esc_html( $c->post_title ); ?></a></h3>
					<div class="contact__row"><?php echo dobraya36_icon( 'pin' ); ?><span><?php echo esc_html( $addr ); ?></span></div>
					<div class="contact__row"><?php echo dobraya36_icon( 'clock' ); ?><span><?php echo esc_html( trim( $hw . ' · ' . $he, ' ·' ) ); ?></span></div>
					<div class="contact__row"><?php echo dobraya36_icon( 'phone' ); ?><a href="tel:<?php echo esc_attr( dobraya36_tel( $ph ) ); ?>"><?php echo esc_html( $ph ); ?></a></div>
					<div class="contact__row" style="margin-top:.4rem"><a class="cat-card__more" href="#" data-booking data-clinic="<?php echo esc_attr( $c->post_title ); ?>"><?php esc_html_e( 'Записаться сюда', 'dobraya36' ); ?><?php echo $arw; ?></a></div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<?php /* ================= ЗАПИСЬ ================= */ ?>
<section class="section" id="zapis">
	<div class="wrap">
		<div class="booking__panel" data-anim>
			<div class="booking__info">
				<h2 class="booking__title"><?php echo esc_html( $F( 'zapis_title', 'Запишитесь на приём' ) ); ?></h2>
				<p class="booking__text"><?php echo esc_html( $F( 'zapis_text', 'Оставьте заявку — администратор перезвонит в течение 15 минут и подберёт удобное время.' ) ); ?></p>
				<ul class="booking__list">
					<?php
					$benefits = $F( 'zapis_benefits', array(
						array( 'text' => 'Бесплатная первичная консультация' ),
						array( 'text' => 'Честный план лечения и фиксированная цена' ),
						array( 'text' => 'Рассрочка 0% и гарантия 2 года' ),
					) );
					foreach ( $benefits as $b ) : ?>
						<li><?php echo $check; ?><?php echo esc_html( is_array( $b ) ? $b['text'] : $b ); ?></li>
					<?php endforeach; ?>
				</ul>
			</div>
			<div class="booking__form">
				<h3 class="booking__form-h"><?php echo esc_html( $F( 'zapis_form_title', 'Оставить заявку' ) ); ?></h3>
				<?php
				$shortcode = $F( 'zapis_form_shortcode', '' );
				echo $shortcode ? do_shortcode( $shortcode ) : '<p class="consent-note">Форма записи появится здесь после настройки Contact Form 7.</p>';
				?>
			</div>
		</div>
	</div>
</section>

<?php
get_footer();
