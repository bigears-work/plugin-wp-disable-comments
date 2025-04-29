<?php
/**
Plugin Name: WP Disable Comments
Description: Removes WordPress comment post type across posts, pages, and media and removes reference to them in admin interface.
Version: 0.1
Author: Big Ears Webagentur
Author URI: https://bigears.work
License: GPL2
*/

// Disable support for comments and trackbacks
function bigears_disable_comments_post_types() {
    foreach (get_post_types() as $post_type) {
        if (post_type_supports($post_type, 'comments')) {
            remove_post_type_support($post_type, 'comments');
            remove_post_type_support($post_type, 'trackbacks');
        }
    }
}
add_action('admin_init', 'bigears_disable_comments_post_types');

// Close comments on the front-end
add_filter('comments_open', '__return_false');
add_filter('pings_open', '__return_false');

// Hide existing comments
add_filter('comments_array', '__return_empty_array', 10, 2);

// Remove comments page in admin menu
function bigears_disable_comments_admin_menu() {
    remove_menu_page('edit-comments.php');
}
add_action('admin_menu', 'bigears_disable_comments_admin_menu');

// Redirect any user trying to access comments page
function bigears_disable_comments_admin_redirect() {
    global $pagenow;
    if ($pagenow === 'edit-comments.php') {
        wp_redirect(admin_url());
        exit;
    }
}
add_action('admin_init', 'bigears_disable_comments_admin_redirect');

// Remove comments metabox from dashboard
function bigears_disable_comments_dashboard() {
    remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');
}
add_action('admin_init', 'bigears_disable_comments_dashboard');

// Remove comments link from admin bar
function bigears_disable_comments_admin_bar() {
    if (is_admin_bar_showing()) {
        global $wp_admin_bar;
        $wp_admin_bar->remove_node('comments');
    }
}
add_action('admin_bar_menu', 'bigears_disable_comments_admin_bar', 999);