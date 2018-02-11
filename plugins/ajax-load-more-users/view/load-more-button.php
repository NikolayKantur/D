<div class="alm-btn-wrap">
    <button id="almu-load-more" class="alm-load-more-btn more"<?php
        if($roles) :
            echo ' data-roles="' . filter_var($roles, FILTER_SANITIZE_STRING) . '"';
        endif; 

        if($include) :
            echo ' data-include="' . filter_var($include, FILTER_SANITIZE_STRING) . '"';
        endif; 
    ?>><?php echo $button_text ?></button>
</div>