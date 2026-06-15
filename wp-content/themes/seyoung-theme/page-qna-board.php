<?php
/**
 * Template Name: CS Center Page
 *
 * The template for displaying the customer service center page.
 *
 * @package Seyoung
 */

get_header();
?>

<div class="sy-cs-page-wrapper">
    <div class="sy-cs-container">
        
        <!-- Sidebar Menu -->
        <aside class="sy-cs-sidebar">
            <h1 class="sy-cs-page-title">CS CENTER</h1>
            <ul class="sy-cs-side-menu">
                <li class="active"><a href="#none">공지사항</a></li>
                <li><a href="#none">자주 묻는 질문</a></li>
                <li><a href="#none">상품문의</a></li>
                <li><a href="#none">상품후기</a></li>
                <li><a href="#none">대량구매</a></li>
            </ul>
        </aside>
        
        <!-- Main Content Area -->
        <main class="sy-cs-main-content">
            <!-- 6-Grid Icon Navigation -->
            <div class="sy-cs-grid-nav">
                <a href="#none" class="sy-cs-grid-item">
                    <div class="sy-cs-icon-box">
                        <!-- Truck icon SVG -->
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10h10z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 8h5.172a1 1 0 01.707.293l2.828 2.828a1 1 0 01.293.707V16H13V8z" />
                        </svg>
                    </div>
                    <span>배송문의</span>
                </a>
                <a href="#none" class="sy-cs-grid-item">
                    <div class="sy-cs-icon-box">
                        <!-- Box icon SVG -->
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </div>
                    <span>반품/교환문의</span>
                </a>
                <a href="#none" class="sy-cs-grid-item">
                    <div class="sy-cs-icon-box">
                        <!-- Shirt/Product icon SVG -->
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 4L7.5 6.5L4 7.5L5.5 11.5L7.5 11V20H16.5V11L18.5 11.5L20 7.5L16.5 6.5L15 4H9Z" />
                        </svg>
                    </div>
                    <span>상품문의</span>
                </a>
                <a href="#none" class="sy-cs-grid-item">
                    <div class="sy-cs-icon-box">
                        <!-- Coupon ticket icon SVG -->
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                        </svg>
                    </div>
                    <span>쿠폰내역</span>
                </a>
                <a href="#none" class="sy-cs-grid-item">
                    <div class="sy-cs-icon-box">
                        <!-- M logo icon SVG -->
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <circle cx="12" cy="12" r="9" stroke-width="1.5" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 16V8L12 12L16 8v8" />
                        </svg>
                    </div>
                    <span>마일리지내역</span>
                </a>
                <a href="#none" class="sy-cs-grid-item">
                    <div class="sy-cs-icon-box">
                        <!-- FAQ round text icon SVG -->
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <circle cx="12" cy="12" r="9" stroke-width="1.5" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6.5 15V9h3M6.5 12h2" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.5 15l1.5-6l1.5 6M11.8 13.5h2.4" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 12.5a1.5 1.5 0 113 0 1.5 1.5 0 01-3 0z M17.5 14l1.5 1.5" />
                        </svg>
                    </div>
                    <span>자주묻는질문</span>
                </a>
            </div>
            
            <!-- FAQ TOP 5 Section -->
            <div class="sy-cs-faq-section">
                <div class="sy-cs-section-header">
                    <h2>자주 묻는 질문 TOP5</h2>
                    <a href="#none" class="sy-cs-more-btn">더보기 ➔</a>
                </div>
                <div class="sy-cs-faq-list">
                    <div class="sy-cs-faq-item">
                        <span class="sy-cs-q-badge">Q</span>
                        <a href="#none" class="sy-cs-faq-title">[기타] A/S 서비스 요금 안내</a>
                    </div>
                    <div class="sy-cs-faq-item">
                        <span class="sy-cs-q-badge">Q</span>
                        <a href="#none" class="sy-cs-faq-title">[배송안내] 배송정책</a>
                    </div>
                    <div class="sy-cs-faq-item">
                        <span class="sy-cs-q-badge">Q</span>
                        <a href="#none" class="sy-cs-faq-title">[상품관련] 쓰리홀 세면기 수전의 경우 플렉시블 호스가 기본장착되어 있는지요</a>
                    </div>
                    <div class="sy-cs-faq-item">
                        <span class="sy-cs-q-badge">Q</span>
                        <a href="#none" class="sy-cs-faq-title">[상품관련] 절수페달을 설치할 수 있는 싱크수전은 어떤것인가요?</a>
                    </div>
                    <div class="sy-cs-faq-item">
                        <span class="sy-cs-q-badge">Q</span>
                        <a href="#none" class="sy-cs-faq-title">[상품관련] 레인샤워헤드 재질중 스테인레스 재질의 사양이 있나요?</a>
                    </div>
                </div>
            </div>
        </main>
        
    </div>
