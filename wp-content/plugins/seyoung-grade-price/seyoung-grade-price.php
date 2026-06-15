<?php
/**
 * Plugin Name: Seyoung Grade Price
 * Plugin URI: http://localhost/seyoung
 * Description: 시공기사(contractor) 등급 회원 전용 우대 단가 제공 플러그인
 * Version: 1.0.0
 * Author: Honey Soft
 * Author URI: http://localhost/seyoung
 * Text Domain: seyoung-grade-price
 * WC requires at least: 6.0
 * WC tested up to: 8.5
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

// 1. Add User Role (contractor) on activation
register_activation_hook( __FILE__, 'seyoung_grade_price_activate' );
function seyoung_grade_price_activate() {
    // Add "contractor" role if it does not exist
    if ( ! get_role( 'contractor' ) ) {
        add_role( 'contractor', '시공기사', array(
            'read' => true, // Allows reading comments, accessing front-end
        ) );
    }
}

// 2. Add Custom Field in WooCommerce product general settings tab
add_action( 'woocommerce_product_options_general_product_data', 'seyoung_grade_price_add_custom_fields' );
function seyoung_grade_price_add_custom_fields() {
    woocommerce_wp_text_input( array(
        'id'          => '_contractor_price',
        'label'       => __( '시공기사 우대가 (원)', 'seyoung-grade-price' ),
        'placeholder' => '예: 120000',
        'desc_tip'    => 'true',
        'description' => __( '시공기사(contractor) 회원에게 노출할 할인 가격을 입력하세요.', 'seyoung-grade-price' ),
        'type'        => 'number',
        'custom_attributes' => array(
            'step' => 'any',
            'min'  => '0'
        )
    ) );
}

// 3. Save Custom Field value
add_action( 'woocommerce_process_product_meta', 'seyoung_grade_price_save_custom_fields' );
function seyoung_grade_price_save_custom_fields( $post_id ) {
    $contractor_price = isset( $_POST['_contractor_price'] ) ? sanitize_text_field( $_POST['_contractor_price'] ) : '';
    update_post_meta( $post_id, '_contractor_price', $contractor_price );
}

// 4. Override price for contractor users
add_filter( 'woocommerce_product_get_price', 'seyoung_grade_price_filter_price', 99, 2 );
add_filter( 'woocommerce_product_variation_get_price', 'seyoung_grade_price_filter_price', 99, 2 );
function seyoung_grade_price_filter_price( $price, $product ) {
    if ( is_user_logged_in() ) {
        $user = wp_get_current_user();
        if ( in_array( 'contractor', (array) $user->roles ) ) {
            $contractor_price = get_post_meta( $product->get_id(), '_contractor_price', true );
            if ( ! empty( $contractor_price ) && floatval( $contractor_price ) > 0 ) {
                return $contractor_price;
            }
        }
    }
    return $price;
}

// 5. Display badge or notice on product pages for contractors
add_action( 'woocommerce_single_product_summary', 'seyoung_grade_price_display_badge', 9 );
function seyoung_grade_price_display_badge() {
    global $product;
    if ( ! $product ) {
        return;
    }

    if ( is_user_logged_in() ) {
        $user = wp_get_current_user();
        if ( in_array( 'contractor', (array) $user->roles ) ) {
            $contractor_price = get_post_meta( $product->get_id(), '_contractor_price', true );
            if ( ! empty( $contractor_price ) && floatval( $contractor_price ) > 0 ) {
                echo '<div style="background-color: #5D4037; color: #FFFFFF; font-size: 12px; font-weight: 700; padding: 5px 10px; border-radius: 4px; display: inline-block; margin-bottom: 15px;">🛡️ 시공기사 우대가 적용 상품</div>';
            }
        }
    }
}
