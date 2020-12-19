<?php

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

    $out .= '
        </section>
       ';


    wp_reset_postdata();

    return $out;
}
