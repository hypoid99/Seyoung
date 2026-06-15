<?php
/**
 * Plugin Name: Seyoung Construction Gallery
 * Plugin URI: http://localhost/seyoung
 * Description: 세영건재 시공 사례 갤러리 플러그인 (CPT + Before/After 사진 및 관련 우커머스 상품 태깅 기능 내장)
 * Version: 1.0.0
 * Author: Honey Soft
 * Author URI: http://localhost/seyoung
 * Text Domain: seyoung-gallery
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

// 1. Register Custom Post Type (seyoung_gallery)
add_action( 'init', 'seyoung_gallery_register_post_type' );
function seyoung_gallery_register_post_type() {
    $labels = array(
        'name'               => '시공 사례',
        'singular_name'      => '시공 사례',
        'menu_name'          => '시공 사례',
        'add_new'            => '새 시공 추가',
        'add_new_item'       => '새 시공 등록',
        'edit_item'          => '시공 수정',
        'new_item'           => '새 시공',
        'view_item'          => '시공 보기',
        'search_items'       => '시공 사례 검색',
        'not_found'          => '등록된 시공 사례가 없습니다.',
        'not_found_in_trash' => '휴지통에 시공 사례가 없습니다.',
    );

    $args = array(
        'labels'              => $labels,
        'public'              => true,
        'has_archive'         => false,
        'publicly_queryable'  => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'query_var'           => true,
        'rewrite'             => array( 'slug' => 'gallery' ),
        'capability_type'     => 'post',
        'hierarchical'        => false,
        'menu_position'       => 27,
        'menu_icon'           => 'dashicons-format-image',
        'supports'            => array( 'title', 'editor', 'thumbnail' ),
    );

    register_post_type( 'seyoung_gallery', $args );
}

// 2. Load custom single template for seyoung_gallery
add_filter( 'single_template', 'seyoung_gallery_load_single_template' );
function seyoung_gallery_load_single_template( $single_template ) {
    global $post;
    if ( $post && $post->post_type === 'seyoung_gallery' ) {
        $file = plugin_dir_path( __FILE__ ) . 'single-seyoung_gallery.php';
        if ( file_exists( $file ) ) {
            return $file;
        }
    }
    return $single_template;
}

// 3. Enqueue Styles
add_action( 'wp_enqueue_scripts', 'seyoung_gallery_enqueue_assets' );
function seyoung_gallery_enqueue_assets() {
    wp_enqueue_style( 'seyoung-gallery-css', plugins_url( 'seyoung-gallery.css', __FILE__ ), array(), '1.0.0' );
}

// 4. Add custom metadata boxes for Before photo and Tagged Products
add_action( 'add_meta_boxes', 'seyoung_gallery_add_meta_boxes' );
function seyoung_gallery_add_meta_boxes() {
    add_meta_box(
        'seyoung_gallery_meta',
        '시공 사례 상세 정보 (비포 사진 및 관련 상품 연동)',
        'seyoung_gallery_meta_box_callback',
        'seyoung_gallery',
        'normal',
        'high'
    );
}

function seyoung_gallery_meta_box_callback( $post ) {
    wp_nonce_field( 'seyoung_gallery_save_meta', 'seyoung_gallery_meta_nonce' );

    // Get current values
    $before_image = get_post_meta( $post->ID, '_gallery_before_image', true );
    $tagged_products = get_post_meta( $post->ID, '_gallery_product_ids', true );
    if ( ! is_array( $tagged_products ) ) {
        $tagged_products = array();
    }

    ?>
    <p>
        <label for="gallery_before_image"><strong>시공 전(Before) 이미지 URL:</strong></label><br>
        <input type="text" name="gallery_before_image" id="gallery_before_image" value="<?php echo esc_attr( $before_image ); ?>" style="width:100%; padding: 8px; border:1px solid #ddd; border-radius:4px;"><br>
        <span class="description">시공 전 사진의 이미지 웹 주소를 복사해 입력하세요. (우커머스 '대표 이미지'가 시공 후(After) 사진으로 사용됩니다.)</span>
    </p>
    
    <p>
        <strong>시공에 사용된 세영건재 제품 태그 (관련 상품):</strong><br>
        <div style="max-height: 200px; overflow-y: auto; border: 1px solid #E5E0D8; padding: 12px; background-color: #FAF8F5; margin-top: 5px; border-radius: 4px;">
            <?php
            if ( class_exists( 'WooCommerce' ) ) {
                $products = wc_get_products( array( 'limit' => -1, 'status' => 'publish' ) );
                if ( ! empty( $products ) ) {
                    foreach ( $products as $product ) {
                        $checked = in_array( $product->get_id(), $tagged_products ) ? 'checked' : '';
                        echo '<label style="display:block; margin-bottom:8px; cursor:pointer; font-size:13px; color:#3E2723;">';
                        echo '<input type="checkbox" name="gallery_product_ids[]" value="' . esc_attr( $product->get_id() ) . '" ' . $checked . ' style="margin-right:8px;"> ';
                        echo esc_html( $product->get_name() ) . ' (' . number_format( $product->get_price() ) . '원)';
                        echo '</label>';
                    }
                } else {
                    echo '<p style="font-size:13px; color:#8D6E63; margin:0;">등록된 우커머스 상품이 없습니다.</p>';
                }
            } else {
                echo '<p style="font-size:13px; color:red; margin:0;">우커머스 플러그인이 비활성화 상태입니다.</p>';
            }
            ?>
        </div>
        <span class="description">이 시공 사례 상세 페이지 하단에 노출하고 구매 링크를 연동할 상품들을 선택해 주세요.</span>
    </p>
    <?php
}

// Save Custom Meta
add_action( 'save_post', 'seyoung_gallery_save_meta' );
function seyoung_gallery_save_meta( $post_id ) {
    if ( ! isset( $_POST['seyoung_gallery_meta_nonce'] ) || ! wp_verify_nonce( $_POST['seyoung_gallery_meta_nonce'], 'seyoung_gallery_save_meta' ) ) {
        return;
    }
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    if ( isset( $_POST['post_type'] ) && 'seyoung_gallery' === $_POST['post_type'] ) {
        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }
    }

    if ( isset( $_POST['gallery_before_image'] ) ) {
        update_post_meta( $post_id, '_gallery_before_image', sanitize_text_field( $_POST['gallery_before_image'] ) );
    }

    $product_ids = isset( $_POST['gallery_product_ids'] ) ? array_map( 'intval', $_POST['gallery_product_ids'] ) : array();
    update_post_meta( $post_id, '_gallery_product_ids', $product_ids );
}

// 5. Shortcode [seyoung_gallery_list]
add_shortcode( 'seyoung_gallery_list', 'seyoung_gallery_list_renderer' );
function seyoung_gallery_list_renderer() {
    ob_start();
    $args = array(
        'post_type'      => 'seyoung_gallery',
        'posts_per_page' => 12,
        'post_status'    => 'publish',
    );
    $query = new WP_Query( $args );
    ?>
    <div class="sy-gallery-wrap">
        <div class="sy-gallery-header">
            <h3>🏡 시공 사례 갤러리</h3>
            <p>세영건재의 친환경 프리미엄 제품들로 새롭게 설계된 욕실 및 시공 사례를 확인하세요.</p>
        </div>
        <div class="sy-gallery-grid">
            <?php
            if ( $query->have_posts() ) {
                while ( $query->have_posts() ) {
                    $query->the_post();
                    $after_image = get_the_post_thumbnail_url( get_the_ID(), 'medium' ) ?: wc_placeholder_img_src('medium');
                    ?>
                    <a href="<?php the_permalink(); ?>" class="sy-gallery-card">
                        <div class="sy-gallery-img" style="background-image: url('<?php echo esc_url($after_image); ?>');"></div>
                        <div class="sy-gallery-info">
                            <h4 class="sy-gallery-title"><?php the_title(); ?></h4>
                            <span class="sy-gallery-link">시공 자세히 보기 &rarr;</span>
                        </div>
                    </a>
                    <?php
                }
                wp_reset_postdata();
            } else {
                echo '<p class="sy-no-gallery" style="text-align:center; width:100%; grid-column:1/-1; padding: 50px 0; color:#8D6E63;">등록된 시공 사례가 없습니다.</p>';
            }
            ?>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
