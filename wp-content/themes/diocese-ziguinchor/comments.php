<?php
/**
 * Comments (list + form), included via comments_template() from single.php.
 * Per-comment "like"/"share" buttons from the source template are dropped:
 * they have no backing data or JS in the source template (see DECISIONS.md).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( post_password_required() ) {
	return;
}

if ( ! function_exists( 'dz_comment_list_item' ) ) {
	/**
	 * wp_list_comments() callback rendering one comment/reply.
	 */
	function dz_comment_list_item( $comment, $args, $depth ) {
		?>
		<div id="comment-<?php comment_ID(); ?>" <?php comment_class( $depth > 1 ? 'comment-box reply' : 'comment-box' ); ?>>
			<div class="comment-wrapper">
				<div class="avatar-wrapper">
					<?php echo get_avatar( $comment, 48 ); ?>
				</div>
				<div class="comment-content">
					<div class="comment-header">
						<div class="user-info">
							<h4><?php comment_author(); ?></h4>
							<span class="time-badge">
								<i class="bi bi-clock"></i>
								<?php echo esc_html( get_comment_date() ); ?>
							</span>
						</div>
					</div>

					<div class="comment-body">
						<?php if ( '0' === $comment->comment_approved ) : ?>
							<p class="comment-awaiting-moderation"><?php esc_html_e( 'Votre commentaire est en attente de modération.', 'diocese-ziguinchor' ); ?></p>
						<?php endif; ?>
						<?php comment_text(); ?>
					</div>

					<div class="comment-actions">
						<?php
						comment_reply_link(
							array_merge(
								$args,
								array(
									'depth'      => $depth,
									'reply_text' => '<i class="bi bi-chat"></i><span>' . esc_html__( 'Répondre', 'diocese-ziguinchor' ) . '</span>',
									'before'     => '<span class="action-btn reply-btn">',
									'after'      => '</span>',
								)
							)
						);
						?>
					</div>
				</div>
			</div>
		<?php
	}
}
?>

<section id="blog-comments" class="blog-comments section">
	<div class="container" data-aos="fade-up" data-aos-delay="100">
		<div class="blog-comments-4">
			<div class="comments-header">
				<h3 class="title"><?php esc_html_e( 'Avis des lecteurs', 'diocese-ziguinchor' ); ?></h3>
				<div class="comments-stats">
					<span class="count"><?php echo (int) get_comments_number(); ?></span>
					<span class="label"><?php esc_html_e( 'Commentaires', 'diocese-ziguinchor' ); ?></span>
				</div>
			</div>

			<?php if ( have_comments() ) : ?>
				<div class="comments-container">
					<?php
					wp_list_comments(
						array(
							'style'    => 'div',
							'callback' => 'dz_comment_list_item',
						)
					);
					?>
				</div>

				<?php if ( get_comment_pages_count() > 1 ) : ?>
					<?php the_comments_navigation(); ?>
				<?php endif; ?>
			<?php endif; ?>
		</div>
	</div>
</section>

<?php if ( comments_open() ) : ?>
	<section id="blog-comment-form" class="blog-comment-form section">
		<div class="container" data-aos="fade-up" data-aos-delay="100">
			<?php
			$dz_commenter = wp_get_current_commenter();
			comment_form(
				array(
					'title_reply'          => __( 'Partagez votre avis', 'diocese-ziguinchor' ),
					'title_reply_before'   => '<div class="section-header"><h3 id="reply-title" class="comment-reply-title">',
					'title_reply_after'    => '</h3><p>' . esc_html__( 'Votre adresse e-mail ne sera pas publiée. Les champs obligatoires sont indiqués avec *', 'diocese-ziguinchor' ) . '</p></div>',
					'comment_notes_before' => '<div class="row gy-3">',
					'comment_notes_after'  => '',
					'fields'               => array(
						'author' => '<div class="col-md-6 form-group"><label for="author">' . esc_html__( 'Nom complet', 'diocese-ziguinchor' ) . ' *</label><input type="text" name="author" class="form-control" id="author" value="' . esc_attr( $dz_commenter['comment_author'] ) . '" required></div>',
						'email'  => '<div class="col-md-6 form-group"><label for="email">' . esc_html__( 'Adresse e-mail', 'diocese-ziguinchor' ) . ' *</label><input type="email" name="email" class="form-control" id="email" value="' . esc_attr( $dz_commenter['comment_author_email'] ) . '" required></div>',
						'url'    => '<div class="col-12 form-group"><label for="url">' . esc_html__( 'Site web', 'diocese-ziguinchor' ) . '</label><input type="url" name="url" class="form-control" id="url" value="' . esc_attr( $dz_commenter['comment_author_url'] ) . '"></div>',
					),
					'comment_field'        => '<div class="col-12 form-group"><label for="comment">' . esc_html__( 'Votre commentaire', 'diocese-ziguinchor' ) . ' *</label><textarea class="form-control" name="comment" id="comment" rows="5" required></textarea></div>',
					'submit_field'         => '<div class="col-12 text-center">%1$s %2$s</div></div>',
					'class_submit'         => 'btn-submit',
					'label_submit'         => __( 'Publier le commentaire', 'diocese-ziguinchor' ),
					'format'               => 'html5',
				)
			);
			?>
		</div>
	</section>
<?php endif; ?>
