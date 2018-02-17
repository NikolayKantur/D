<?php 
     
    /*
        Задаем условие при котором будем менять шаблон главной страницы.
        Если пользователь залогинен, показываем шаблон (page-progress).
        Если пользователь НЕ залогинен, показываем главный шаблон (main-page). 
    */
     
     if( is_user_logged_in() ){
         
        include 'templates/template-progress.php';
        
     }else{
         
        include 'main-page.php';
        
     }
     
 ?>