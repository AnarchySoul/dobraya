<?php
/**
 * Plugin Name: Добрая стоматология — первичная настройка
 * Description: Однократно создаёт главную страницу, статьи, форму записи, меню и наполняет ACF-поля. После выполнения самоотключается (флаг в опциях). Можно перезапустить: ?dobraya36_setup=rerun (для админа).
 * Version: 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'wp_loaded', 'dobraya36_run_setup', 20 );

/**
 * Безопасный поиск записи по заголовку (без устаревшей get_page_by_title).
 */
function dobraya36_find_by_title( $title, $post_type = 'page' ) {
	$q = new WP_Query( array(
		'post_type'              => $post_type,
		'title'                  => $title,
		'post_status'            => array( 'publish', 'draft', 'pending' ),
		'posts_per_page'         => 1,
		'no_found_rows'          => true,
		'ignore_sticky_posts'    => true,
		'update_post_meta_cache' => false,
		'update_post_term_cache' => false,
	) );
	return $q->have_posts() ? $q->posts[0] : null;
}

function dobraya36_run_setup() {

	$done_flag = 'dobraya36_setup_done_v1';

	$force = ( isset( $_GET['dobraya36_setup'] ) && 'rerun' === $_GET['dobraya36_setup'] && current_user_can( 'manage_options' ) );

	if ( get_option( $done_flag ) && ! $force ) {
		return;
	}

	// Нужны ACF; CF7 желателен, но не обязателен.
	if ( ! function_exists( 'update_field' ) ) {
		return;
	}

	// ------------------------------------------------------------------
	// 1. Страницы: Главная и Статьи
	// ------------------------------------------------------------------
	$home = get_page_by_path( 'glavnaya' );
	if ( ! $home ) {
		$home = dobraya36_find_by_title( 'Главная', 'page' );
	}
	if ( ! $home ) {
		$home_id = wp_insert_post( array(
			'post_type'   => 'page',
			'post_title'  => 'Главная',
			'post_name'   => 'glavnaya',
			'post_status' => 'publish',
		) );
	} else {
		$home_id = $home->ID;
	}

	$blog = get_page_by_path( 'stati' );
	if ( ! $blog ) {
		$blog = dobraya36_find_by_title( 'Статьи', 'page' );
	}
	if ( ! $blog ) {
		$blog_id = wp_insert_post( array(
			'post_type'   => 'page',
			'post_title'  => 'Статьи',
			'post_name'   => 'stati',
			'post_status' => 'publish',
		) );
	} else {
		$blog_id = $blog->ID;
	}

	update_option( 'show_on_front', 'page' );
	update_option( 'page_on_front', $home_id );
	update_option( 'page_for_posts', $blog_id );

	// Публикуем страницу политики конфиденциальности, если есть черновик.
	$privacy = get_page_by_path( 'privacy-policy' );
	if ( ! $privacy ) {
		$privacy = dobraya36_find_by_title( 'Политика конфиденциальности', 'page' );
	}
	if ( $privacy ) {
		if ( 'publish' !== $privacy->post_status ) {
			wp_update_post( array( 'ID' => $privacy->ID, 'post_status' => 'publish' ) );
		}
		update_option( 'wp_page_for_privacy_policy', $privacy->ID );
	}

	// Удаляем стандартную «Пример страницы».
	$sample = get_page_by_path( 'sample-page' );
	if ( ! $sample ) {
		$sample = dobraya36_find_by_title( 'Пример страницы', 'page' );
	}
	if ( ! $sample ) {
		$sample = dobraya36_find_by_title( 'Sample Page', 'page' );
	}
	if ( $sample && (int) $sample->ID !== (int) $home_id ) {
		wp_delete_post( $sample->ID, true );
	}
	// Удаляем стандартную запись «Привет, мир!».
	$hello = get_posts( array( 'name' => 'hello-world', 'post_type' => 'post', 'post_status' => 'any', 'numberposts' => 1 ) );
	if ( ! empty( $hello ) ) {
		wp_delete_post( $hello[0]->ID, true );
	}

	// ------------------------------------------------------------------
	// 2. Рубрика и статьи
	// ------------------------------------------------------------------
	$cat_id = 0;
	$term   = term_exists( 'Полезное', 'category' );
	if ( ! $term ) {
		$term = wp_insert_term( 'Полезное', 'category', array( 'slug' => 'poleznoe' ) );
	}
	if ( ! is_wp_error( $term ) && isset( $term['term_id'] ) ) {
		$cat_id = (int) $term['term_id'];
	}

	$articles = array(
		array(
			'title' => 'Чем опасен пульпит и почему его нельзя терпеть',
			'body'  => '<p>Пульпит — это воспаление сосудисто-нервного пучка внутри зуба. Чаще всего он становится следствием запущенного кариеса, когда инфекция проникает в глубокие ткани.</p><p>Главный симптом — сильная, пульсирующая боль, которая усиливается ночью и от температурных раздражителей. Без лечения воспаление переходит на корень зуба и окружающую кость, что грозит потерей зуба и даже общими осложнениями.</p><p>Современное лечение пульпита проходит под анестезией и абсолютно безболезненно. Врач очищает и пломбирует каналы, сохраняя ваш зуб. Чем раньше вы обратитесь — тем проще и дешевле лечение.</p>',
		),
		array(
			'title' => 'Что такое периодонтит и как его лечат',
			'body'  => '<p>Периодонтит — это воспаление тканей, которые удерживают зуб в кости. Он развивается, когда инфекция из корневых каналов выходит за пределы зуба.</p><p>Заболевание может протекать почти незаметно, а может сопровождаться болью при накусывании, отёком и повышением температуры. Опасность в том, что у корня формируется очаг инфекции — киста или гранулёма.</p><p>Лечение включает тщательную обработку каналов, противовоспалительную терапию и качественное пломбирование. В большинстве случаев зуб удаётся сохранить.</p>',
		),
		array(
			'title' => 'Кариес: причины, стадии и современное лечение',
			'body'  => '<p>Кариес — самое распространённое стоматологическое заболевание. Он начинается с белого пятна на эмали и постепенно разрушает твёрдые ткани зуба.</p><p>На ранней стадии кариес можно остановить без сверления — реминерализацией. Но если появилась полость, необходимо аккуратно удалить поражённые ткани и поставить пломбу.</p><p>Регулярные осмотры раз в полгода помогают заметить проблему в самом начале и обойтись минимальным вмешательством.</p>',
		),
		array(
			'title' => 'Гарантия на лечение: как это работает у нас',
			'body'  => '<p>Мы уверены в качестве своей работы, поэтому даём удвоенную гарантию — 2 года на все виды лечения и протезирования.</p><p>Гарантия означает, что при возникновении проблемы по нашей вине мы бесплатно всё исправим. Чтобы она действовала, достаточно приходить на профилактические осмотры и соблюдать рекомендации врача.</p><p>Мы фиксируем план лечения и стоимость заранее — никаких неожиданных доплат в процессе.</p>',
		),
		array(
			'title' => 'Профессиональная гигиена полости рта: зачем она нужна',
			'body'  => '<p>Даже при тщательной чистке дома на зубах со временем образуются налёт и зубной камень, которые невозможно убрать обычной щёткой.</p><p>Профессиональная гигиена включает ультразвуковое удаление камня, чистку Air Flow и полировку. Это профилактика кариеса и заболеваний дёсен, а также естественное осветление эмали.</p><p>Рекомендуем проходить процедуру раз в 6 месяцев — это заметно продлевает здоровье зубов.</p>',
		),
		array(
			'title' => 'Имплантация зубов: этапы и восстановление',
			'body'  => '<p>Имплантация — самый надёжный способ восстановить утраченный зуб. Титановый имплант заменяет корень, а сверху фиксируется коронка.</p><p>Лечение проходит в несколько этапов: диагностика и КТ, установка импланта, период приживления и протезирование. Современные системы приживаются в 98–99% случаев.</p><p>После установки важно соблюдать гигиену и рекомендации врача — тогда имплант прослужит десятилетиями.</p>',
		),
	);

	if ( false === get_option( 'dobraya36_articles_created' ) ) {
		$i = 0;
		foreach ( $articles as $a ) {
			$exists = dobraya36_find_by_title( $a['title'], 'post' );
			if ( $exists ) {
				continue;
			}
			$post_id = wp_insert_post( array(
				'post_type'    => 'post',
				'post_title'   => $a['title'],
				'post_content' => $a['body'],
				'post_status'  => 'publish',
				'post_excerpt' => wp_trim_words( wp_strip_all_tags( $a['body'] ), 24, '…' ),
				'post_date'    => date( 'Y-m-d H:i:s', strtotime( "-{$i} days" ) ),
			) );
			if ( $post_id && ! is_wp_error( $post_id ) && $cat_id ) {
				wp_set_post_categories( $post_id, array( $cat_id ) );
			}
			$i++;
		}
		update_option( 'dobraya36_articles_created', 1 );
	}

	// ------------------------------------------------------------------
	// 3. Форма записи (Contact Form 7)
	// ------------------------------------------------------------------
	$cf7_shortcode = '';
	if ( class_exists( 'WPCF7_ContactForm' ) ) {
		$form_title = 'Запись на приём';
		$existing   = dobraya36_find_by_title( $form_title, 'wpcf7_contact_form' );

		$form_body = "<div class=\"cf7-field\">[select* clinic \"Выберите клинику\" \"На Старых Большевиков, 2\" \"На Ленинском, 151\" \"На Тютчева, 99а\"]</div>\n"
			. "<div class=\"cf7-field\">[text* patient-name placeholder \"Ваше имя\"]</div>\n"
			. "<div class=\"cf7-field\">[tel* patient-phone placeholder \"Телефон\"]</div>\n"
			. "<div class=\"cf7-field\">[textarea patient-comment placeholder \"Комментарий (необязательно)\"]</div>\n"
			. "<div class=\"cf7-field\">[acceptance consent] Я согласен на обработку персональных данных [/acceptance]</div>\n"
			. "[submit \"Записаться на приём\"]";

		$mail_body = "Новая заявка с сайта «Добрая стоматология»\n\n"
			. "Имя: [patient-name]\n"
			. "Телефон: [patient-phone]\n"
			. "Клиника: [clinic]\n"
			. "Комментарий: [patient-comment]\n";

		$props = array(
			'form'     => $form_body,
			'mail'     => array(
				'active'             => true,
				'subject'            => 'Заявка на запись: [patient-name]',
				'sender'             => 'Добрая стоматология <wordpress@' . wp_parse_url( home_url(), PHP_URL_HOST ) . '>',
				'recipient'          => get_option( 'admin_email' ),
				'body'               => $mail_body,
				'additional_headers' => 'Reply-To: ' . get_option( 'admin_email' ),
				'attachments'        => '',
				'use_html'           => false,
				'exclude_blank'      => false,
			),
			'messages' => array(
				'mail_sent_ok' => 'Спасибо! Ваша заявка отправлена — администратор перезвонит в ближайшее время.',
			),
		);

		if ( $existing ) {
			$cf = WPCF7_ContactForm::get_instance( $existing->ID );
			$cf->set_properties( $props );
			$cf->set_title( $form_title );
			$cf_id = $cf->save();
			$cf_id = $existing->ID;
			$cf    = WPCF7_ContactForm::get_instance( $cf_id );
		} else {
			$cf = WPCF7_ContactForm::get_template( array( 'title' => $form_title ) );
			$cf->set_properties( $props );
			$cf_id = $cf->save();
			$cf    = WPCF7_ContactForm::get_instance( $cf_id );
		}

		if ( $cf ) {
			$hash = method_exists( $cf, 'hash' ) ? $cf->hash() : '';
			if ( $hash ) {
				$cf7_shortcode = sprintf( '[contact-form-7 id="%s" title="%s"]', esc_attr( $hash ), esc_attr( $form_title ) );
			} else {
				$cf7_shortcode = sprintf( '[contact-form-7 id="%d" title="%s"]', (int) $cf_id, esc_attr( $form_title ) );
			}
		}
	}

	// ------------------------------------------------------------------
	// 4. Наполнение ACF-полей главной страницы
	// ------------------------------------------------------------------
	$set = function ( $key, $value ) use ( $home_id ) {
		update_field( $key, $value, $home_id );
	};

	// -- Hero --
	$set( 'field_hero_eyebrow', 'Стоматология в Воронеже с 2013 года' );
	$set( 'field_hero_title', 'Заботливая стоматология для всей семьи' );
	$set( 'field_hero_title_accent', 'без страха и боли' );
	$set( 'field_hero_sub', 'Лечим, восстанавливаем и сохраняем здоровье зубов на современном оборудовании. Честные цены, рассрочка 0% и удвоенная гарантия на все виды работ.' );
	$set( 'field_hero_cta_primary', 'Записаться на приём' );
	$set( 'field_hero_cta_secondary', 'Услуги и цены' );
	$set( 'field_hero_note', 'Бесплатная консультация и план лечения' );
	$set( 'field_hero_stats', array(
		array( 'value' => '12 лет', 'label' => 'заботимся о ваших улыбках' ),
		array( 'value' => '3 клиники', 'label' => 'в удобных районах Воронежа' ),
		array( 'value' => '2 года', 'label' => 'гарантия на лечение' ),
	) );
	$set( 'field_offer_ribbon', 'Выгода 6 100 ₽' );
	$set( 'field_offer_label', 'Акция месяца · Имплантация' );
	$set( 'field_offer_title', 'Имплант OSSTEM под ключ' );
	$set( 'field_offer_price_new', '27 900' );
	$set( 'field_offer_price_old', '34 000 ₽' );
	$set( 'field_offer_note', 'Дополнительно <strong>−5%</strong> на терапевтическое лечение перед имплантацией.' );
	$set( 'field_offer_cta', 'Записаться на консультацию' );
	$set( 'field_hero_badge_title', 'Рассрочка 0%' );
	$set( 'field_hero_badge_text', 'без переплат и банка' );

	// -- Преимущества --
	$set( 'field_advantages', array(
		array( 'icon' => 'card', 'title' => 'Рассрочка 0%', 'text' => 'Оплата лечения частями без процентов, банка и справок о доходах.' ),
		array( 'icon' => 'heart', 'title' => 'Внимательные врачи', 'text' => 'Квалифицированная и позитивная команда, которая любит свою работу.' ),
		array( 'icon' => 'shield', 'title' => 'Гарантия 2 года', 'text' => 'Удвоенная гарантия на все виды выполненных работ.' ),
		array( 'icon' => 'gift', 'title' => 'Акции и бонусы', 'text' => 'Выгодные предложения и приятные подарки каждый месяц.' ),
	) );

	// -- Услуги --
	$set( 'field_services_eyebrow', 'Направления' );
	$set( 'field_services_title', 'Всё для здоровья ваших зубов' );
	$set( 'field_services_sub', 'Полный спектр стоматологических услуг для взрослых и детей — от профилактики до сложной имплантации.' );
	$set( 'field_services', array(
		array( 'icon' => 'tooth', 'title' => 'Лечение зубов', 'text' => 'Кариес, пульпит и периодонтит под местной анестезией.', 'link' => '' ),
		array( 'icon' => 'crown', 'title' => 'Протезирование', 'text' => 'Коронки, виниры и мосты, которые не отличить от своих.', 'link' => '' ),
		array( 'icon' => 'implant', 'title' => 'Имплантология', 'text' => 'Восстановление зубов имплантами премиум-систем.', 'link' => '' ),
		array( 'icon' => 'braces', 'title' => 'Ортодонтия', 'text' => 'Брекеты и элайнеры для ровной и красивой улыбки.', 'link' => '' ),
		array( 'icon' => 'heart', 'title' => 'Пародонтология', 'text' => 'Лечение дёсен и профилактика их заболеваний.', 'link' => '' ),
		array( 'icon' => 'scalpel', 'title' => 'Хирургия', 'text' => 'Бережное удаление зубов любой сложности.', 'link' => '' ),
		array( 'icon' => 'sparkle', 'title' => 'Эстетика', 'text' => 'Отбеливание и художественная реставрация зубов.', 'link' => '' ),
		array( 'icon' => 'baby', 'title' => 'Для беременных', 'text' => 'Безопасное лечение с заботой о будущей маме.', 'link' => '' ),
	) );

	// -- Технологии --
	$set( 'field_tech_eyebrow', 'Технологии и безопасность' );
	$set( 'field_tech_title', 'Современно, безопасно, комфортно' );
	$set( 'field_tech_sub', 'Мы используем проверенные протоколы и материалы, чтобы лечение проходило спокойно и без осложнений.' );
	$set( 'field_tech', array(
		array( 'icon' => 'spark', 'title' => 'Plasmolifting', 'text' => 'Плазмотерапия ускоряет заживление тканей и укрепляет дёсны собственной плазмой пациента.' ),
		array( 'icon' => 'sparkle', 'title' => 'Отбеливание', 'text' => 'Безопасное осветление эмали на несколько тонов за один визит без вреда для зубов.' ),
		array( 'icon' => 'shield', 'title' => 'Стерильность', 'text' => 'Коффердам и свинцовый фартук, одноразовые инструменты и полная стерилизация.' ),
	) );

	// -- Акции --
	$set( 'field_promos_eyebrow', 'Выгодно' );
	$set( 'field_promos_title', 'Акции этого месяца' );
	$set( 'field_promos_sub', 'Качественная стоматология может быть доступной. Успейте воспользоваться выгодными предложениями.' );
	$set( 'field_promos', array(
		array( 'style' => 'accent', 'badge' => 'Имплантация', 'title' => 'Имплант OSSTEM под ключ', 'text' => 'Надёжная система с пожизненной гарантией на имплант.', 'price_new' => '27 900', 'price_old' => '34 000', 'cta' => 'Записаться' ),
		array( 'style' => 'default', 'badge' => 'Протезирование', 'title' => 'Коронка из диоксида циркония', 'text' => 'Прочная и эстетичная коронка, неотличимая от собственного зуба.', 'price_new' => '15 000', 'price_old' => '', 'cta' => 'Записаться' ),
		array( 'style' => 'warm', 'badge' => 'Подарок', 'title' => 'Скидка в честь дня рождения', 'text' => 'Дарим приятную скидку на лечение в течение недели до и после праздника.', 'price_new' => '', 'price_old' => '', 'cta' => 'Узнать условия' ),
	) );

	// -- О клинике --
	$set( 'field_about_eyebrow', 'О клинике' );
	$set( 'field_about_title', 'Добрая стоматология — это про заботу' );
	$set( 'field_about_text', '<p>Мы работаем с 2013 года и всё это время остаёмся верны главному принципу — относиться к каждому пациенту так, как хотели бы, чтобы относились к нам самим.</p><p>Наша команда вежливая, отзывчивая и заботливая. Мы находим индивидуальный подход к каждому: помогаем справиться со страхом, честно объясняем план лечения и делаем всё, чтобы визит к стоматологу был спокойным.</p>' );
	$set( 'field_director_name', 'Беликова Юлия Константиновна' );
	$set( 'field_director_role', 'Главный врач и основатель клиники' );
	$set( 'field_about_stats', array(
		array( 'value' => '2013', 'label' => 'год основания' ),
		array( 'value' => '3', 'label' => 'клиники в городе' ),
		array( 'value' => '10 000+', 'label' => 'счастливых пациентов' ),
	) );
	$set( 'field_about_hours', array(
		array( 'days' => 'Пн – Пт', 'time' => '9:00 – 20:00' ),
		array( 'days' => 'Сб – Вс', 'time' => '10:00 – 16:00' ),
	) );

	// -- Статьи --
	$set( 'field_articles_eyebrow', 'Полезное' );
	$set( 'field_articles_title', 'Статьи о здоровье зубов' );
	$set( 'field_articles_sub', 'Простым языком рассказываем о лечении, профилактике и уходе за полостью рта.' );
	$set( 'field_articles_count', 3 );

	// -- Отзывы --
	$set( 'field_reviews_eyebrow', 'Отзывы' );
	$set( 'field_reviews_title', 'Что говорят наши пациенты' );
	$set( 'field_reviews', array(
		array( 'name' => 'Ольга', 'meta' => 'Лечение и протезирование', 'rating' => 5, 'text' => 'Прекрасная клиника и очень внимательные врачи. Всё объяснили, лечение прошло безболезненно, а цены приятно удивили.' ),
		array( 'name' => 'Марина', 'meta' => 'Детская стоматология', 'rating' => 5, 'text' => 'Приводим сюда всей семьёй. Врач нашёл подход к ребёнку — теперь дочка ходит лечить зубы без слёз и страха.' ),
		array( 'name' => 'Дмитрий', 'meta' => 'Имплантация', 'rating' => 5, 'text' => 'Поставил импланты — всё на высшем уровне. Профессиональный доктор, честная цена и отличный результат. Рекомендую!' ),
	) );

	// -- Контакты --
	$phone_main = '+7 (473) 211-30-11';
	$set( 'field_contacts_eyebrow', 'Контакты' );
	$set( 'field_contacts_title', 'Три клиники в удобных районах' );
	$set( 'field_contacts_sub', 'Выберите ближайшую клинику — мы ждём вас в удобное время.' );
	$set( 'field_contacts', array(
		array( 'title' => 'На Старых Большевиков', 'address' => 'ул. Старых Большевиков, д. 2', 'hours' => 'Пн–Пт 9:00–20:00 · Сб–Вс 10:00–16:00', 'phone' => $phone_main ),
		array( 'title' => 'На Ленинском', 'address' => 'Ленинский пр., д. 151', 'hours' => 'Пн–Пт 9:00–20:00 · Сб–Вс 10:00–16:00', 'phone' => $phone_main ),
		array( 'title' => 'На Тютчева', 'address' => 'ул. Ф. Тютчева, д. 99а', 'hours' => 'Пн–Пт 9:00–20:00 · Сб–Вс 10:00–16:00', 'phone' => $phone_main ),
	) );

	// -- Запись --
	$set( 'field_zapis_title', 'Запишитесь на приём' );
	$set( 'field_zapis_text', 'Оставьте заявку — администратор перезвонит в течение 15 минут, подберёт удобное время и ответит на все вопросы.' );
	$set( 'field_zapis_benefits', array(
		array( 'text' => 'Бесплатная первичная консультация' ),
		array( 'text' => 'Честный план лечения и фиксированная цена' ),
		array( 'text' => 'Рассрочка 0% и гарантия 2 года' ),
	) );
	$set( 'field_zapis_form_title', 'Оставить заявку' );
	if ( $cf7_shortcode ) {
		$set( 'field_zapis_form_shortcode', $cf7_shortcode );
	}

	// ------------------------------------------------------------------
	// 5. Настройки сайта (ACF Options)
	// ------------------------------------------------------------------
	update_field( 'field_phone', $phone_main, 'option' );
	update_field( 'field_email', 'info@dobraya36.ru', 'option' );
	update_field( 'field_vk_url', 'https://vk.com/dobraya36', 'option' );
	update_field( 'field_hours_short', 'Пн–Пт 9:00–20:00 · Сб–Вс 10:00–16:00', 'option' );
	update_field( 'field_topbar_promo_1', 'Рассрочка 0%', 'option' );
	update_field( 'field_topbar_promo_2', 'Удвоенная гарантия', 'option' );
	update_field( 'field_branches', array(
		array( 'address' => 'ул. Старых Большевиков, 2' ),
		array( 'address' => 'Ленинский пр., 151' ),
		array( 'address' => 'ул. Ф. Тютчева, 99а' ),
	), 'option' );
	update_field( 'field_copyright', 'Беликова Ю.К., 2013–' . date( 'Y' ), 'option' );
	update_field( 'field_disclaimer', 'Имеются противопоказания. Необходима консультация специалиста.', 'option' );

	// ------------------------------------------------------------------
	// 6. Меню
	// ------------------------------------------------------------------
	dobraya36_build_menu( $blog_id, isset( $privacy ) ? $privacy : null );

	// ------------------------------------------------------------------
	// Готово.
	// ------------------------------------------------------------------
	update_option( $done_flag, time() );
}

