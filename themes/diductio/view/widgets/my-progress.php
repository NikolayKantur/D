<?php
/**
 * My progress view
 * Вьюшка отвечающая за отображение Блока "мой прогресс" с активными задачами в сайдбаре
 */
?>
<ul>
    <!-- My progress view  -->
    <li>
        <a href="/progress">
            Мой прогресс
            <div style='float: right; margin-right: 0;' class='stat-col'>
                <span class='label label-success label-soft'><?=$user_statistic['in_progress'];?></span>
                <span style='margin-left: 5px;' class='label label-success label-soft'><?=$percent;?> %</span>
            </div>
        </a>
    </li>
    <?php foreach ($knowledges as $knowledge):
        $pass_info = $GLOBALS['dPost']->get_passing_info_by_post($user_ID, $knowledge->ID);
        $link = get_permalink($knowledge->ID) . get_first_unchecked_lesson($knowledge->ID);
        ?>
    <li class="widget-my-project-list">
        <div>
            <a class="link-style-1" href="<?=$link;?>"><?=$knowledge->post_title;?></a>
        </div>
        <?php if($pass_info['undone_title']):
            $stoped_on = $GLOBALS['dPost']->get_accordion_element_title($knowledge->ID, $pass_info['first_undone']);
            ?>
            <div class="progress-on">На этапе: <?=$stoped_on;?></div>
        <?php endif; ?>
    </li>
    <?php endforeach; ?>
    <!-- Navigation  -->
    <li class='row'>
        <div class='col-xs-6 col-md-6 col-sm-6'><a href='/wp-admin/profile.php'>Мой профиль</a></div>
        <div style='text-align: right;' class='col-xs-6 col-md-6 col-sm-6'><?=wp_loginout(false, 0);?></div>
    </li>
    <!-- Navigation end -->
</ul>
