<?php
$Did_Categories = new Did_Categories();
?>
<div class="stat-col">
    <div class="add-to-favor-wrapper">
                <span class="wpfp-span">
                    <a id="suggest-to-user" data-toggle="modal" data-target="#suggestUser">Добавить</a>
                </span>
    </div>
</div>

<!-- Modal -->
<div class="modal fade bs-example-modal-lg" id="suggestUser" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Добавить людям</h4>
            </div>
            <div class="modal-body all-users">
                <div class="row">
                    <?php if($suggesting_users): ?>
                        <?php foreach ($suggesting_users as $user):
                            $user_id = $user->ID;

                            $category_statistic = $Did_Categories->getCategoryStatistic($user_id);
                            $tag_statistic = $Did_Categories->getTagStatistic($user_id);
                            
                            $user_statistic = $st->get_user_info($user_id);
                            $user_statistic['author'] = Did_User::getAllMyPosts($user_id);

                            $viewParams = Did_Views::getParamsForView('people.single-row', array(
                                'user_id' => $user_id,
                                'author_info' => get_userdata($user_id),
                                'category_statistic' => $category_statistic,
                                'user_statistic' => $user_statistic,
                                'tag_statistic' => $tag_statistic,
                            ));

                            ?>
                            <div class="col-md-12">
                                <label style="display: block;" for="user-<?=$user->ID;?>">
                                    <div class="col-md-1 user-selecting">
                                        <input <?php if($user->is_selected): ?> checked="checked" disabled="disabled" <?php endif;?> id="user-<?=$user->ID;?>" data-user="<?=$user->ID;?>" class="suggested-user" type="checkbox" value="test">
                                    </div>
                                    <?php diductio_view('people.single-row', $viewParams); ?>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="modalMessage">Нет взаимных подписок</div>
                    <?php endif;?>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
                <button type="button" class="btn btn-primary">Сохранить</button>
            </div>
        </div>
    </div>
</div>