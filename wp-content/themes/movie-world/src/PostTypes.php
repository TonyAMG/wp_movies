<?php

add_action('init', 'register_movie_post_type');
function register_movie_post_type()
{
    $args = array(
        'public'        => true,
        'label'         => 'Movie',
        'labels'        => [
            'add_new_item' => 'Add New Movie',
            'edit_item'    => 'Edit Movie'
        ],
        'has_archive'   => true,
        'rewrite'      => true,
        'show_in_rest' => true,
        "supports" => false
    );
    register_post_type('movie', $args);
}
