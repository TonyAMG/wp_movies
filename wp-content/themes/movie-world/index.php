<?php get_header() ?>
<?php echo $movie_world_errors ?? '' ?>
<?php echo do_shortcode('[movies_gallery]') ?>
<?php echo do_shortcode('[movies_gallery]') ?>
<div class="container">
    <div class="row">
        <div class="col-sm-8 blog-main">

            <?php
            if (have_posts()) {
                while (have_posts()) {
                    the_post();
                    get_template_part('content');
                }
            }
            ?>

            <nav>
                <ul class="pager">
                    <li><?php previous_posts_link('Назад')?></li>
                    <li><?php next_posts_link('Вперед')?></li>
                </ul>
            </nav>
        </div><!-- /.blog-main -->
        <?php get_sidebar() ?>
    </div><!-- /.row -->
<?php get_footer() ?>

