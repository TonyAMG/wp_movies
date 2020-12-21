<?php get_header() ?>

<?php query_posts(['post_type' => 'movie']) ?>
    <div class="container">
    <div class="row">
    <div class="col-sm-8 blog-main">

<div class="blog-post">
    <h2 class="blog-post-title"><?php the_title() ?></a></h2>
<p class="blog-post-meta"><?php the_date() ?> <a href="#"><?php the_author() ?></a></p>
<?php the_content() ?>

    <hr class="new1">
</div>

    </div>
        <span class="film-desc-headers">Режиссер:</span>
        <span class="film-desc">
            <?php echo get_post_meta( get_the_ID(), 'director', true ) ?>
        </span>
        <br>
        <span class="film-desc-headers">Год выпуска:</span>
        <span class="film-desc">
            <?php echo get_post_meta( get_the_ID(), 'release_year', true ) ?>

    </div>
<?php get_footer() ?>