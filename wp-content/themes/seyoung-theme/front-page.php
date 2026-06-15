<?php
/**
 * The template for displaying the home page.
 *
 * @package Seyoung
 */

get_header();

// Swiper.js CDN 리소스 연동
echo '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />';
echo '<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>';

// 슬라이더 이미지 URL 획득
$theme_uri = get_stylesheet_directory_uri();
$slide1_url = $theme_uri . '/images/hero-bg1.png';
$slide2_url = $theme_uri . '/images/hero-bg2.png';
$slide3_url = $theme_uri . '/images/hero-bg3.png';
?>

<div class="sy-home-wrapper">

    <!-- Header-Carousel Category Navigation Bar (Royal&Co Style) -->
    <nav class="sy-top-cat-nav">
        <div class="sy-top-cat-container">
            <div class="sy-top-cat-main-menu">
                <?php
                // 상단 카테고리 퀵바 메뉴 정의 (맨 앞줄에 '신제품', '추천' 추가)
                $bar_categories = array(
                    array(
                        'slug' => 'new-products', 
                        'name' => '신제품', 
                        'custom_link' => add_query_arg('orderby', 'date', get_permalink(wc_get_page_id('shop'))),
                        'subs' => array('이달의 신상', '신규 타일', '최신 도기', '신제품 수전')
                    ),
                    array(
                        'slug' => 'featured-products', 
                        'name' => '추천', 
                        'custom_link' => '#sy-featured',
                        'subs' => array('MD 추천 상품', '실시간 베스트', '인기 패키지', '시공 만족 1위')
                    ),
                    array(
                        'slug' => 'tile', 
                        'name' => '타일', 
                        'subs' => array('포세린 타일', '폴리싱 타일', '모자이크 타일', '바닥 타일', '벽 타일')
                    ),
                    array(
                        'slug' => 'ceramics', 
                        'name' => '도기류', 
                        'subs' => array('양변기', '세면대', '소변기', '이동식 욕조', '비데')
                    ),
                    array(
                        'slug' => 'faucets', 
                        'name' => '수전·샤워기', 
                        'subs' => array('세면 수전', '샤워 수전', '주방 수전', '해바라기 샤워기')
                    ),
                    array(
                        'slug' => 'cabinets', 
                        'name' => '욕실장·거울', 
                        'subs' => array('슬라이딩 욕실장', '플랩장', 'LED 스마트 거울', '일반 거울')
                    ),
                    array(
                        'slug' => 'accessories', 
                        'name' => '욕실 소품', 
                        'subs' => array('수건걸이', '휴지걸이', '코너 선반', '욕실 소형 소품')
                    ),
                    array(
                        'slug' => 'materials', 
                        'name' => '부속·자재', 
                        'subs' => array('백시멘트', '타일 본드', '줄눈제', '배수 유가', '트랩 및 배관')
                    ),
                );

                foreach ($bar_categories as $index => $item) :
                    // 커스텀 링크가 있으면 쓰고, 없으면 우커머스 카테고리 링크 생성
                    if (isset($item['custom_link'])) {
                        $link = $item['custom_link'];
                    } else {
                        $term = get_term_by('slug', $item['slug'], 'product_cat');
                        $link = $term ? get_term_link($term) : get_permalink(wc_get_page_id('shop'));
                    }
                    ?>
                    <div class="sy-top-cat-item" data-target="sy-sub-bar-<?php echo $index; ?>">
                        <a href="<?php echo esc_url($link); ?>" class="sy-top-cat-link"><?php echo esc_html($item['name']); ?></a>
                    </div>
                <?php endforeach; ?>
                
                <span class="sy-menu-divider">|</span>
                
                <!-- Extra Service Pages -->
                <a href="<?php echo esc_url(home_url('/quote-board/')); ?>" class="sy-top-extra-link">대량견적</a>
                <a href="<?php echo esc_url(home_url('/gallery-board/')); ?>" class="sy-top-extra-link">시공사례</a>
                <a href="<?php echo esc_url(home_url('/qna-board/')); ?>" class="sy-top-extra-link">1:1문의</a>
            </div>

            <!-- Subcategory Rows (Outside the scrollable menu to prevent clipping) -->
            <?php foreach ($bar_categories as $index => $item) : ?>
                <div id="sy-sub-bar-<?php echo $index; ?>" class="sy-top-sub-bar">
                    <div class="sy-top-sub-container">
                        <?php foreach ($item['subs'] as $sub) : 
                            // 서브 메뉴 클릭 시 검색 쿼리 연동
                            $sub_link = add_query_arg('s', $sub, get_permalink(wc_get_page_id('shop')));
                            if ($item['slug'] === 'featured-products') {
                                // 추천 메뉴의 경우 메인페이지 추천 섹션으로 스크롤 점프 연동
                                $sub_link = '#sy-featured';
                            }
                        ?>
                            <a href="<?php echo esc_url($sub_link); ?>" class="sy-top-sub-link"><?php echo esc_html($sub); ?></a>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </nav>

    <!-- 1. 3-Slide Auto Rolling Hero Carousel (Swiper) -->
    <section class="swiper sy-hero-slider">
        <div class="swiper-wrapper">
            
            <!-- Slide 1: Premium Tiles -->
            <div class="swiper-slide sy-hero-slide-item" style="background-image: url('<?php echo esc_url($slide1_url); ?>');">
                <div class="sy-hero-overlay"></div>
                <div class="sy-hero-container">
                    <div class="sy-hero-card">
                        <span class="sy-hero-tagline">최고급 욕실 & 타일 전문</span>
                        <h1 class="sy-hero-title">감각적인 욕실 공간의 완성<br><span class="sy-gold-text">세영건재</span></h1>
                        <p class="sy-hero-desc">최고급 품질의 타일부터 감각적인 욕실 악세사리까지,<br>세영건재가 제안하는 격이 다른 욕실 솔루션을 만나보세요.</p>
                        <div class="sy-hero-actions">
                            <a href="#sy-categories" class="sy-btn sy-btn-primary">상품 카테고리</a>
                            <a href="#sy-featured" class="sy-btn sy-btn-outline">추천 상품</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Slide 2: Designer Faucets -->
            <div class="swiper-slide sy-hero-slide-item" style="background-image: url('<?php echo esc_url($slide2_url); ?>');">
                <div class="sy-hero-overlay"></div>
                <div class="sy-hero-container">
                    <div class="sy-hero-card">
                        <span class="sy-hero-tagline">디자인 수전 & 프리미엄 세면기</span>
                        <h1 class="sy-hero-title">디테일이 만들어내는 품격<br><span class="sy-gold-text">명품 디자인 수전</span></h1>
                        <p class="sy-hero-desc">무광 니켈, 로즈골드, 블랙 매트 등 디테일이 살아있는 프리미엄 수전으로<br>나만의 시그니처 욕실 인테리어를 연출해 보세요.</p>
                        <div class="sy-hero-actions">
                            <a href="<?php echo esc_url(get_term_link(get_term_by('slug', 'faucets', 'product_cat'))); ?>" class="sy-btn sy-btn-primary">수전 라인업</a>
                            <a href="#sy-featured" class="sy-btn sy-btn-outline">추천 상품</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Slide 3: Smart Ceramics -->
            <div class="swiper-slide sy-hero-slide-item" style="background-image: url('<?php echo esc_url($slide3_url); ?>');">
                <div class="sy-hero-overlay"></div>
                <div class="sy-hero-container">
                    <div class="sy-hero-card">
                        <span class="sy-hero-tagline">스마트 도기 & 욕실가구</span>
                        <h1 class="sy-hero-title">편리함과 미학의 조화<br><span class="sy-gold-text">스마트 양변기 & LED 거울</span></h1>
                        <p class="sy-hero-desc">센서 감지 자동 물내림 양변기와 무드 조명이 빛나는 LED 스마트 거울까지,<br>세영건재가 한 차원 진화한 욕실 라이프를 제시합니다.</p>
                        <div class="sy-hero-actions">
                            <a href="<?php echo esc_url(get_term_link(get_term_by('slug', 'ceramics', 'product_cat'))); ?>" class="sy-btn sy-btn-primary">도기류 보러가기</a>
                            <a href="#sy-featured" class="sy-btn sy-btn-outline">추천 상품</a>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Carousel Navigation & Pagination -->
        <div class="swiper-pagination"></div>
        <div class="swiper-button-prev"></div>
        <div class="swiper-button-next"></div>
    </section>



    <!-- 3. Categories Grid Section with Elegant SVG Icons & Hover Dropdown Subcategories -->
    <section id="sy-categories" class="sy-categories-section">
        <div class="sy-section-header">
            <span class="sy-section-tag">주요 상품</span>
            <h2 class="sy-section-title">주요 상품 카테고리</h2>
            <div class="sy-section-divider"></div>
            <p class="sy-section-subtitle">공간의 품격을 높이는 세영건재의 엄선된 제품 컬렉션</p>
        </div>

        <div class="sy-categories-grid">
            <?php
            // 카테고리 및 임의 생성 서브 카테고리 데이터 정의 (슬러그, 이름, 설명, SVG 경로, 서브 리스트)
            $categories_data = array(
                array(
                    'slug' => 'tile',
                    'name' => '타일 (바닥·벽)',
                    'desc' => '유럽풍 감성을 담은 심리스 포세린 및 유광/무광 인테리어 타일',
                    'svg' => '<rect x="3" y="3" width="8" height="8" rx="1" stroke-width="1.5" /><rect x="13" y="3" width="8" height="8" rx="1" stroke-width="1.5" /><rect x="3" y="13" width="8" height="8" rx="1" stroke-width="1.5" /><rect x="13" y="13" width="8" height="8" rx="1" stroke-width="1.5" />',
                    'subs' => array('포세린 타일', '폴리싱 타일', '모자이크 타일', '바닥 타일', '벽 타일')
                ),
                array(
                    'slug' => 'ceramics',
                    'name' => '도기류 (세면대·변기)',
                    'desc' => '곡선미가 살아있는 모던 세면볼과 청결하고 슬림한 비데 일체형 양변기',
                    'svg' => '<path d="M4 6h16v4a8 8 0 01-16 0V6z" stroke-width="1.5" /><path d="M12 14v6M8 20h8" stroke-width="1.5" />',
                    'subs' => array('양변기', '세면대', '소변기', '이동식 욕조', '비데')
                ),
                array(
                    'slug' => 'faucets',
                    'name' => '수전·샤워기',
                    'desc' => '무광 니켈, 로즈골드, 블랙 매트 등 디테일이 살아있는 프리미엄 수전',
                    'svg' => '<path d="M17 17a5 5 0 11-10 0M12 7V3m0 0H9m3 0h3M7 11h10" stroke-width="1.5" stroke-linecap="round"/><circle cx="12" cy="12" r="1" fill="currentColor"/>',
                    'subs' => array('세면 수전', '샤워 수전', '주방 수전', '해바라기 샤워기')
                ),
                array(
                    'slug' => 'cabinets',
                    'name' => '욕실장·거울',
                    'desc' => '부드러운 터치 센서 LED 거울과 짜임새 있는 수납을 돕는 슬라이딩 장',
                    'svg' => '<rect x="4" y="4" width="16" height="16" rx="2" stroke-width="1.5" /><path d="M12 4v16M4 10h16" stroke-width="1.5" />',
                    'subs' => array('슬라이딩 욕실장', '플랩장', 'LED 스마트 거울', '일반 거울')
                ),
                array(
                    'slug' => 'accessories',
                    'name' => '욕실 소품·악세사리',
                    'desc' => '수건걸이, 황동 휴지걸이, 고급 코너 선반 등 욕실의 품격을 채우는 소품',
                    'svg' => '<path d="M5 8h14M5 14h14M8 4h8" stroke-width="1.5" stroke-linecap="round"/><circle cx="5" cy="8" r="1" fill="currentColor"/><circle cx="19" cy="8" r="1" fill="currentColor"/><circle cx="5" cy="14" r="1" fill="currentColor"/><circle cx="19" cy="14" r="1" fill="currentColor"/>',
                    'subs' => array('수건걸이', '휴지걸이', '코너 선반', '욕실 소형 소품')
                ),
                array(
                    'slug' => 'materials',
                    'name' => '부속·자재',
                    'desc' => '친환경 백시멘트, 아덱스 줄눈제, 타일 접착제 및 배수 유가류 일체',
                    'svg' => '<path d="M14.7 6.3a1 1 0 000 1.4l1.6 1.6a1 1 0 001.4 0l3.77-3.77a6 6 0 01-7.94 7.94l-6.91 6.91a2.12 2.12 0 01-3-3l6.91-6.91a6 6 0 017.94-7.94l-3.77 3.77z" stroke-width="1.5" />',
                    'subs' => array('백시멘트', '타일 본드', '줄눈제', '배수 유가', '트랩 및 배관')
                ),
            );

            foreach ($categories_data as $cat) {
                $term = get_term_by('slug', $cat['slug'], 'product_cat');
                $link = $term ? get_term_link($term) : get_permalink(wc_get_page_id('shop'));
                ?>
                <div class="sy-category-card">
                    <a href="<?php echo esc_url($link); ?>" class="sy-category-card-top-link">
                        <div class="sy-category-icon-wrapper">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="sy-category-svg">
                                <?php echo $cat['svg']; ?>
                            </svg>
                        </div>
                        <h3 class="sy-category-title"><?php echo esc_html($cat['name']); ?></h3>
                        <p class="sy-category-desc"><?php echo esc_html($cat['desc']); ?></p>
                    </a>
                    
                    <!-- Hover Dropdown Subcategories -->
                    <div class="sy-subcategory-list-wrapper">
                        <span class="sy-subcategory-header">주요 품목</span>
                        <div class="sy-subcategory-list">
                            <?php foreach ($cat['subs'] as $sub_name) : 
                                // 서브 카테고리를 눌렀을 때 상점의 카테고리 필터 검색 연동을 위해 search query 바인딩
                                $sub_link = add_query_arg('s', $sub_name, $link);
                            ?>
                                <a href="<?php echo esc_url($sub_link); ?>" class="sy-subcategory-link-item"><?php echo esc_html($sub_name); ?></a>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <a href="<?php echo esc_url($link); ?>" class="sy-category-link">전체 상품 보기
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="sy-arrow-icon">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </a>
                </div>
                <?php
            }
            ?>
        </div>
    </section>

    <!-- 4. Featured Products Section with Modern Card Aesthetics -->
    <section id="sy-featured" class="sy-products-section">
        <div class="sy-container">
            <div class="sy-section-header">
                <span class="sy-section-tag">추천 컬렉션</span>
                <h2 class="sy-section-title">이달의 추천 베스트 상품</h2>
                <div class="sy-section-divider"></div>
                <p class="sy-section-subtitle">세영건재 매장에서 가장 인기 있는 시공 만족도 1위의 상품 라인업</p>
            </div>

            <div class="sy-products-grid">
                <?php
                if ( class_exists( 'WooCommerce' ) ) {
                    $query_args = array(
                        'post_type'      => 'product',
                        'posts_per_page' => 6,
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
                                            <span class="sy-sale-badge">세일</span>
                                        <?php endif; ?>
                                        <div class="sy-product-overlay">
                                            <span class="sy-product-view-btn">상세보기</span>
                                        </div>
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
                                        <a href="?add-to-cart=<?php echo esc_attr( $post->ID ); ?>" class="sy-product-cart-btn">
                                            담기
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <?php
                        endwhile;
                        wp_reset_postdata();
                    } else {
                        // 상품이 아직 등록되지 않은 경우 플레이스홀더 출력
                        for ($i = 1; $i <= 6; $i++) {
                            ?>
                            <div class="sy-product-card sy-product-placeholder">
                                <div class="sy-product-image" style="background-color: var(--sy-gray-border); display: flex; align-items: center; justify-content: center;">
                                    <span style="font-size: 36px; color: var(--sy-light-brown);">📦</span>
                                </div>
                                <div class="sy-product-info">
                                    <span class="sy-product-cat">상품</span>
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
        </div>
    </section>

    <!-- Brand Value Section (Why Seyoung?) -->
    <section class="sy-value-section">
        <div class="sy-container">
            <div class="sy-value-grid">
                <div class="sy-value-card">
                    <div class="sy-value-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                        </svg>
                    </div>
                    <h3>최고급 품질 보장</h3>
                    <p>내구성과 내식성이 우수한 검증된 최상급 브랜드 상품만을 공급합니다.</p>
                </div>
                <div class="sy-value-card">
                    <div class="sy-value-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3>정직한 우대가격</h3>
                    <p>중간 유통 마진을 줄여 일반 소비자 및 시공기사분들께 정직하고 합리적인 우대가격을 보장합니다.</p>
                </div>
                <div class="sy-value-card">
                    <div class="sy-value-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" />
                        </svg>
                    </div>
                    <h3>전문 상품 매칭</h3>
                    <p>30년 경력의 노하우를 바탕으로 현장에 딱 맞는 면적 계산 및 완벽한 상품 매칭 솔루션을 제안합니다.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- 5. Premium CTA Section -->
    <section class="sy-cta-section">
        <div class="sy-cta-container">
            <span class="sy-cta-tag">대량 주문 및 B2B 협력</span>
            <h2>대량 상품 발주 및 도면 견적 상담</h2>
            <p>신축/리모델링 시공업자 및 대량 구매를 희망하시는 파트너 고객을 위해<br>특별 맞춤형 우대가격과 정밀한 수량 설계를 제공합니다.</p>
            <div class="sy-cta-buttons">
                <a href="<?php echo esc_url(home_url('/quote-board/')); ?>" class="sy-btn sy-btn-accent">✉️ 온라인 대량 견적 문의</a>
                <a href="tel:010-0000-0000" class="sy-btn sy-btn-outline-white">📞 전화 상담 문의</a>
            </div>
        </div>
    </section>

</div>

<!-- Swiper & Drag Scroll Initialization Script (Safe loading check) -->
<script>
(function() {
    function initSwiper() {
        if (typeof Swiper !== 'undefined') {
            new Swiper('.sy-hero-slider', {
                loop: true,
                autoplay: {
                    delay: 4000,
                    disableOnInteraction: false,
                },
                effect: 'fade',
                fadeEffect: {
                    crossFade: true
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
            });
        } else {
            setTimeout(initSwiper, 50);
        }
    }

    function initDragScroll() {
        const slider = document.querySelector('.sy-top-cat-main-menu');
        if (!slider) return;
        
        let isDown = false;
        let startX;
        let scrollLeft;
        
        slider.addEventListener('mousedown', (e) => {
            isDown = true;
            slider.style.cursor = 'grabbing';
            startX = e.pageX - slider.offsetLeft;
            scrollLeft = slider.scrollLeft;
        });
        
        slider.addEventListener('mouseleave', () => {
            isDown = false;
            slider.style.cursor = 'default';
        });
        
        slider.addEventListener('mouseup', () => {
            isDown = false;
            slider.style.cursor = 'default';
        });
        
        slider.addEventListener('mousemove', (e) => {
            if (!isDown) return;
            e.preventDefault();
            const x = e.pageX - slider.offsetLeft;
            const walk = (x - startX) * 1.5; // Scroll speed multiplier
            slider.scrollLeft = scrollLeft - walk;
        });
    }

    function initSubMenuHover() {
        const items = document.querySelectorAll('.sy-top-cat-item');
        const subBars = document.querySelectorAll('.sy-top-sub-bar');
        
        items.forEach(item => {
            const targetId = item.getAttribute('data-target');
            const targetBar = document.getElementById(targetId);
            if (!targetBar) return;
            
            let hideTimeout;
            
            function showMenu() {
                clearTimeout(hideTimeout);
                subBars.forEach(bar => {
                    if (bar !== targetBar) {
                        bar.classList.remove('active');
                    }
                });
                targetBar.classList.add('active');
            }
            
            function hideMenu() {
                hideTimeout = setTimeout(() => {
                    targetBar.classList.remove('active');
                }, 100);
            }
            
            item.addEventListener('mouseenter', showMenu);
            item.addEventListener('mouseleave', hideMenu);
            
            targetBar.addEventListener('mouseenter', () => {
                clearTimeout(hideTimeout);
            });
            targetBar.addEventListener('mouseleave', hideMenu);
            
            // For mobile touch screens, toggle on tap
            item.addEventListener('click', (e) => {
                // If it's a touch pointer, show/toggle instead of immediate link navigation
                if (window.matchMedia('(max-width: 768px)').matches) {
                    if (!targetBar.classList.contains('active')) {
                        e.preventDefault();
                        showMenu();
                    }
                }
            });
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        initSwiper();
        initDragScroll();
        initSubMenuHover();
    });
})();
</script>

<?php
get_footer();
?>
