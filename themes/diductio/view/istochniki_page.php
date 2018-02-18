<div class="post_tags">
<?php 

foreach ( $tags as $tag ) {
    $tag_id = $tag->term_id;
    $tag_link = get_tag_link( $tag_id ); 

    $tag_categories = Did_Post::getTagCategoriesBy($tag_id); ?>

    <div class='tag'>
        <h1 class='entry-title'>
            <a href='<?php echo $tag_link ?>' title='<?php echo $tag->name ?> Tag' class='<?php echo $tag->slug ?>'>
                <?php echo $tag->name ?>
            </a>
        </h1>

        <div class='post-descr'><?php echo $tag->description; ?></div>

        <div class='tag_bottom'></div>

        <div class='istochiniki-rubriki'>
            <span class='label label-success'>Массивов  - <?php echo $tag->count ?></span>&nbsp;&nbsp;&nbsp;<?php

            foreach ($tag_categories as $key => $value) { ?>
                <span class="cat-links 2">
                    <a href="<?php echo $value['cat_link'] ?>">
                        <?php echo $value['cat_name']; ?>
                    </a>
                </span>
            <?php } ?>
            
        </div>
    </div>
<?php } ?>

</div>