<?php

get_header();

$posts = get_posts( array(
    'numberposts' => 10,
    'category'    => 0,
    'orderby'     => 'date',
    'order'       => 'DESC',
    'include'     => array(),
    'exclude'     => array(),
    'meta_key'    => '',
    'meta_value'  => '',
    'post_type'   => 'film'
) );
?>

<section class="regular slider">

<?php foreach( $posts as $post ):
    setup_postdata($post); ?>

    <div>
        <h2 class="blog-post-title"><a href="<?php the_permalink(); ?>"> <?php the_title() ?> </a></h2>
        <?php the_content() ?>
    </div>

<?php endforeach ?>

</section>

    <script type="text/javascript">
        $(document).on('ready', function() {
        $(".regular").slick({
            dots: true,
            infinite: true,
            slidesToShow: 5,
            slidesToScroll: 2,
        });
        });
    </script>

<?php wp_reset_postdata() ?>

<?php get_footer() ?>