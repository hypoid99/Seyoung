<?php
/**
 * Plugin Name: Seyoung Q&A Board
 * Plugin URI: http://localhost/seyoung
 * Description: 세영건재 1:1 고객 문의 게시판 플러그인 (비밀글 작성 및 관리자 댓글 답변 연동)
 * Version: 1.0.0
 * Author: Honey Soft
 * Author URI: http://localhost/seyoung
 * Text Domain: seyoung-board
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

// 1. Register Custom Post Type (seyoung_qna)
add_action( 'init', 'seyoung_qna_register_post_type' );
function seyoung_qna_register_post_type() {
    $labels = array(
        'name'               => '1:1 문의',
        'singular_name'      => '1:1 문의',
        'menu_name'          => '1:1 문의',
        'add_new'            => '새 문의 작성',
        'add_new_item'       => '새 문의 작성',
        'edit_item'          => '문의 수정',
        'new_item'           => '새 문의',
        'view_item'          => '문의 보기',
        'search_items'       => '문의 검색',
        'not_found'          => '등록된 문의가 없습니다.',
        'not_found_in_trash' => '휴지통에 문의가 없습니다.',
    );

    $args = array(
        'labels'              => $labels,
        'public'              => true,
        'has_archive'         => false,
        'publicly_queryable'  => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'query_var'           => true,
        'rewrite'             => array( 'slug' => 'qna' ),
        'capability_type'     => 'post',
        'hierarchical'        => false,
        'menu_position'       => 26,
        'menu_icon'           => 'dashicons-testimonial',
        'supports'            => array( 'title', 'editor', 'author', 'comments' ),
    );

    register_post_type( 'seyoung_qna', $args );
}

// 2. Load custom single template for seyoung_qna
add_filter( 'single_template', 'seyoung_qna_load_single_template' );
function seyoung_qna_load_single_template( $single_template ) {
    global $post;
    if ( $post && $post->post_type === 'seyoung_qna' ) {
        $file = plugin_dir_path( __FILE__ ) . 'single-seyoung_qna.php';
        if ( file_exists( $file ) ) {
            return $file;
        }
    }
    return $single_template;
}

// 3. gracefull redirect for unauthorized private posts view
add_action( 'template_redirect', 'seyoung_qna_restrict_access' );
function seyoung_qna_restrict_access() {
    if ( is_singular( 'seyoung_qna' ) ) {
        global $post;
        if ( $post->post_status === 'private' ) {
            $current_user_id = get_current_user_id();
            $is_author = ( $current_user_id && $current_user_id == $post->post_author );
            $is_admin = current_user_can( 'manage_options' );
            if ( ! $is_author && ! $is_admin ) {
                wp_redirect( home_url( '/qna-board/' ) ); // Redirect to main Q&A page
                exit;
            }
        }
    }
}

// 4. Enqueue Styles
add_action( 'wp_enqueue_scripts', 'seyoung_qna_enqueue_assets' );
function seyoung_qna_enqueue_assets() {
    wp_enqueue_style( 'seyoung-board-css', plugins_url( 'seyoung-board.css', __FILE__ ), array(), '1.0.0' );
}

// 5. Handle Frontend Form Submission
add_action( 'wp_loaded', 'seyoung_qna_handle_submission' );
function seyoung_qna_handle_submission() {
    if ( ! isset( $_POST['seyoung_qna_nonce'] ) || ! wp_verify_nonce( $_POST['seyoung_qna_nonce'], 'seyoung_qna_action' ) ) {
        return;
    }

    if ( ! is_user_logged_in() ) {
        wp_die( '로그인이 필요한 서비스입니다.' );
    }

    $title = sanitize_text_field( $_POST['qna_title'] );
    $content = wp_kses_post( $_POST['qna_content'] );
    $is_private = isset( $_POST['qna_private'] ) && $_POST['qna_private'] === 'yes';

    if ( empty( $title ) || empty( $content ) ) {
        wp_redirect( add_query_arg( 'qna_error', 'empty', wp_get_referer() ) );
        exit;
    }

    $new_post = array(
        'post_title'   => $title,
        'post_content' => $content,
        'post_status'  => $is_private ? 'private' : 'publish',
        'post_type'    => 'seyoung_qna',
        'post_author'  => get_current_user_id(),
    );

    $post_id = wp_insert_post( $new_post );

    if ( $post_id && ! is_wp_error( $post_id ) ) {
        wp_redirect( add_query_arg( 'qna_success', 'success', wp_get_referer() ) );
        exit;
    } else {
        wp_redirect( add_query_arg( 'qna_error', 'failed', wp_get_referer() ) );
        exit;
    }
}

// 6. Q&A Board Shortcode ([seyoung_qna_board])
add_shortcode( 'seyoung_qna_board', 'seyoung_qna_board_renderer' );
function seyoung_qna_board_renderer() {
    ob_start();
    
    $current_user_id = get_current_user_id();
    $is_admin = current_user_can( 'manage_options' );

    // Handling alerts/notices
    if ( isset( $_GET['qna_success'] ) ) {
        echo '<div class="sy-board-alert success">✔️ 문의가 성공적으로 등록되었습니다.</div>';
    }
    if ( isset( $_GET['qna_error'] ) ) {
        echo '<div class="sy-board-alert error">❌ 문의 등록에 실패했습니다. 내용을 모두 입력해 주세요.</div>';
    }

    // Determine current view (list or write form)
    $view = isset( $_GET['action'] ) && $_GET['action'] === 'write' ? 'write' : 'list';

    if ( $view === 'write' ) {
        if ( ! is_user_logged_in() ) {
            echo '<div class="sy-board-message">로그인한 회원만 문의를 작성할 수 있습니다. <a href="' . wp_login_url( get_permalink() ) . '" class="sy-btn sy-btn-primary">로그인하기</a></div>';
        } else {
            ?>
            <div class="sy-board-form-wrap">
                <h3 class="sy-form-title">✏️ 1:1 고객 문의 작성</h3>
                <form action="" method="post" class="sy-qna-form">
                    <?php wp_nonce_field( 'seyoung_qna_action', 'seyoung_qna_nonce' ); ?>
                    
                    <div class="sy-form-group">
                        <label for="qna_title">문의 제목</label>
                        <input type="text" name="qna_title" id="qna_title" required placeholder="예: 타일 배송 일정 문의드립니다.">
                    </div>

                    <div class="sy-form-group">
                        <label for="qna_content">문의 내용</label>
                        <textarea name="qna_content" id="qna_content" rows="8" required placeholder="상세한 문의 내용을 남겨주세요."></textarea>
                    </div>

                    <div class="sy-form-group checkbox-group">
                        <label>
                            <input type="checkbox" name="qna_private" value="yes" checked>
                            <span>🔒 비밀글로 작성 (나와 관리자만 열람 가능)</span>
                        </label>
                    </div>

                    <div class="sy-form-actions">
                        <button type="submit" class="sy-btn sy-btn-primary">등록하기</button>
                        <a href="<?php echo esc_url( remove_query_arg('action') ); ?>" class="sy-btn sy-btn-secondary">목록으로</a>
                    </div>
                </form>
            </div>
            <?php
        }
    } else {
        // LIST VIEW
        $paged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
        $args = array(
            'post_type'      => 'seyoung_qna',
            'post_status'    => array( 'publish', 'private' ), // Query both public and private
            'posts_per_page' => 10,
            'paged'          => $paged,
        );

        $query = new WP_Query( $args );
        ?>
        <div class="sy-board-list-wrap">
            <div class="sy-board-header">
                <h3>📋 1:1 문의 내역</h3>
                <?php if ( is_user_logged_in() ) : ?>
                    <a href="<?php echo esc_url( add_query_arg( 'action', 'write' ) ); ?>" class="sy-btn sy-btn-primary">✏️ 문의하기</a>
                <?php else : ?>
                    <a href="<?php echo wp_login_url( get_permalink() ); ?>" class="sy-btn sy-btn-secondary">로그인 후 문의하기</a>
                <?php endif; ?>
            </div>

            <table class="sy-board-table">
                <thead>
                    <tr>
                        <th class="col-num">번호</th>
                        <th class="col-title">제목</th>
                        <th class="col-author">작성자</th>
                        <th class="col-date">작성일</th>
                        <th class="col-status">답변 상태</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ( $query->have_posts() ) {
                        $count = $query->found_posts - (($paged - 1) * 10);
                        while ( $query->have_posts() ) {
                            $query->the_post();
                            $post_id = get_the_ID();
                            $status = get_post_status( $post_id );
                            $is_private = ( $status === 'private' );
                            $post_author_id = get_the_author_meta( 'ID' );

                            $has_access = ( ! $is_private || $current_user_id == $post_author_id || $is_admin );

                            // Check answer status (BACS comment count)
                            $comments_count = get_comments_number( $post_id );
                            $status_badge = ( $comments_count > 0 ) ? '<span class="sy-badge status-replied">답변완료</span>' : '<span class="sy-badge status-waiting">답변대기</span>';

                            echo '<tr>';
                            echo '<td class="col-num">' . $count-- . '</td>';
                            
                            if ( $has_access ) {
                                $lock_icon = $is_private ? '🔒 ' : '';
                                echo '<td class="col-title"><a href="' . esc_url( get_permalink() ) . '">' . $lock_icon . esc_html( get_the_title() ) . '</a></td>';
                            } else {
                                echo '<td class="col-title text-muted">🔒 비밀글입니다.</td>';
                            }

                            echo '<td class="col-author">' . esc_html( get_the_author() ) . '</td>';
                            echo '<td class="col-date">' . esc_html( get_the_date( 'Y-m-d' ) ) . '</td>';
                            echo '<td class="col-status">' . $status_badge . '</td>';
                            echo '</tr>';
                        }
                        wp_reset_postdata();
                    } else {
                        echo '<tr><td colspan="5" class="text-center">등록된 문의글이 없습니다.</td></tr>';
                    }
                    ?>
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="sy-board-pagination">
                <?php
                echo paginate_links( array(
                    'base'    => str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ),
                    'format'  => '?paged=%#%',
                    'current' => max( 1, $paged ),
                    'total'   => $query->max_num_pages,
                    'prev_text' => '&larr; 이전',
                    'next_text' => '다음 &rarr;',
                ) );
                ?>
            </div>
        </div>
        <?php
    }

    return ob_get_clean();
}
