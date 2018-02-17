<?php
/*
 * Template Name: Шаблон "Мои подписчики"
 * Данный шаблон страницы выводит всех пользователей на сайте
*/
global $wp_roles;

$author = $user_info = get_query_var('username') ? get_user_by('slug', get_query_var('username')) : wp_get_current_user();
$roles = array();
foreach ($wp_roles->roles as $rKey => $rvalue) {
    $roles[] = $rKey;
}
$my_subscribers = array_map(function ($author) {
    return $author['ID'];
}, Did_User::getAllMySubscribers($author->ID));

$count_args  = array(
    'role__in'      => $roles,
    'fields'    => 'all_with_meta',
    'number'    => 999999,
    'include' => $my_subscribers
);
$user_count_query = new WP_User_Query($count_args);
$user_count = $user_count_query->get_results();
// count the number of users found in the query
$total_users = $user_count ? count($user_count) : 1;
// grab the current page number and set to 1 if no page number is set
$page = max(1,get_query_var('paged'));

// how many users to show per page
$users_per_page = 80;

$total_pages = 1;
$offset = $users_per_page * ($page - 1);
$total_pages = ceil($total_users / $users_per_page);

$args  = array(
    // search only for Authors role
    'role__in'      => $roles,
    'number'    => $users_per_page,
    'offset'    => $offset,
    'include' => $my_subscribers
);
// Create the WP_User_Query object
$user_query = new WP_User_Query($args);

get_header(); ?>

<div id="primary" class="content-area">
    <?php do_action('progress-subscribers-header'); ?>
    <main id="main" class="site-main" role="main">
        <article id="users-page" class="page type-page status-publish hentry">
            <header class="entry-header">
                <h1 class="entry-title">Подписчики</h1>
                <div class="entry-content all-users">
                    
                    <?php
                    // User Loop
                    if ( ! empty( $user_query->results ) ) {
                        foreach ( $user_query->results as $user ) {
                            get_template_part( 'templates/content/content', 'peoples' );
                        }
                    } else {
                        echo 'No users found.';
                    }
                    ?>
                </div>
            
            </header>
        </article>
        <?php
        // grab the current query parameters
        $query_string = $_SERVER['QUERY_STRING'];
        
        // The $base variable stores the complete URL to our page, including the current page arg
        
        // if in the admin, your base should be the admin URL + your page
        // $base = 'http://5.178.82.26/lyudi/' . remove_query_arg('p', $query_string) . '%_%';
        $slug = basename(get_permalink());
        $paginate_url =  home_url()."/{$slug}/page/";
        // if on the front end, your base is the current page
        //$base = get_permalink( get_the_ID() ) . '?' . remove_query_arg('p', $query_string) . '%_%';
        
        $page_args =  array(
            'base' => str_replace( $big = 999999999, '%#%', $paginate_url.$big ), // the base URL, including query arg
            'format' => '&p=%#%', // this defines the query parameter that will be used, in this case "p"
            
            'prev_text' => __('&laquo; Previous'), // text for previous page
            'next_text' => __('Next &raquo;'), // text for next page
            'total' => $total_pages, // the total number of pages we have
            'current'  => $page,
            'end_size' => 1,
            'mid_size' => 5,
        );
        ?>
        <nav class="navigation pagination custom-page-wrapper" role="navigation">
            <div class="nav-links custom-pagination">
                <?php
                echo paginate_links( $page_args);
                ?>
            </div>
        </nav>
    </main><!-- .site-main -->
</div><!-- .content-area -->

<?php get_footer(); ?>
