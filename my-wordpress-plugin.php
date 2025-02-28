<?php
/*
Plugin Name: AA NEWS LIST
Description: A simple plugin that displays a list of news items with dates.
Version: 1.1
Author: Your Name
*/

// Enqueue the plugin stylesheet
function my_plugin_enqueue_styles() {
    wp_enqueue_style('my-plugin-styles', plugin_dir_url(__FILE__) . 'style.css');
}
add_action('wp_enqueue_scripts', 'my_plugin_enqueue_styles');

// Include the file that creates the database table
require_once plugin_dir_path(__FILE__) . 'create-news-table.php';

// Function to display the news list
function display_news_list() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'epadel_news';

    // Fetch news items from the database in descending order by id
    $news_items = $wpdb->get_results("SELECT date, news FROM $table_name ORDER BY id DESC", ARRAY_A);

   // Generate the HTML for the news list
   $output = '<div class="news-list-container" style="max-height: 200px; overflow-y: auto;"><ul class="no-bullets">';
   foreach ($news_items as $item) {
    $output .= '<li><div class="news-date">' . esc_html($item['date']) . '</div>';
    $output .= '<div class="news-item"><strong>' . esc_html($item['news']) . '</strong></div></li>';
   }
   $output .= '</ul></div>';

   return $output;
}

// Register the shortcode
function register_news_list_shortcode() {
    add_shortcode('news_list', 'display_news_list');
}
add_action('init', 'register_news_list_shortcode');

// Register the activation hook to create the database table
register_activation_hook(__FILE__, 'create_news_table');
?>