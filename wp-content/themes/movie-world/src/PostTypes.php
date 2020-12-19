<?php

add_action('init', 'register_film_post_type');
function register_film_post_type()
{
    $args = array(
        'public'        => true,
        'label'         => 'Film',
        'labels'        => [
            'add_new_item' => 'Add New Film',
            'edit_item'    => 'Edit Film'
        ],
        'has_archive'   => true,
        'rewrite'      => true
    );
    register_post_type('film', $args);
}