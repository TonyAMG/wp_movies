<?php

function register_film_post_type()
{
    $args = array(
        'public'        => true,
        'label'         => 'Film',
        'labels'        => [
            'add_new_item' => 'Add New Film',
            'edit_item'    => 'Edit Film'
        ],
        'has_archive'   => 'true'
    );
    register_post_type('film', $args);
}
add_action('init', 'register_film_post_type');

function register_director_metabox()
{
    add_meta_box(
        'film_director',
        'Film Director',
        'render_director_metabox',
        'film',
        'side'
    );
}
add_action('add_meta_boxes', 'register_director_metabox');

function render_director_metabox($post)
{
    $value = get_post_meta($post->ID, 'director', true);
    ?>
    <label for="director">Director</label>
    <input name="director" id="director" type="text" min="2" max="30" value="<?=$value?>">
    <?php
}

function save_director_metabox($post_id)
{
    if (array_key_exists('director', $_POST)) {
        update_post_meta(
            $post_id,
            'director',
            $_POST['director']
        );
    }
}
add_action('save_post', 'save_director_metabox');





function register_release_year_metabox()
{
    add_meta_box(
        'release_year',
        'Year',
        'render_release_year_metabox',
        'film',
        'side'
    );
}
add_action('add_meta_boxes', 'register_release_year_metabox');

function render_release_year_metabox($post)
{
    $value = get_post_meta($post->ID, 'release_year', true);
    ?>
    <label for="release_year">Year</label>
    <input name="release_year" id="release_year" type="text" max="4" value="<?=$value?>">
    <?php
}

function save_release_year_metabox($post_id)
{
    if (array_key_exists('release_year', $_POST)) {
        update_post_meta(
            $post_id,
            'release_year',
            $_POST['release_year']
        );
    }
}
add_action('save_post', 'save_release_year_metabox');