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

/**
 * Render Royal&Co style top utility bar at the very top of the page.
 */
function seyoung_theme_top_utility_bar() {
    // Check if WooCommerce is active
    if ( ! class_exists( 'WooCommerce' ) ) {
        return;
    }
    
    $my_account_link = get_permalink( get_option('woocommerce_myaccount_page_id') );
    $cart_link = wc_get_cart_url();
    $cart_count = WC()->cart->get_cart_contents_count();
    $qna_link = home_url('/qna-board/');
    
    // Check user login status
    if ( is_user_logged_in() ) {
        $login_logout_link = wp_logout_url( home_url() );
        $login_logout_text = 'LOGOUT';
        $join_html = '';
    } else {
        $login_logout_link = $my_account_link;
        $login_logout_text = 'LOGIN';
        $join_html = '<a href="' . esc_url( $my_account_link ) . '" class="sy-utility-link">JOIN</a>';
    }
    
    $orders_link = wc_get_endpoint_url( 'orders', '', $my_account_link );
    $search_link = get_permalink( wc_get_page_id( 'shop' ) );
    ?>
    <div class="sy-top-utility-bar">
        <div class="sy-top-utility-container">
            <div class="sy-utility-links">
                <a href="<?php echo esc_url( $login_logout_link ); ?>" class="sy-utility-link"><?php echo esc_html( $login_logout_text ); ?></a>
                <?php echo $join_html; ?>
                <a href="<?php echo esc_url( $my_account_link ); ?>" class="sy-utility-link">MY PAGE</a>
                <a href="<?php echo esc_url( $cart_link ); ?>" class="sy-utility-link sy-utility-cart">
                    CART <span class="sy-cart-badge"><?php echo esc_html( $cart_count ); ?></span>
                </a>
                <a href="<?php echo esc_url( $orders_link ); ?>" class="sy-utility-link">주문배송 조회</a>
                <a href="<?php echo esc_url( $qna_link ); ?>" class="sy-utility-link">고객센터</a>
                <a href="<?php echo esc_url( $search_link ); ?>" class="sy-utility-search-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="sy-utility-search-icon">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </a>
            </div>
        </div>
    </div>
    <?php
}
add_action( 'astra_header_before', 'seyoung_theme_top_utility_bar' );

/**
 * Update WooCommerce cart count badge via AJAX.
 */
function seyoung_theme_cart_link_fragment( $fragments ) {
    ob_start();
    $cart_count = WC()->cart->get_cart_contents_count();
    ?>
    <span class="sy-cart-badge"><?php echo esc_html( $cart_count ); ?></span>
    <?php
    $fragments['span.sy-cart-badge'] = ob_get_clean();
    return $fragments;
}
add_filter( 'woocommerce_add_to_cart_fragments', 'seyoung_theme_cart_link_fragment' );
