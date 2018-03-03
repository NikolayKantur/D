<?php
/**
 * The template for displaying all single posts and attachments
 *
 * @package    WordPress
 * @subpackage Twenty_Fifteen
 * @since      Twenty Fifteen 1.0
 */
global $post, $dUser, $st, $dPost;

$post_statistic = $st->get_course_info($post->ID);
$post_statistic['total_progress'] = Did_Posts::getAllUsersProgress($post->ID);
$post_statistic['overdue_users'] = count(Did_Posts::getOverDueUsers($post->ID));

$active_users = $post_statistic['active_users'];
$done_users = $post_statistic['done_users'];

if ($active_users) {
    $act_args = array(
        'include' => $active_users,
    );
    $active_users_array = new WP_User_Query($act_args);
}

// suggest users
$suggestUser = new Did_SuggestUser();
if (is_user_logged_in()) {
    $suggesting_users = $suggestUser->getSuggestingUsers(get_current_user_id(), $post->ID);
}

$current_user_id = get_current_user_id();
$posts_users = $st->get_users_by_post($post->ID);

get_header(); ?>

<div id="primary" class="content-area">
    
	<div id="statistic" class="hentry">
	
	<div id="user-activity" class="row">
		
		<?php 
        foreach ( $posts_users as $user ){
			if ( $user['user_id'] == $current_user_id ){
				$current_user_progress = $user['progress'];
                break;
			}
		}
		
		// Estimated progress
		$estimated_progress = Did_User::getEstimatedProgressForPost($post->ID);
		$estimated_progress_class = '';
		
		if (isset($post_statistic['users_started'][$current_user_id])) {
			
            // Hide estimated progress if user completed all tasks
			if ($estimated_progress > 0 && $current_user_progress < 100 ) {
				$prefix_word = 'Ещё';
				
				if ($estimated_progress === 100) {
					$prefix_word = 'Уже';
					$estimated_progress_class = 'progress-bar-danger progress-bar-striped';
				}
				?>
				<div class="col-sm-6 col-md-6">
					<div>
						<span>Мой расчетный прогресс</span>
						<span class="passing_date"><?= $prefix_word . ' '
							. $st::ru_months_days($countdown->days) ?></span>
					</div>
					<div class="progress">
						<div class="progress-bar <?= $estimated_progress_class; ?>" role="progressbar"
							 aria-valuenow="<?= $estimated_progress; ?>"
							 aria-valuemin="0" aria-valuemax="100" style="width: <?= $estimated_progress; ?>%;">
							<?= $estimated_progress; ?> %
						</div>
					</div>
				</div>
				<?php
			} 
		}
		
		$end = count($posts_users) >= 2 ? 2 : count($posts_users);

        $showed_ids = [];
		for ($i = 0; $i < $end; $i++):
            if(in_array($posts_users[$i]['user_id'], $showed_ids)) {
                continue;
            }
            
			$passing_date = $dPost->get_passing_info_by_post($posts_users[$i]['user_id'], $post->ID);
			?>
			<div class="col-sm-6 col-md-6">
				<div>
					<a href="<?= $posts_users[$i]['user_link']; ?>">
						<?= $posts_users[$i]['avatar']; ?>
						<span><?= $posts_users[$i]['username']; ?></span>
					</a>
					<span class="passing_date"><?= $passing_date['date_string']; ?></span>
				</div>
				<div class="progress">
					<div class="progress-bar " role="progressbar"
						 aria-valuenow="<?= $posts_users[$i]['progress']; ?>" aria-valuemin="0" aria-valuemax="100"
						 style="width:<?= $posts_users[$i]['progress']; ?>%;">
						<?= $posts_users[$i]['progress']; ?> %
					</div>
				</div>
			</div>
		<?php 
            $showed_ids[] = $posts_users[$i]['user_id'];

        endfor; ?>

			<div class="row">
				<div class="col-md-4 col-md-offset-8 more-users">
				<?php do_action('single-after-stat-row') ?>
					<?php if (count($posts_users) > 2): ?>
					<a id="display-more-users" class="link-style-2" href="javascript:void(0);">
						Развернуть
					</a>
					<?php endif; ?>
				</div>
			</div>
		<?php if (count($posts_users) > 2): ?>
			<div class="rest-users" style="display: none;">
				<?php for ($i = 2; $i < count($posts_users); $i++):
					$passing_date = $dPost->get_passing_info_by_post($posts_users[$i]['user_id'], $post->ID);
					?>
					<div class="col-sm-6 col-md-6">
						<div>
							<a href="<?= $posts_users[$i]['user_link']; ?>">
								<?= $posts_users[$i]['avatar']; ?>
								<span><?= $posts_users[$i]['username']; ?></span>
							</a>
							<span class="passing_date"><?= $passing_date['date_string']; ?></span>
						</div>
						<div class="progress">
							<div class="progress-bar " role="progressbar"
								 aria-valuenow="<?= $posts_users[$i]['progress']; ?>" aria-valuemin="0"
								 aria-valuemax="100" style="width:<?= $posts_users[$i]['progress']; ?>%;">
								<?= $posts_users[$i]['progress']; ?> %
							</div>
						</div>
					</div>
				<?php endfor; ?>
			</div>
		<?php endif; ?>
	
	</div>
	
	</div>
	
	
    <main id="main" class="site-main" role="main">

        <?php
        // Start the loop.
        while (have_posts()) : the_post();
            
            /*
             * Include the post format-specific template for the content. If you want to
             * use this in a child theme, then include a file called called content-___.php
             * (where ___ is the post format) and that will be used instead.
             */
            get_template_part('content', get_post_format());
            
            // If comments are open or we have at least one comment, load up the comment template.
            if (comments_open() || get_comments_number()) :
                comments_template('/comments-short.php');
            endif;
            
            // Previous/next post navigation.
            the_post_navigation(array(
                'next_text' => '<span class="meta-nav" aria-hidden="true">' . __('Next', 'diductio') . '</span> ' .
                    '<span class="screen-reader-text">' . __('Next post:', 'diductio') . '</span> ' .
                    '<span class="post-title">%title</span>',
                'prev_text' => '<span class="meta-nav" aria-hidden="true">' . __('Previous',
                        'diductio') . '</span> ' .
                    '<span class="screen-reader-text">' . __('Previous post:', 'diductio') . '</span> ' .
                    '<span class="post-title">%title</span>',
            ));
            
            // End the loop.
        endwhile;
        ?>
    
    </main><!-- .site-main -->
</div><!-- .content-area -->

<?php get_footer(); ?>
