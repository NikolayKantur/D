<?php
/**
 * The template used for displaying content in the peoples page (each user view)
 *
 * @package WordPress
 * @subpackage Twenty_Fifteen
 * @since Twenty Fifteen 1.0
 */

if(!isset($user)) {
    global $user;
}

?>
<div class="col-md-12 peoples-row">
    <?php 
    diductio_view(
        'people.single-row', 
        Did_Views::getParamsForView('people.single-row', array(
            'user' => $user
        ))
    );
    ?>
</div>
