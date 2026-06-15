<?php
/**
 * Plugin Name: Seyoung Quote
 * Plugin URI: http://localhost/seyoung
 * Description: 세영건재 대량구매 및 시공 견적 문의 접수 플러그인 (견적 폼 단축코드 및 관리자 DB 기록/메일 알림 기능)
 * Version: 1.0.0
 * Author: Honey Soft
 * Author URI: http://localhost/seyoung
 * Text Domain: seyoung-quote
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

// 1. Register Custom Post Type (seyoung_quote)
add_action( 'init', 'seyoung_quote_register_post_type' );
function seyoung_quote_register_post_type() {
    $labels = array(
        'name'               => '견적 문의',
        'singular_name'      => '견적 문의',
        'menu_name'          => '대량 견적 문의',
        'add_new'            => '새 견적 추가',
        'add_new_item'       => '새 견적 추가',
        'edit_item'          => '견적 보기/수정',
        'view_item'          => '견적 보기',
        'all_items'          => '모든 견적 접수 내역',
        'search_items'       => '견적 검색',
        'not_found'          => '접수된 견적 문의가 없습니다.',
    );

    $args = array(
        'labels'              => $labels,
        'public'              => false, // Admin only, no frontend single page
        'show_ui'             => true,
        'show_in_menu'        => true,
        'query_var'           => true,
        'capability_type'     => 'post',
        'hierarchical'        => false,
        'menu_position'       => 28,
        'menu_icon'           => 'dashicons-media-spreadsheet',
        'supports'            => array( 'title', 'editor' ),
    );

    register_post_type( 'seyoung_quote', $args );
}

// 2. Add Meta Boxes to show Quote Details in Admin
add_action( 'add_meta_boxes', 'seyoung_quote_add_meta_boxes' );
function seyoung_quote_add_meta_boxes() {
    add_meta_box(
        'seyoung_quote_detail',
        '견적 상세 신청 정보',
        'seyoung_quote_meta_box_callback',
        'seyoung_quote',
        'normal',
        'high'
    );
}

function seyoung_quote_meta_box_callback( $post ) {
    $phone = get_post_meta( $post->ID, '_quote_phone', true );
    $email = get_post_meta( $post->ID, '_quote_email', true );
    $type = get_post_meta( $post->ID, '_quote_type', true );
    $product_id = get_post_meta( $post->ID, '_quote_product_id', true );
    $file_url = get_post_meta( $post->ID, '_quote_file_url', true );

    $product_name = '선택 없음';
    if ( $product_id ) {
        $product = wc_get_product( $product_id );
        if ( $product ) {
            $product_name = $product->get_name() . ' (ID: ' . $product_id . ')';
        }
    }

    ?>
    <table class="form-table">
        <tr>
            <th>연락처</th>
            <td><strong><?php echo esc_html( $phone ); ?></strong></td>
        </tr>
        <tr>
            <th>이메일</th>
            <td><strong><?php echo esc_html( $email ); ?></strong></td>
        </tr>
        <tr>
            <th>문의 유형</th>
            <td><strong><?php echo esc_html( $type ); ?></strong></td>
        </tr>
        <tr>
            <th>문의 대상 제품</th>
            <td><strong><?php echo esc_html( $product_name ); ?></strong></td>
        </tr>
        <tr>
            <th>첨부 도면/파일</th>
            <td>
                <?php if ( ! empty( $file_url ) ) : ?>
                    <a href="<?php echo esc_url( $file_url ); ?>" target="_blank" class="button button-primary">파일 다운로드 / 보기</a>
                <?php else : ?>
                    첨부파일 없음
                <?php endif; ?>
            </td>
        </tr>
    </table>
    <?php
}

// 3. Enqueue Styles
add_action( 'wp_enqueue_scripts', 'seyoung_quote_enqueue_assets' );
function seyoung_quote_enqueue_assets() {
    wp_enqueue_style( 'seyoung-quote-css', plugins_url( 'seyoung-quote.css', __FILE__ ), array(), '1.0.0' );
}

// 4. Handle Quote Submission
add_action( 'wp_loaded', 'seyoung_quote_handle_submission' );
function seyoung_quote_handle_submission() {
    if ( ! isset( $_POST['seyoung_quote_nonce'] ) || ! wp_verify_nonce( $_POST['seyoung_quote_nonce'], 'seyoung_quote_action' ) ) {
        return;
    }

    $name = sanitize_text_field( $_POST['quote_name'] );
    $phone = sanitize_text_field( $_POST['quote_phone'] );
    $email = sanitize_email( $_POST['quote_email'] );
    $type = sanitize_text_field( $_POST['quote_type'] );
    $product_id = isset( $_POST['quote_product_id'] ) ? intval( $_POST['quote_product_id'] ) : 0;
    $message = wp_kses_post( $_POST['quote_message'] );

    if ( empty( $name ) || empty( $phone ) || empty( $email ) || empty( $message ) ) {
        wp_redirect( add_query_arg( 'quote_error', 'empty', wp_get_referer() ) );
        exit;
    }

    // Handle File Upload
    $file_url = '';
    if ( ! empty( $_FILES['quote_file']['name'] ) ) {
        require_once( ABSPATH . 'wp-admin/includes/file.php' );
        require_once( ABSPATH . 'wp-admin/includes/media.php' );
        require_once( ABSPATH . 'wp-admin/includes/image.php' );

        $uploaded_file = $_FILES['quote_file'];
        
        // Basic extension checks
        $allowed_types = array( 'image/jpeg', 'image/png', 'application/pdf', 'image/gif' );
        if ( ! in_array( $uploaded_file['type'], $allowed_types ) ) {
            wp_redirect( add_query_arg( 'quote_error', 'invalid_file', wp_get_referer() ) );
            exit;
        }

        $upload_overrides = array( 'test_form' => false );
        $movefile = wp_handle_upload( $uploaded_file, $upload_overrides );

        if ( $movefile && ! isset( $movefile['error'] ) ) {
            $file_url = $movefile['url'];
        } else {
            wp_redirect( add_query_arg( 'quote_error', 'upload_failed', wp_get_referer() ) );
            exit;
        }
    }

    // Create seyoung_quote CPT Post
    $title = "견적요청 - " . $name . " - " . date('Y-m-d');
    $new_quote = array(
        'post_title'   => $title,
        'post_content' => $message,
        'post_status'  => 'publish', // Stored safely (admins only since public => false)
        'post_type'    => 'seyoung_quote',
    );

    $post_id = wp_insert_post( $new_quote );

    if ( $post_id && ! is_wp_error( $post_id ) ) {
        update_post_meta( $post_id, '_quote_phone', $phone );
        update_post_meta( $post_id, '_quote_email', $email );
        update_post_meta( $post_id, '_quote_type', $type );
        update_post_meta( $post_id, '_quote_product_id', $product_id );
        update_post_meta( $post_id, '_quote_file_url', $file_url );

        // Send Email to Admin
        $admin_email = get_option( 'admin_email' );
        $subject = "[세영건재] 새 대량 견적/구매 문의 접수됨 - $name";
        $email_content = "새로운 대량 견적 문의가 접수되었습니다.\n\n";
        $email_content .= "고객/업체명: $name\n";
        $email_content .= "연락처: $phone\n";
        $email_content .= "이메일: $email\n";
        $email_content .= "문의 유형: $type\n";
        $email_content .= "상세 요청내용:\n$message\n\n";
        if ( ! empty( $file_url ) ) {
            $email_content .= "첨부파일(도면): $file_url\n";
        }
        $email_content .= "관리자 화면 주소: " . admin_url( 'post.php?post=' . $post_id . '&action=edit' ) . "\n";

        wp_mail( $admin_email, $subject, $email_content );

        wp_redirect( add_query_arg( 'quote_success', 'success', wp_get_referer() ) );
        exit;
    } else {
        wp_redirect( add_query_arg( 'quote_error', 'failed', wp_get_referer() ) );
        exit;
    }
}

// 5. Shortcode Renderer [seyoung_quote_form]
add_shortcode( 'seyoung_quote_form', 'seyoung_quote_form_renderer' );
function seyoung_quote_form_renderer() {
    ob_start();

    if ( isset( $_GET['quote_success'] ) ) {
        echo '<div class="sy-quote-alert success">✔️ 견적 문의가 안전하게 접수되었습니다. 관리자 확인 후 즉시 메일/전화로 연락드리겠습니다.</div>';
    }
    if ( isset( $_GET['quote_error'] ) ) {
        $msg = '견적 등록에 실패했습니다. 입력 양식을 다시 확인해 주세요.';
        if ( $_GET['quote_error'] === 'invalid_file' ) {
            $msg = '허용되지 않는 파일 형식입니다. 이미지(JPG, PNG, GIF) 또는 PDF 파일만 첨부해 주세요.';
        } elseif ( $_GET['quote_error'] === 'upload_failed' ) {
            $msg = '파일 업로드에 실패했습니다. 다시 시도해 주세요.';
        }
        echo '<div class="sy-quote-alert error">❌ ' . esc_html( $msg ) . '</div>';
    }
    ?>
    <div class="sy-quote-form-wrap">
        <h3 class="sy-quote-form-title">📋 대량 구매 및 시공 견적 상담 신청</h3>
        <p class="sy-quote-form-subtitle">시공 면적 도면이나 필요한 수량 명세를 첨부해주시면 더욱 신속한 견적이 가능합니다.</p>
        
        <form action="" method="post" enctype="multipart/form-data" class="sy-quote-form">
            <?php wp_nonce_field( 'seyoung_quote_action', 'seyoung_quote_nonce' ); ?>
            
            <div class="sy-quote-grid">
                <div class="sy-quote-field">
                    <label for="quote_name">고객명 / 업체명 <span class="required">*</span></label>
                    <input type="text" name="quote_name" id="quote_name" required placeholder="예: 홍길동 (인테리어디자인)">
                </div>

                <div class="sy-quote-field">
                    <label for="quote_phone">연락처 <span class="required">*</span></label>
                    <input type="text" name="quote_phone" id="quote_phone" required placeholder="예: 010-1234-5678">
                </div>
            </div>

            <div class="sy-quote-grid">
                <div class="sy-quote-field">
                    <label for="quote_email">이메일 주소 <span class="required">*</span></label>
                    <input type="email" name="quote_email" id="quote_email" required placeholder="예: user@example.com">
                </div>

                <div class="sy-quote-field">
                    <label for="quote_type">문의 유형 <span class="required">*</span></label>
                    <select name="quote_type" id="quote_type" required>
                        <option value="대량 자재 구매">📦 대량 자재 구매 (타일/도기 도매)</option>
                        <option value="욕실 시공 견적">🛠️ 욕실 리모델링/시공 견적</option>
                        <option value="기타 문의">❓ 기타 상담 및 도면 분석</option>
                    </select>
                </div>
            </div>

            <div class="sy-quote-field">
                <label for="quote_product_id">관심 상품 연동 (선택)</label>
                <select name="quote_product_id" id="quote_product_id">
                    <option value="0">--- 문의할 특정 제품 선택 (옵션) ---</option>
                    <?php
                    if ( class_exists( 'WooCommerce' ) ) {
                        $products = wc_get_products( array( 'limit' => -1, 'status' => 'publish' ) );
                        foreach ( $products as $product ) {
                            echo '<option value="' . esc_attr( $product->get_id() ) . '">' . esc_html( $product->get_name() ) . '</option>';
                        }
                    }
                    ?>
                </select>
            </div>

            <div class="sy-quote-field">
                <label for="quote_message">견적 요청 상세내용 <span class="required">*</span></label>
                <textarea name="quote_message" id="quote_message" rows="6" required placeholder="시공 면적, 제품 사양, 예상 납기일 등 상세한 문의 사항을 입력해 주세요."></textarea>
            </div>

            <div class="sy-quote-field">
                <label for="quote_file">도면 및 참고 자료 첨부 (이미지 또는 PDF, 최대 5MB)</label>
                <input type="file" name="quote_file" id="quote_file" accept=".jpg,.jpeg,.png,.gif,.pdf">
            </div>

            <div class="sy-quote-actions">
                <button type="submit" class="sy-btn sy-btn-primary">✉️ 견적 요청서 전송</button>
            </div>
        </form>
    </div>
    <?php
    return ob_get_clean();
}
