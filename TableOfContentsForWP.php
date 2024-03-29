<?php
/*
Plugin Name: Table of Contents for WordPress
Description: Description: Elevate your WordPress content with an interactive Table of Contents and header links. Keep your readers engaged and help them navigate your posts seamlessly.
Version: 1.0
Author: Radosław Lewicki
Author URI: https://github.com/levy52
Domain Path: /languages
License: GPLv2 or later
*/

function enqueue_custom_assets()
{
    wp_register_script('custom-js', plugins_url('table-of-contents-for-wp/js/table-of-contents.js'));
    wp_enqueue_script('custom-js');

    wp_register_style('custom-css', plugins_url('table-of-contents-for-wp/css/style.css'));
    wp_enqueue_style('custom-css');
}

add_action('wp_enqueue_scripts', 'enqueue_custom_assets');

function add_table_of_contents_to_post_content($content)
{
    if (is_singular('post')) {
        $dom = new DOMDocument;
        $dom->loadHTML('<?xml encoding="UTF-8>' . $content, LIBXML_NOERROR);

        $h2_elements = $dom->getElementsByTagName('h2');

        foreach ($h2_elements as $index => $h2) {
            $h2_content = $h2->nodeValue;
            $id = sanitize_title($h2_content);
            $h2->setAttribute('id', $id);
        }

        $new_content = '<div class="post-content">' . $dom->saveHTML() . '</div>';
        return $new_content;
    } else {
        return $content;
    }
}

add_filter('the_content', 'add_table_of_contents_to_post_content');

function generate_table_of_contents()
{
    global $post;
    $content = $post->post_content;

    $dom = new DOMDocument;
    $dom->loadHTML('<?xml encoding="UTF-8>' . $content, LIBXML_NOERROR);

    $h2_elements = $dom->getElementsByTagName('h2');

    if ($h2_elements->length === 0) {
        return '';
    }
    $toc_list = '<div class="table-of-contents__wrapper">';
    $toc_list .= '<ul class="table-of-contents">';
    $toc_list .= '<p class="table-of-contents__title">' . __('Table of contents', $domain = 'levy52') . '</p>';

    foreach ($h2_elements as $index => $h2) {
        $h2_content = $h2->nodeValue;
        $id = sanitize_title($h2_content);
        $h2->setAttribute('id', $id);

        $toc_list .= '<li><a href="#' . $id . '">' . $h2_content . '</a></li>';
    }

    $toc_list .= '</ul>';
    $toc_list .= '</div>';

    return $toc_list;
}
