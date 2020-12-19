<?php

add_action('wp_enqueue_scripts', 'movie_world_scripts');
function movie_world_scripts()
{
    wp_enqueue_style('bootstrap', get_template_directory_uri() . '/assets/bootstrap/bootstrap.css');
    wp_enqueue_style('theme-style', get_stylesheet_uri());
    wp_enqueue_style('slick-slider-style', get_template_directory_uri() . '/assets/slick-slider/css/slick.css');
    wp_enqueue_style('slick-slider-theme', get_template_directory_uri() . '/assets/slick-slider/css/slick-theme.css');
    wp_enqueue_script('slick-slider', get_template_directory_uri() . '/assets/slick-slider/js/slick.js', ['jquery']);
    wp_enqueue_script('slider-init', get_template_directory_uri() . '/assets/slick-slider/js/slider-init.js', ['slick-slider'], null, true);
}