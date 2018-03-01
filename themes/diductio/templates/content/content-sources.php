<?php
/**
 * The template showing preview block of the source page (http://dev.diductio.ru/source)
 *
 * @package WordPress
 * @subpackage Twenty_Fifteen
 * @since Twenty Fifteen 1.0
 */
?>

<article id="tag-<?=$tag->term_id;?>" class="post type-post status-publish format-standard hentry">

	<header class="entry-header">
		<?php echo "<a href='" . get_tag_link($tag->term_id) . "'><h1 class='entry-title'>".$tag->name."</h1></a>" ?>
	</header><!-- .entry-header -->

	<div class="entry-content">
		<!-- <div class="add-to-favor-wrapper">
		    <span class="label label-success label-soft">Массивов</span>
		    <span class="label label-success"><?=$tag->count;?></span>
		</div> -->
		<?=$tag->description;?>
	<div class="public_statistic">
		
	</div>
	</div><!-- .entry-content -->
	<footer class="entry-footer">
		<div class="footer-statistic">
			<div class="stat-col">
				<span class="label label-success label-soft">Массивов</span>
		    	<span class="label label-success"><?=$tag->count;?></span>
			</div>
		</div>
		<?php

		$tag_categories = Did_Posts::getCategoriesByPostsWithCurrentTag();

		foreach ($tag_categories as $key => $value) {
			$html2 .= '<span class="cat-links 2">';
			$html2 .= '<a href="'.$value['cat_link'].'">'.$value['cat_name'].'</a>';
			$html2 .= '</span>';
		}

		echo $html2;

		?>	
	</footer>
</article><!-- #post-## -->
