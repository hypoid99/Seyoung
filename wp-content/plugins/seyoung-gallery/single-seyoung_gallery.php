<?php
/**
 * The template for displaying a single seyoung_gallery post.
 *
 * @package Seyoung
 */

get_header();

global $post;
$post_id = get_the_ID();

// Retrieve before/after photos
$before_image = get_post_meta( $post_id, '_gallery_before_image', true );
$after_image = get_the_post_thumbnail_url( $post_id, 'large' ) ?: wc_placeholder_img_src('large');

// Retrieve tagged products
$tagged_product_ids = get_post_meta( $post_id, '_gallery_product_ids', true );
if ( ! is_array( $tagged_product_ids ) ) {
    $tagged_product_ids = array();
}
?>

<div class="sy-gallery-detail-wrap">
    
    <!-- 1. Header Section -->
    <div class="sy-gallery-detail-header">
        <h2 class="sy-gallery-detail-title"><?php the_title(); ?></h2>
        <div class="sy-gallery-detail-meta">
            <span>등록일: <strong><?php echo get_the_date( 'Y-m-d' ); ?></strong></span>
        </div>
    </div>

    <!-- 2. Before / After Photos -->
    <?php if ( ! empty( $before_image ) ) : ?>
        <div class="sy-gallery-comparison">
            <!-- Before Image -->
            <div class="sy-gallery-photo-box">
                <span class="sy-gallery-badge before">BEFORE</span>
                <img src="<?php echo esc_url( $before_image ); ?>" alt="시공 전 사진">
            </div>
            <!-- After Image -->
            <div class="sy-gallery-photo-box">
                <span class="sy-gallery-badge after">AFTER</span>
                <img src="<?php echo esc_url( $after_image ); ?>" alt="시공 후 사진">
            </div>
        </div>
    <?php else : ?>
        <!-- Full width After Image if Before is not set -->
        <div class="sy-gallery-single-photo" style="margin-bottom: 40px; border: 1px solid #E5E0D8; border-radius: 6px; overflow: hidden;">
            <img src="<?php echo esc_url( $after_image ); ?>" alt="시공 완료 사진" style="width: 100%; height: 450px; object-fit: cover; display: block;">
        </div>
    <?php endif; ?>

    <!-- 3. Construction Description -->
    <div class="sy-gallery-detail-desc">
        <?php the_content(); ?>
    </div>

    <!-- 4. Tagged Products Section -->
    <?php if ( ! empty( $tagged_product_ids ) && class_exists( 'WooCommerce' ) ) : ?>
        <div class="sy-gallery-tagged-section">
            <h3 class="sy-gallery-tagged-title">🧱 이 시공에 사용된 세영건재 제품</h3>
            <div class="sy-gallery-products-grid">
                <?php
                foreach ( $tagged_product_ids as $prod_id ) {
                    $product = wc_get_product( $prod_id );
                    if ( ! $product ) {
                        continue;
                    }

                    $prod_name = $product->get_name();
                    $prod_price = $product->get_price_html();
                    $prod_image = get_the_post_thumbnail_url( $prod_id, 'medium' ) ?: wc_placeholder_img_src('medium');
                    $prod_url = get_permalink( $prod_id );
                    ?>
                    <div class="sy-gallery-product-card">
                        <a href="<?php echo esc_url( $prod_url ); ?>" style="text-decoration: none;">
                            <div class="sy-gallery-prod-img" style="background-image: url('<?php echo esc_url( $prod_image ); ?>');"></div>
                        </a>
                        <h4 class="sy-gallery-prod-name">
                            <a href="<?php echo esc_url( $prod_url ); ?>"><?php echo esc_html( $prod_name ); ?></a>
                        </h4>
                        <span class="sy-gallery-prod-price"><?php echo $prod_price; ?></span>
                        <a href="<?php echo esc_url( $prod_url ); ?>" class="sy-gallery-buy-btn">상세보기 / 구매</a>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
    <?php endif; ?>

    <!-- 5. Navigation -->
    <div class="sy-gallery-detail-footer" style="margin-top: 40px; border-top: 1px solid #E5E0D8; padding-top: 20px; display: flex; justify-content: center;">
        <a href="<?php echo esc_url( home_url( '/gallery-board/' ) ); ?>" class="sy-btn sy-btn-secondary" style="border: 2px solid #3E2723; color: #3E2723; padding: 10px 24px; text-decoration: none; font-size: 14px; font-weight: 600; border-radius: 4px; text-align: center; transition: all 0.2s;">
            &larr; 갤러리 목록으로
        </a>
    </div>

</div>

<?php
get_footer();
?>
