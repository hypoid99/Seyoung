<?php
/**
 * Plugin Name: Seyoung Tile Calculator
 * Plugin URI: http://localhost/seyoung
 * Description: 쇼핑몰 상품 상세 페이지에 타일 소요 면적 및 필요 박스 계산기 노출 플러그인
 * Version: 1.0.0
 * Author: Honey Soft
 * Author URI: http://localhost/seyoung
 * Text Domain: seyoung-tile-calculator
 * WC requires at least: 6.0
 * WC tested up to: 8.5
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

// 1. Enqueue script and styles
add_action( 'wp_enqueue_scripts', 'seyoung_tile_calc_register_assets' );
function seyoung_tile_calc_register_assets() {
    wp_register_script( 'seyoung-tile-calculator-js', plugins_url( 'seyoung-tile-calculator.js', __FILE__ ), array('jquery'), '1.0.0', true );
    wp_register_style( 'seyoung-tile-calculator-css', plugins_url( 'seyoung-tile-calculator.css', __FILE__ ), array(), '1.0.0' );
}

// 2. Display Custom Field on WooCommerce admin product general settings
add_action( 'woocommerce_product_options_general_product_data', 'seyoung_tile_calc_add_custom_fields' );
function seyoung_tile_calc_add_custom_fields() {
    woocommerce_wp_text_input( array(
        'id'          => '_tile_area_per_box',
        'label'       => __( '박스당 면적 (㎡)', 'seyoung-tile-calculator' ),
        'placeholder' => '예: 1.44',
        'desc_tip'    => 'true',
        'description' => __( '타일 1박스당 시공 가능한 면적(㎡)을 입력하세요.', 'seyoung-tile-calculator' ),
        'type'        => 'number',
        'custom_attributes' => array(
            'step' => 'any',
            'min'  => '0'
        )
    ) );
}

// 3. Save Custom Field value
add_action( 'woocommerce_process_product_meta', 'seyoung_tile_calc_save_custom_fields' );
function seyoung_tile_calc_save_custom_fields( $post_id ) {
    $tile_area = isset( $_POST['_tile_area_per_box'] ) ? sanitize_text_field( $_POST['_tile_area_per_box'] ) : '';
    update_post_meta( $post_id, '_tile_area_per_box', $tile_area );
}

// 4. Output calculator block on product page
add_action( 'woocommerce_before_add_to_cart_form', 'seyoung_tile_calc_display_calculator' );
function seyoung_tile_calc_display_calculator() {
    global $product;
    if ( ! $product ) {
        return;
    }
    
    $tile_area = get_post_meta( $product->get_id(), '_tile_area_per_box', true );
    if ( empty( $tile_area ) || floatval( $tile_area ) <= 0 ) {
        return; // Only display calculator if box area is set and greater than zero
    }

    
    // Load script and style
    wp_enqueue_script( 'seyoung-tile-calculator-js' );
    wp_enqueue_style( 'seyoung-tile-calculator-css' );

    $product_price = $product->get_price();
    ?>
    <div class="seyoung-tile-calculator-wrap" data-area-per-box="<?php echo esc_attr($tile_area); ?>" data-product-price="<?php echo esc_attr($product_price); ?>">
        <h4 class="tile-calc-title">📐 타일 필요 수량 계산기</h4>
        <p class="tile-calc-subtitle">시공할 공간의 가로/세로 길이를 입력하세요. (1박스당 면적: <?php echo esc_html($tile_area); ?>㎡)</p>
        
        <div class="tile-calc-inputs">
            <div class="tile-calc-field">
                <label for="tile-calc-width">가로 길이 (m)</label>
                <input type="number" id="tile-calc-width" step="0.1" min="0" placeholder="예: 3.2">
            </div>
            <div class="tile-calc-field">
                <label for="tile-calc-height">세로 길이 (m)</label>
                <input type="number" id="tile-calc-height" step="0.1" min="0" placeholder="예: 2.4">
            </div>
            <div class="tile-calc-field">
                <label for="tile-calc-loss">여유분 (로스율)</label>
                <select id="tile-calc-loss">
                    <option value="5">5% (일반)</option>
                    <option value="10" selected>10% (추천 - 마감 절단용)</option>
                    <option value="15">15% (디자인/대형 타일)</option>
                </select>
            </div>
        </div>
        
        <div class="tile-calc-results">
            <div class="tile-calc-res-item">
                <span class="res-label">필요 면적</span>
                <span class="res-value"><strong id="tile-calc-res-area">0.00</strong> ㎡</span>
            </div>
            <div class="tile-calc-res-item">
                <span class="res-label">필요 수량</span>
                <span class="res-value"><strong id="tile-calc-res-boxes">0</strong> 박스</span>
            </div>
            <div class="tile-calc-res-item">
                <span class="res-label">예상 금액</span>
                <span class="res-value highlight"><strong id="tile-calc-res-price">0</strong> 원</span>
            </div>
        </div>
        
        <p class="tile-calc-note">* 입력된 값에 따른 박스 수량이 자동으로 아래 수량(Quantity)에 반영됩니다.</p>
    </div>
    <?php
}
