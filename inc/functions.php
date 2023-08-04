<?php

function ap2a_add_author_meta_box() {
    add_meta_box(
        'author_meta_box', // id
        'Page Author', // title
        'ap2a_display_author_meta_box', // callback
        'page', // screen
        'side', // context
        'high' // priority
    );
}
add_action('add_meta_boxes', 'ap2a_add_author_meta_box');

function ap2a_display_author_meta_box($post) {
    // Fetch all users with roles 'author' and 'editor'
    $args = array(
        'role__in' => ['author', 'editor']
    );
    $users = get_users($args);
    // Get the current page author
    $current_author = get_post_meta($post->ID, 'ap2a_page_author', true);
    // Nonce for security
    wp_nonce_field('ap2a_save_author_meta_box', 'ap2a_author_meta_box_nonce');

    // Dropdown for selecting user
    echo '<select id="ap2a_page_author" name="ap2a_page_author">';
    echo '<option value="">Select Author</option>';
    foreach($users as $user) {
        echo '<option value="' . esc_attr($user->ID) . '" ' . selected($current_author, $user->ID, false) . '>' . esc_html($user->display_name) . '</option>';
    }
    echo '</select>';
}

function ap2a_save_author_meta_box($post_id) {
    // Verify nonce
    if (!isset($_POST['ap2a_author_meta_box_nonce']) || !wp_verify_nonce($_POST['ap2a_author_meta_box_nonce'], 'ap2a_save_author_meta_box')) {
        return $post_id;
    }
    // If this is an autosave, our form has not been submitted, so we don't want to do anything.
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return $post_id;
    }
    // Check the user's permissions.
    if (!current_user_can('edit_page', $post_id)) {
        return $post_id;
    }
    if (!empty($_POST['ap2a_page_author'])) {
        update_post_meta($post_id, 'ap2a_page_author', sanitize_text_field($_POST['ap2a_page_author']));
    } else {
        delete_post_meta($post_id, 'ap2a_page_author');
    }
}
add_action('save_post', 'ap2a_save_author_meta_box');


// Assign author to Yoast
function ap2a_assign_author_to_yoast($post_id) {
    $author_id = get_post_meta($post_id, 'ap2a_page_author', true);
    if (!empty($author_id)) {
        // Assign this author to Yoast SEO
        update_post_meta($post_id, '_yoast_wpseo_metadesc', 'Author: ' . get_the_author_meta('display_name', $author_id));
    }
}
add_action('save_post', 'ap2a_assign_author_to_yoast');


// Author widget
class AP2A_Author_Widget extends WP_Widget {
    function __construct() {
        parent::__construct(
            'ap2a_author_widget',
            __('Page Author', 'ap2a'),
            array('description' => __('Displays the assigned page author information.', 'ap2a'))
        );
    }
    public function widget($args, $instance) {
        global $post;
        $author_id = get_post_meta($post->ID, 'ap2a_page_author', true);
        if (!empty($author_id)) {
            $author_url = get_author_posts_url($author_id);
            $avatar_url = get_avatar_url($author_id);
            $linkedin_url = get_user_meta($author_id, 'linkedin', true); //social media links
            echo $args['before_widget'];
            echo '<div class="ap2a-author-widget">';
            echo '<img src="' . esc_url($avatar_url) . '" alt="Author Avatar">';
            echo '<div class="author-details">';
            echo '<p>Author:</p>';
            echo '<p><a href="' . esc_url($author_url) . '"><strong>' . esc_html(get_the_author_meta('display_name', $author_id)) . '</strong></a> | <a href="' . esc_url($linkedin_url) . '" class="linkedin author-social"><i class="thb-icon-linkedin"></i></a></p>';
    
            // Last modified Date
            if (function_exists('print_last_modified_date')) {
                echo '<p id="lastmodified">' . print_last_modified_date() . '</p>';
            }
    
            echo '</div>'; // End author-details
            echo '</div>'; // End ap2a-author-widget
            echo $args['after_widget'];
        }
    }      
}
// Register the widget
function ap2a_register_widgets() {
    register_widget('AP2A_Author_Widget');
}
add_action('widgets_init', 'ap2a_register_widgets');

