<?php
/**
 *  Данная Вьюшка показывает статистику по категориям пользователя в личном и публичном кабинете
 */
?>
<!-- User Category Static  -->
<?php if($category_statistic): ?>
    <div class="public_statistic row precent-row">
        <div class="stat-col">
            <span class="label label-success label-soft">Активных</span>
            <span class="label label-success"><?=$user_statistic['in_progress'];?></span>
            <span class="label label-danger"><?=$user_statistic['overdue_tasks'];?></span>
        </div>
    <?php foreach($category_statistic as $key => $stat):?>
        <div class="stat-col">
            <span class="label label-success label-soft"><?=$key;?></span>
            <span class="label label-success"><?=$stat;?></span>
        </div>
    <?php endforeach;?>
    <?php foreach($tag_statistic as $key => $stat):?>
        <div class="stat-col">
            <span class="label label-success label-soft"><?=$key;?></span>
            <span class="label label-success "><?=$stat;?></span>
        </div>
    <?php endforeach;?>
    </div>
<?php else:?>
    <?php
        //TODO: Display error/notice if category statistic does not exist.
    ?>
<?php endif; ?>
<!-- User Category Static end -->
