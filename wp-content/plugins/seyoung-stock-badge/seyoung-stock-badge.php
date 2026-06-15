<?php
/**
 * Plugin Name: Seyoung Stock Badge
 * Plugin URI: http://localhost/seyoung
 * Description: 우커머스 상품 목록 및 상세 페이지에 실시간 재고 상태에 따른 '품절 임박' 및 '일시 품절' 배지 노출 플러그인
 * Version: 1.0.0
 * Author: Honey Soft
 * Author URI: http://localhost/seyoung
 * Text Domain: seyoung-stock-badge
 * WC requires at least: 6.0
 * WC tested up to: 8.5
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

// 1. Enqueue Assets
add_action( 'wp_enqueue_scripts', 'seyoung_stock_badge_enqueue_assets' );
function seyoung_stock_badge_enqueue_assets() {
    wp_enqueue_style( 'seyoung-stock-badge-css', plugins_url( 'seyoung-stock-badge.css', __FILE__ ), array(), '1.0.0' );
}

// 2. Helper function to generate stock badge HTML
function seyoung_get_stock_badge_html( $product ) {
    if ( ! $product ) {
        return '';
    }

    $html = '';

    // If stock is managed at the product level
    if ( $product->managing_stock() ) {
        $stock_qty = $product->get_stock_quantity();
        
        if ( ! $product->is_in_stock() || $stock_qty <= 0 ) {
            $html = '<span class="sy-stock-badge out-of-stock">❌ 일시 품절</span>';
        } elseif ( $stock_qty > 0 && $stock_qty <= 10 ) {
            $html = '<span class="sy-stock-badge low-stock">🔥 품절 임박 (남은 수량: ' . esc_html( $stock_qty ) . '개)</span>';
        }
    } else {
        // If stock is NOT managed, check the general stock status
        if ( ! $product->is_in_stock() ) {
            $html = '<span class="sy-stock-badge out-of-stock">❌ 일시 품절</span>';
        }
    }

    return $html;
}

// 3. Hook into WooCommerce Shop Loop (Product List)
add_action( 'woocommerce_after_shop_loop_item_title', 'seyoung_stock_badge_shop_loop', 15 );
function seyoung_stock_badge_shop_loop() {
    global $product;
    if ( $product ) {
        $badge = seyoung_get_stock_badge_html( $product );
        if ( ! empty( $badge ) ) {
            echo '<div class="sy-stock-badge-loop-wrap">' . $badge . '</div>';
        }
    }
}

// 4. Hook into WooCommerce Single Product Summary Page
add_action( 'woocommerce_single_product_summary', 'seyoung_stock_badge_single_product', 15 );
function seyoung_stock_badge_single_product() {
    global $product;
    if ( $product ) {
        $badge = seyoung_get_stock_badge_html( $product );
        if ( ! empty( $badge ) ) {
            echo '<div class="sy-stock-badge-single-wrap" style="margin-bottom: 20px;">' . $badge . '</div>';
        }
    }
}
