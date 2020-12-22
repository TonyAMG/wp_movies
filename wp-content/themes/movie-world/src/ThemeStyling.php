<?php

add_action('wp_enqueue_scripts', 'movie_world_scripts');
function movie_world_scripts()
{
    wp_enqueue_style('bootstrap','//stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.css');
    wp_enqueue_style('theme-style', get_stylesheet_uri());
    wp_enqueue_style('movie-details-page', get_template_directory_uri() . '/assets/css/movie-details-page.css');
    wp_enqueue_style('slick-slider-style', get_template_directory_uri() . '/assets/slick-slider/css/slick.css');
    wp_enqueue_style('slick-slider-theme', get_template_directory_uri() . '/assets/slick-slider/css/slick-theme.css');
    wp_enqueue_script('slick-slider', '//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.js', ['jquery']);
    wp_enqueue_script('slider-init', get_template_directory_uri() . '/assets/slick-slider/js/slider-init.js', ['slick-slider'], null, true);
}