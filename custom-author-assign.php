<?php
/*
Plugin Name: Author on Pages
Plugin URI: https://github.com/cnoid/page-author-wp/
Description: Adds Author capability to Pages, which includes metadata to Yoast SEO. Then prints it under "Author Widget". Also prints "Last Modified" Date.
Version: 0.3.6
Author: Mimmikk.
Author URI: https://github.com/cnoid/
*/

// Prevent direct file access
defined('ABSPATH') or die('bro what are you doin??');

define('AP2A_PATH', plugin_dir_path(__FILE__));
define('AP2A_URL', plugin_dir_url(__FILE__));
define('AP2A_URL', plugins_url('', __FILE__));


// Include required files
require_once(AP2A_PATH . 'inc/functions.php');


// Enqueue scripts and styles for admin area
add_action('admin_enqueue_scripts', 'ap2a_admin_enqueue_scripts');
function ap2a_admin_enqueue_scripts() {
    wp_enqueue_style('ap2a_admin_styles', AP2A_URL . 'css/style.css');
    wp_enqueue_script('ap2a_admin_scripts', AP2A_URL . 'js/script.js', array('jquery'), '1.0.0', true);
}

// Enqueue scripts and styles for front-end
add_action('wp_enqueue_scripts', 'ap2a_public_enqueue_scripts');
function ap2a_public_enqueue_scripts() {
    wp_enqueue_style('ap2a_public_styles', AP2A_URL . 'css/style.css');
}

//Gather and print last modified
function print_last_modified_date() {
    global $post;
    $modified_date = get_the_modified_date('F j, Y', $post->ID); 
    return 'Last updated: ' . $modified_date;
}
