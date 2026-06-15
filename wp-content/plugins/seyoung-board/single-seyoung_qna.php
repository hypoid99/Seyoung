<?php
/**
 * The template for displaying a single 1:1 Q&A post.
 *
 * @package Seyoung
 */

get_header();

global $post;
$current_user_id = get_current_user_id();
$post_author_id = $post->post_author;
$is_author = ( $current_user_id && $current_user_id == $post_author_id );
$is_admin = current_user_can( 'manage_options' );
$is_private = ( $post->post_status === 'private' );

// double safety check for private access
if ( $is_private && ! $is_author && ! $is_admin ) {
    echo '<div class="sy-board-message">🔒 비밀글로 설정된 문의입니다. 작성자 본인 및 관리자만 열람할 수 있습니다.</div>';
    get_footer();
    exit;
}
?>

<div class="sy-qna-detail-wrap" style="background-color: #FFFFFF; border: 1px solid #E5E0D8; border-radius: 6px; padding: 30px; margin: 40px auto; max-width: 800px; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.02); font-family: 'Outfit', 'Noto Sans KR', sans-serif; color: #3E2723;">
    
    <!-- 1. Question Header -->
    <div class="sy-qna-detail-header" style="border-bottom: 2px solid #5D4037; padding-bottom: 20px; margin-bottom: 25px;">
        <span class="sy-badge <?php echo $is_private ? 'status-waiting' : 'status-replied'; ?>" style="margin-bottom: 10px; padding: 4px 10px; background-color: <?php echo $is_private ? '#F5F1EA' : '#5D4037'; ?>; color: <?php echo $is_private ? '#8D6E63' : '#FFFFFF'; ?>; border-radius: 4px; font-size: 11px; font-weight: 700; display: inline-block;">
            <?php echo $is_private ? '🔒 비밀글 문의' : '🔓 공개글 문의'; ?>
        </span>
        <h2 class="sy-qna-detail-title" style="font-size: 24px; font-weight: 800; margin: 10px 0; color: #3E2723;"><?php the_title(); ?></h2>
        <div class="sy-qna-meta" style="font-size: 13px; color: #8D6E63; display: flex; gap: 20px;">
            <span>작성자: <strong><?php the_author(); ?></strong></span>
            <span>작성일: <strong><?php echo get_the_date( 'Y-m-d H:i' ); ?></strong></span>
        </div>
    </div>

    <!-- 2. Question Body -->
    <div class="sy-qna-detail-content" style="font-size: 15px; line-height: 1.8; color: #3E2723; min-height: 150px; padding-bottom: 30px; border-bottom: 1px solid #E5E0D8;">
        <?php the_content(); ?>
    </div>

    <!-- 3. Reply / Comments Section -->
    <div class="sy-qna-replies-section" style="margin-top: 35px;">
        <h3 style="font-size: 18px; font-weight: 700; margin-bottom: 20px; color: #3E2723; display: flex; align-items: center; gap: 8px;">
            💬 답변 내용
        </h3>
        
        <?php
        $comments = get_comments( array(
            'post_id' => $post->ID,
            'status'  => 'approve',
            'order'   => 'ASC',
        ) );

        if ( ! empty( $comments ) ) {
            foreach ( $comments as $comment ) {
                $comment_author_is_admin = user_can( $comment->user_id, 'manage_options' );
                ?>
                <div class="sy-qna-reply-card" style="background-color: <?php echo $comment_author_is_admin ? '#F5F1EA' : '#FAF8F5'; ?>; border: 1px solid <?php echo $comment_author_is_admin ? '#5D4037' : '#E5E0D8'; ?>; border-radius: 6px; padding: 20px; margin-bottom: 15px; position: relative;">
                    <div class="reply-header" style="font-size: 12px; font-weight: 700; color: #5D4037; display: flex; justify-content: space-between; margin-bottom: 10px;">
                        <span>
                            <?php echo $comment_author_is_admin ? '🛡️ 세영건재 관리자 답변' : '👤 ' . esc_html( $comment->comment_author ); ?>
                        </span>
                        <span style="font-weight: normal; color: #8D6E63;">
                            <?php echo esc_html( $comment->comment_date ); ?>
                        </span>
                    </div>
                    <div class="reply-body" style="font-size: 14px; line-height: 1.6; color: #3E2723;">
                        <?php echo wpautop( esc_html( $comment->comment_content ) ); ?>
                    </div>
                </div>
                <?php
            }
        } else {
            echo '<div style="background-color: #FAF8F5; border: 1px solid #E5E0D8; padding: 25px; text-align: center; border-radius: 6px; font-size: 14px; color: #8D6E63; margin-bottom: 25px;">';
            echo '🕒 답변 대기 중입니다. 관리자가 검토 후 빠른 시일 내에 답변해 드리겠습니다.';
            echo '</div>';
        }
        ?>
    </div>

    <!-- 4. Admin Reply Form -->
    <?php if ( $is_admin ) : ?>
        <div class="sy-admin-reply-form" style="margin-top: 35px; background-color: #FAF8F5; border: 1px dashed #5D4037; border-radius: 6px; padding: 25px;">
            <h4 style="font-size: 15px; font-weight: 700; margin-top: 0; margin-bottom: 15px; color: #5D4037;">🛠️ 관리자 답변 작성</h4>
            <form action="<?php echo esc_url( site_url( 'wp-comments-post.php' ) ); ?>" method="post" id="qna-reply-form" style="display: flex; flex-direction: column; gap: 12px;">
                <textarea name="comment" id="comment" rows="5" required placeholder="고객 문의에 대한 답변 내용을 입력하세요." style="border: 1px solid #E5E0D8; border-radius: 4px; padding: 12px; font-size: 14px; outline: none; background-color: #FFFFFF;"></textarea>
                <input type="hidden" name="comment_post_ID" value="<?php echo esc_attr( $post->ID ); ?>" id="comment_post_ID">
                <input type="hidden" name="comment_parent" id="comment_parent" value="0">
                
                <div style="display: flex; justify-content: flex-end;">
                    <button type="submit" class="sy-btn sy-btn-primary" style="background-color: #5D4037; color: #FFFFFF; padding: 10px 20px; font-size: 13px; font-weight: 600; border: none; border-radius: 4px; cursor: pointer; transition: background-color 0.2s;">답변 등록</button>
                </div>
            </form>
        </div>
    <?php endif; ?>

    <!-- 5. Footer Navigation -->
    <div class="sy-qna-detail-footer" style="margin-top: 30px; border-top: 1px solid #E5E0D8; padding-top: 20px; display: flex; justify-content: center;">
        <a href="<?php echo esc_url( home_url( '/qna-board/' ) ); ?>" class="sy-btn sy-btn-secondary" style="border: 2px solid #3E2723; color: #3E2723; padding: 10px 24px; text-decoration: none; font-size: 14px; font-weight: 600; border-radius: 4px; text-align: center; transition: all 0.2s;">
            &larr; 문의 목록으로
        </a>
    </div>

</div>

<?php
get_footer();
?>
