<?php
/**
 * My progress view
 * Вьюшка отвечающая за отображение Блока "мой прогресс" с активными задачами в сайдбаре
 */

$st = (new Did_Statistic)->oldStatisticClass;
?>

<ul>
<!-- My progress view  -->
<?php if(is_user_logged_in()): ?>
    <li>
        <a href="/progress">
            Мой прогресс
            <div style='float: right; margin-right: 0;' class='stat-col'>
                <span class='label label-success label-soft label-short' data-toggle="tooltip" data-placement="top" title="Активных"><?=$user_statistic['in_progress'];?></span>
                <?php if($user_statistic['overdue_tasks'] > 0): ?>
                    <span class="label label-danger label-short" data-toggle="tooltip" data-placement="top" title="Просроченных"><?=$user_statistic['overdue_tasks'];?></span>
                <?php endif; ?>
            </div>
        </a>
    </li> 
    
<?php $i = 0; foreach ($knowledges as $knowledge) :            
    $added_by = Did_Statistic::addedBy($knowledge->ID, $user_ID);

    // Выводим фактический прогресс
    $actual_progress = $st->get_user_progress_by_post($knowledge->ID, $user_ID);
    $estimated_progress = Did_User::getEstimatedProgressForKnowledge($knowledge); ?>
    
    <li class="widget-my-project-list">
        <div>
            <div class="stat-col margin-right-none">
                <span class="<?php if($estimated_progress > $actual_progress){echo("label label-danger label-short");}else{echo("label label-success label-soft label-short");} ?>" data-toggle="tooltip" data-placement="bottom" data-original-title="Фактический прогресс">
                    <?= $actual_progress; ?>
                </span>    
            </div>  
            
            <div class="stat-col">
                <span class="label label-success label-soft label-short" data-toggle="tooltip" data-placement="bottom" data-original-title="Рассчётный прогресс">
                    <?= $estimated_progress; ?>  
                </span>    
            </div>            
               
            <a class="link-style-1" href="<?php echo (get_permalink($knowledge->ID)); ?>" title="<?=$knowledge->post_title;?>">
                <?php 
                    if($knowledges[$i]->post_status == "private"){
                        echo ("Личное: $knowledge->post_title");
                    }else{
                        echo ("$knowledge->post_title");
                    }
                    $i++;
                ?>    
            </a>
            
        </div>
        
        <?php if ($added_by && $added_by->ID != $user_ID): ?>
            <div class="progress-on">
                Добавил:
                <a href="<?= get_site_url(); ?>/people/<?= $added_by->user_nicename ?>">
                    <?=$added_by->display_name?>
                </a>
            </div>
        <?php endif; ?>
        
    </li>
    
<?php endforeach; ?>
    
    <!-- Navigation  -->
    <li class='row'>
        <div class='col-xs-3 col-md-3 col-sm-3'><a style="font-size: 15px" class="link-style-1" href='/progress'>Всё</a></div>
        <div class='col-xs-5 col-md-5 col-sm-5'><a style="font-size: 15px" class="link-style-1" href='/wp-admin/profile.php'>Настройки</a></div>
        <div style='text-align: right;' class='col-xs-4 col-md-4 col-sm-4 logout'><?=wp_loginout(false, 0);?></div>
    </li>
        
    <!-- Navigation end -->
    <?php else: ?>
        <li><a class="link-style-3" href="<?=wp_registration_url();?>">Регистрация</a></li>
        <li class="logout"><?=wp_loginout(false, 0);?></li>
    <?php endif; ?>
        
</ul>