</div>

<!-- Custom CS Footer (Replaces standard footer visually) -->
<footer class="sy-cs-footer">
    <div class="sy-cs-footer-container">
        <!-- Col 1: Customer Center & Bank -->
        <div class="sy-cs-footer-col">
            <div class="sy-cs-footer-sns">
                <a href="#none" class="sy-sns-btn sy-sns-fb">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" width="16" height="16">
                        <path d="M22 12c0-5.52-4.48-10-10-10S2 6.48 2 12c0 4.84 3.44 8.87 8 9.8V15H8v-3h2V9.5C10 7.57 11.57 6 13.5 6H16v3h-2c-.55 0-1 .45-1 1v2h3v3h-3v6.95c4.56-.93 8-4.96 8-9.75z"/>
                    </svg>
                </a>
                <a href="#none" class="sy-sns-btn sy-sns-blog">
                    <span style="font-weight: 800; font-size: 10px;">blog</span>
                </a>
                <a href="#none" class="sy-sns-btn sy-sns-insta">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" width="16" height="16">
                        <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.051.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/>
                    </svg>
                </a>
            </div>
            <div class="sy-cs-footer-info">
                <h3>CS CENTER 1566-7070</h3>
                <p class="sy-cs-time">평일 09:00 - 18:00</p>
                <p class="sy-cs-time">토요일 09:00 - 12:00 (A/S 상담만 가능)</p>
                <p class="sy-cs-time">일요일·공휴일 휴무</p>
            </div>
            <div class="sy-cs-footer-bank">
                <h3>BANK INFO</h3>
                <p>KEB하나은행 115-89000-481505</p>
                <p>예금주: 세영건재 주식회사</p>
            </div>
        </div>
        
        <!-- Col 2: Quick Links & Family Site -->
        <div class="sy-cs-footer-col sy-cs-footer-menu-col">
            <div class="sy-cs-footer-nav-links">
                <h3>NAV LINKS</h3>
                <div class="sy-cs-nav-grid">
                    <a href="<?php echo esc_url( get_permalink( get_option('woocommerce_myaccount_page_id') ) ); ?>">MY PAGE</a>
                    <a href="<?php echo esc_url( wc_get_cart_url() ); ?>">CART</a>
                    <a href="<?php echo esc_url( home_url('/qna-board/') ); ?>">CS CENTER</a>
                    <a href="<?php echo esc_url( home_url('/about/') ); ?>">ABOUT US</a>
                    <a href="#none">이용약관</a>
                    <a href="#none">개인정보처리방침</a>
                    <a href="#none">제휴문의</a>
                </div>
            </div>
            <div class="sy-cs-footer-family">
                <h3>FAMILY SITE</h3>
                <a href="#none">세영건재 쇼룸</a>
                <a href="#none">세영라운지</a>
            </div>
        </div>
        
        <!-- Col 3: Company Info -->
        <div class="sy-cs-footer-col sy-cs-footer-company-col">
            <h3>세영건재(주) 사업자 정보</h3>
            <p>회사명 : 세영건재 주식회사</p>
            <p>대표자 : 안세영</p>
            <p>주소 : 경기도 화성시 시청로 895-20</p>
            <p>전화 : 1566-7070</p>
            <p>사업자등록번호 : 122-81-06434</p>
            <p>통신판매업신고번호 : 제2015-화성팔탄-0005호</p>
            <p>개인정보보호책임자 : 홍길동 (support@seyoung.com)</p>
            <p class="sy-cs-copyright">Copyright© 세영건재 주식회사 All rights reserved.<br>Hosting by 가비아 퍼스트몰</p>
        </div>
    </div>
</footer>

<?php
get_footer();
?>
