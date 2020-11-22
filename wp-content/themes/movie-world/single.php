<?php get_header() ?>
    <div class="container">
    <div class="row">
    <div class="col-sm-8 blog-main">
    <div class="blog-post">
        <h2 class="blog-post-title"><?php the_title() ?></h2>
        <p class="blog-post-meta"><?php the_date() ?> <a href="#"><?php the_author() ?></a></p>
        <?php the_content() ?>
    </div>
    </div>
<?php get_sidebar() ?>
    </div>
<?php comments_template() ?>
<?php get_footer() ?>