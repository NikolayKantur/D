<?php
/**
 * Вьюшка отвечающая за отоброжение информации о пользователе: имя, занятость,
 * количество активных и просроченных массивов, сколько еще будет занят
 */
?>
<div class="personal-area row">
    <div class="avatar">
        <div class="image"><?=get_avatar($author_info->user_email, 96); ?></div>
        <div class="person-desription">
            <?php if($author_info->inner_passing_rating > 0): ?>
                <span class="label <?php if($author_info->inner_passing_rating > 99):?>label-success<?php else:?>label-danger<?php endif;?> single_stat" data-toggle="tooltip" data-placement="top" title="Общая оценка системы"><?=$author_info->inner_passing_rating;?>%</span>
            <?php endif; ?>
            
            <h1 class="entry-title inline">
                <?php if($enable_link): ?>
                    <a class="personal-area-link"  href="<?= get_site_url(); ?>/people/<?= $author_info->user_nicename ?>">
                        <?=$author_info->data->display_name;?>
                    </a>
                <?php else: ?>
                    <?=$author_info->data->display_name;?>
                <?php endif; ?>
            </h1>
            <?php if($will_busy_days):?><span class="inline">, занят еще <?=$will_busy_days;?></span><?php endif;?>
            <div class="about"><?=get_user_meta($author_info->ID, 'description')[0]; ?></div>
            <div class="user-categories">
                <?php diductio_view('user-category-static', compact('user_statistic','category_statistic', 'author_info', 'tag_statistic')); ?>
            </div>
        </div>
    </div>
</div>
