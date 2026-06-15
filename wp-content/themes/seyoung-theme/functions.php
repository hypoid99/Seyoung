<?php
/**
 * Seyoung Theme functions and definitions
 *
 * @package Seyoung
 */

/**
 * Enqueue parent and child theme styles.
 */
function seyoung_theme_enqueue_styles() {
    // 부모 테마(Astra)의 스타일시트를 먼저 로드합니다.
    wp_enqueue_style( 'astra-parent-style', get_template_directory_uri() . '/style.css' );

    // 자식 테마의 스타일시트를 부모 테마 스타일 뒤에 로드합니다.
    wp_enqueue_style( 'seyoung-child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array( 'astra-parent-style' ),
        wp_get_theme()->get( 'Version' )
    );
}
add_action( 'wp_enqueue_scripts', 'seyoung_theme_enqueue_styles' );
