<?php
/**
 * The template for displaying the home page.
 *
 * @package Seyoung
 */

get_header();
?>

<div class="sy-home-wrapper">

    <!-- 1. Hero Section -->
    <section class="sy-hero-section">
        <div class="sy-hero-overlay"></div>
        <div class="sy-hero-container">
            <span class="sy-hero-tagline">세영건재 온라인 스토어</span>
            <h1 class="sy-hero-title">타일 · 욕실용품 · 세면용품 전문</h1>
            <p class="sy-hero-desc">감각적인 공간의 완성, 세영건재가 최고급 자재와 차별화된 품격으로 보답합니다.</p>
            <div class="sy-hero-actions">
                <a href="#sy-categories" class="sy-btn sy-btn-primary">카테고리 둘러보기</a>
                <a href="#sy-featured" class="sy-btn sy-btn-secondary">추천 상품</a>
            </div>
        </div>
    </section>

    <!-- 2. Categories Grid Section -->
    <section id="sy-categories" class="sy-categories-section">
        <div class="sy-section-header">
            <h2 class="sy-section-title">주요 카테고리</h2>
            <p class="sy-section-subtitle">세영건재가 엄선한 6가지 핵심 제품군을 소개합니다.</p>
            <div class="sy-section-divider"></div>
        </div>

        <div class="sy-categories-grid">
            <?php
            // 카테고리 목록 정의 (슬러그 기준)
            $categories_data = array(
                array('slug' => 'tile', 'name' => '타일 (바닥·벽)', 'desc' => '심리스·패턴 명품 타일', 'icon' => '🧱'),
                array('slug' => 'ceramics', 'name' => '도기류 (세면대·변기)', 'desc' => '위생적이고 아름다운 도기', 'icon' => '🚽'),
                array('slug' => 'faucets', 'name' => '수전·샤워기', 'desc' => '디자인 프리미엄 수전', 'icon' => '🚿'),
                array('slug' => 'cabinets', 'name' => '욕실장·거울', 'desc' => '실용적인 수납과 LED 거울', 'icon' => '🪞'),
                array('slug' => 'accessories', 'name' => '욕실 소품·악세사리', 'desc' => '무광 블랙·샤틴 욕실 소품', 'icon' => '🧼'),
                array('slug' => 'materials', 'name' => '부속·자재', 'desc' => '부자재 및 줄눈 소모품', 'icon' => '🛠️'),
            );

            foreach ($categories_data as $cat) {
                $term = get_term_by('slug', $cat['slug'], 'product_cat');
                // 만약 해당 카테고리가 없으면 상점 페이지로 링크
                $link = $term ? get_term_link($term) : get_permalink(wc_get_page_id('shop'));
                ?>
                <a href="<?php echo esc_url($link); ?>" class="sy-category-card">
                    <div class="sy-category-icon"><?php echo esc_html($cat['icon']); ?></div>
                    <h3 class="sy-category-title"><?php echo esc_html($cat['name']); ?></h3>
                    <p class="sy-category-desc"><?php echo esc_html($cat['desc']); ?></p>
                    <span class="sy-category-arrow">자세히 보기 &rarr;</span>
                </a>
                <?php
            }
            ?>
        </div>
    </section>

    <!-- 3. Featured Products Section -->
    <section id="sy-featured" class="sy-products-section">
        <div class="sy-section-header">
            <h2 class="sy-section-title">세영건재 추천 상품</h2>
            <p class="sy-section-subtitle">품격 높은 욕실 인테리어를 위한 인기 베스트셀러</p>
            <div class="sy-section-divider"></div>
        </div>

        <div class="sy-products-grid">
            <?php
            // 우커머스에서 최근 등록된 상품 4개 가져오기
            if ( class_exists( 'WooCommerce' ) ) {
                $query_args = array(
                    'post_type'      => 'product',
                    'posts_per_page' => 4,
                    'orderby'        => 'date',
                    'order'          => 'DESC',
                );
                $loop = new WP_Query( $query_args );

                if ( $loop->have_posts() ) {
                    while ( $loop->have_posts() ) : $loop->the_post();
                        global $product;
                        $price = $product->get_price_html();
                        $image = get_the_post_thumbnail_url($post->ID, 'medium') ?: wc_placeholder_img_src('medium');
                        $permalink = get_permalink();
                        ?>
                        <div class="sy-product-card">
                            <a href="<?php echo esc_url($permalink); ?>" class="sy-product-img-link">
                                <div class="sy-product-image" style="background-image: url('<?php echo esc_url($image); ?>');">
                                    <?php if ( $product->is_on_sale() ) : ?>
                                        <span class="sy-sale-badge">SALE</span>
                                    <?php endif; ?>
                                </div>
                            </a>
                            <div class="sy-product-info">
                                <span class="sy-product-cat">
                                    <?php
                                    $terms = get_the_terms( $post->ID, 'product_cat' );
                                    if ( $terms && ! is_wp_error( $terms ) ) {
                                        echo esc_html( $terms[0]->name );
                                    }
                                    ?>
                                </span>
                                <h3 class="sy-product-title">
                                    <a href="<?php echo esc_url($permalink); ?>"><?php the_title(); ?></a>
                                </h3>
                                <div class="sy-product-price-row">
                                    <span class="sy-product-price"><?php echo $price; ?></span>
                                    <a href="?add-to-cart=<?php echo esc_attr( $post->ID ); ?>" class="sy-product-cart-btn" aria-label="장바구니 담기">
                                        🛒 담기
                                    </a>
                                </div>
                            </div>
                        </div>
                        <?php
                    endwhile;
                    wp_reset_postdata();
                } else {
                    // 상품이 없을 때 표시되는 안내 카드
                    for ($i = 1; $i <= 4; $i++) {
                        ?>
                        <div class="sy-product-card sy-product-placeholder">
                            <div class="sy-product-image" style="background-color: var(--sy-gray-border); display: flex; align-items: center; justify-content: center; font-size: 32px;">
                                📦
                            </div>
                            <div class="sy-product-info">
                                <span class="sy-product-cat">카테고리</span>
                                <h3 class="sy-product-title">준비된 상품이 없습니다.</h3>
                                <div class="sy-product-price-row">
                                    <span class="sy-product-price">등록 대기중</span>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                }
            } else {
                echo '<p class="sy-error">우커머스 플러그인이 비활성화 상태입니다.</p>';
            }
            ?>
        </div>
    </section>

    <!-- 4. Banner / Call to Action Section -->
    <section class="sy-cta-section">
        <div class="sy-cta-container">
            <h2>대량 주문 및 맞춤형 견적 상담</h2>
            <p>시공업자(인테리어 기사님) 및 대량 구매 고객을 위해 우대가 단가 및 친절한 상담을 제공합니다.</p>
            <a href="tel:010-0000-0000" class="sy-btn sy-btn-accent">📞 매장 전화 상담문의</a>
        </div>
    </section>

</div>

<?php
get_footer();
?>
