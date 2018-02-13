<div class="alm-btn-wrap">
    <button id="almu-load-more" class="alm-load-more-btn more"<?php
        if($roles) :
            echo ' data-roles="' . filter_var($roles, FILTER_SANITIZE_STRING) . '"';
        endif; 

        if($include) :
            echo ' data-include="' . filter_var($include, FILTER_SANITIZE_STRING) . '"';
        endif;

        if($exclude) :
            echo ' data-exclude="' . filter_var($exclude, FILTER_SANITIZE_STRING) . '"';
        endif; 

        if($order) :
            echo ' data-order="' . filter_var($order, FILTER_SANITIZE_STRING) . '"';
        endif; 

        if($orderby) :
            echo ' data-orderby="' . filter_var($orderby, FILTER_SANITIZE_STRING) . '"';
        endif; 

        if($per_page) :
            echo ' data-per-page="' . filter_var($per_page, FILTER_SANITIZE_NUMBER_INT) . '"';
        endif; 
    ?>><?php echo $button_text ?></button>
</div>