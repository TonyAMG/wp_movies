<?php

add_shortcode('movies_gallery', 'movies_gallery_shortcode_handler');
function movies_gallery_shortcode_handler($atts)
{
    $atts = shortcode_atts([
        'numberposts' => 50,
        'orderby'     => 'rand',
        'order'       => 'DESC',
        'titles'      => 'true'
    ], $atts);

    $posts = get_posts( array(
        'numberposts' => $atts['numberposts'],
        'orderby'     => $atts['orderby'],
        'order'       => $atts['order'],
        'post_type'   => 'movie'
    ) );

    $out = '<section class="regular slider">';


    foreach ($posts as $post):
        $movie = get_post_meta( $post->ID );

        $title_raw = mb_substr($movie['title'][0], 0, 17);
        $title_nice = (mb_strlen($title_raw) > 16) ? $title_raw.'...' : $title_raw;
        $title = !($atts['titles'] === 'false') ? $title_nice : '';

        $link = get_the_permalink($post->ID);

        setup_postdata($post);
        $out .= '
            <div>
                <h2 class="blog-post-title"><a href="'.$link.'">'.$title.'</a></h2>
                <a href="'.$link.'"><img src="'.$movie['poster_path'][0].'" alt="Poster not available"></a>
            </div>
        ';
    endforeach;

    $out .= '</section>';

    wp_reset_postdata();
    return $out;
}

