<?php
/**
 * Форма поиска.
 *
 * @package dobraya36
 */
?>
<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label class="screen-reader-text" for="s"><?php esc_html_e( 'Поиск:', 'dobraya36' ); ?></label>
	<input type="search" id="s" class="search-form__field" placeholder="<?php esc_attr_e( 'Поиск по сайту…', 'dobraya36' ); ?>" value="<?php echo get_search_query(); ?>" name="s" />
	<button type="submit" class="search-form__submit btn btn--primary btn--sm"><?php esc_html_e( 'Найти', 'dobraya36' ); ?></button>
</form>
