<?php

add_action('wp_enqueue_scripts', 'movie_world_scripts');
function movie_world_scripts()
{
    wp_enqueue_style('bootstrap', get_template_directory_uri() . '/assets/bootstrap/bootstrap.css');
    wp_enqueue_style('theme-style', get_stylesheet_uri());
    wp_enqueue_style('slick-slider', get_template_directory_uri() . '/assets/slick-slider/slick.css');
    wp_enqueue_style('slick-slider-theme', get_template_directory_uri() . '/assets/slick-slider/slick-theme.css');
    wp_enqueue_script('jquery-slick', get_template_directory_uri() . '/assets/slick-slider/jquery-2.2.0.min.js');
    wp_enqueue_script('slick-slider', get_template_directory_uri() . '/assets/slick-slider/slick.js');
}


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


add_action('add_meta_boxes', 'register_director_metabox');
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
function render_director_metabox($post)
{
    $value = get_post_meta($post->ID, 'director', true);
    ?>
    <label for="director">Director</label>
    <input name="director" id="director" type="text" min="2" max="30" value="<?=$value?>">
    <?php
}


add_action('save_post', 'save_director_metabox');
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



add_action('add_meta_boxes', 'register_release_year_metabox');
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
function render_release_year_metabox($post)
{
    $value = get_post_meta($post->ID, 'release_year', true);
    ?>
    <label for="release_year">Year</label>
    <input name="release_year" id="release_year" type="text" max="4" value="<?=$value?>">
    <?php
}

add_action('save_post', 'save_release_year_metabox');
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


add_shortcode('films_gallery', 'films_gallery_shortcode_handler');
function films_gallery_shortcode_handler($atts)
{
    $atts = shortcode_atts([
            'numberposts' => 10,
            'orderby'     => 'date',
            'order'       => 'DESC'
    ], $atts);

    $posts = get_posts( array(
        'numberposts' => $atts['numberposts'],
        'orderby'     => $atts['orderby'],
        'order'       => $atts['order'],
        'post_type'   => 'film'
    ) );

    $out = '<section class="regular slider">';

    foreach ($posts as $post):
        setup_postdata($post);

        $out .= '<div>
                <h2 class="blog-post-title"><a href="'.get_the_permalink($post->ID).'"> '.get_the_title($post->ID).' </a></h2>
                '.get_the_content().'
        </div>';

    endforeach;

    $out .= '</section>

    <script type="text/javascript">
        $(document).on(\'ready\', function() {
        $(".regular").slick({
            dots: true,
            infinite: true,
            slidesToShow: 5,
            slidesToScroll: 2,
        });
        });
    </script>';


    wp_reset_postdata();

    return $out;
}