/**
 * Создаёт главное и «подвальное» меню и привязывает к локациям темы.
 */
function dobraya36_build_menu( $blog_id, $privacy ) {
	$home = home_url( '/' );

	// --- Главное меню ---
	$menu_name = 'Главное меню';
	$menu      = wp_get_nav_menu_object( $menu_name );
	if ( ! $menu ) {
		$menu_id = wp_create_nav_menu( $menu_name );
	} else {
		$menu_id = $menu->term_id;
		// Очистим, чтобы не дублировать при повторном запуске.
		$items = wp_get_nav_menu_items( $menu_id );
		if ( $items ) {
			foreach ( $items as $it ) {
				wp_delete_post( $it->ID, true );
			}
		}
	}

	if ( ! is_wp_error( $menu_id ) ) {
		$primary_items = array(
			array( 'Услуги', $home . '#uslugi' ),
			array( 'Акции', $home . '#akcii' ),
			array( 'О клинике', $home . '#o-nas' ),
			array( 'Статьи', get_permalink( $blog_id ) ),
			array( 'Отзывы', $home . '#otzyvy' ),
			array( 'Контакты', $home . '#kontakty' ),
		);
		foreach ( $primary_items as $it ) {
			wp_update_nav_menu_item( $menu_id, 0, array(
				'menu-item-title'  => $it[0],
				'menu-item-url'    => $it[1],
				'menu-item-status' => 'publish',
			) );
		}
	}

	// --- Меню в подвале ---
	$footer_name = 'Меню в подвале';
	$fmenu       = wp_get_nav_menu_object( $footer_name );
	if ( ! $fmenu ) {
		$footer_id = wp_create_nav_menu( $footer_name );
	} else {
		$footer_id = $fmenu->term_id;
		$items     = wp_get_nav_menu_items( $footer_id );
		if ( $items ) {
			foreach ( $items as $it ) {
				wp_delete_post( $it->ID, true );
			}
		}
	}
	if ( ! is_wp_error( $footer_id ) ) {
		wp_update_nav_menu_item( $footer_id, 0, array( 'menu-item-title' => 'Услуги', 'menu-item-url' => $home . '#uslugi', 'menu-item-status' => 'publish' ) );
		wp_update_nav_menu_item( $footer_id, 0, array( 'menu-item-title' => 'Статьи', 'menu-item-url' => get_permalink( $blog_id ), 'menu-item-status' => 'publish' ) );
		wp_update_nav_menu_item( $footer_id, 0, array( 'menu-item-title' => 'Контакты', 'menu-item-url' => $home . '#kontakty', 'menu-item-status' => 'publish' ) );
		if ( $privacy ) {
			wp_update_nav_menu_item( $footer_id, 0, array( 'menu-item-title' => 'Политика конфиденциальности', 'menu-item-object-id' => $privacy->ID, 'menu-item-object' => 'page', 'menu-item-type' => 'post_type', 'menu-item-status' => 'publish' ) );
		}
	}

	// --- Привязка к локациям ---
	$locations = get_theme_mod( 'nav_menu_locations' );
	if ( ! is_array( $locations ) ) {
		$locations = array();
	}
	if ( ! is_wp_error( $menu_id ) ) {
		$locations['primary'] = (int) $menu_id;
	}
	if ( ! is_wp_error( $footer_id ) ) {
		$locations['footer'] = (int) $footer_id;
	}
	set_theme_mod( 'nav_menu_locations', $locations );
}
