<?php get_header() ?>

        <?php $movie = get_post_custom() ?>
        <div class="mw-theme-movie-details-page">
            <h2 class="mw-theme-movie-title"><?=$movie['title'][0]?></h2>
            <p class="mw-theme-movie-original-title"><?=$movie['original_title'][0]?></p>
            <div class="mw-theme-movie-logo-column">
                <img src="<?=$movie['poster_path'][0]?>">
            </div>
            <div class="mw-theme-movie-details-column">
                <span class="mw-theme-movie-desc-headers">Релиз: &nbsp;</span>
                <span class="mw-theme-movie-desc"><?=$movie['release_date'][0]?></span>
                <br>
                <span class="mw-theme-movie-desc-headers">Бюджет: &nbsp;</span>
                <span class="mw-theme-movie-desc"><?=$movie['budget'][0]?></span>
                <br>
                <span class="mw-theme-movie-desc-headers">Сборы: &nbsp;</span>
                <span class="mw-theme-movie-desc"><?=$movie['revenue'][0]?></span>
                <br>
                <span class="mw-theme-movie-desc-headers">Жанр: &nbsp;</span>
                <span class="mw-theme-movie-desc"><?=$movie['genres'][0]?></span>
                <br>
                <span class="mw-theme-movie-desc-headers">Время: &nbsp;</span>
                <span class="mw-theme-movie-desc"><?=$movie['runtime'][0]?></span>
                <br>
                <span class="mw-theme-movie-desc-headers">Страна: &nbsp;</span>
                <span class="mw-theme-movie-desc"><?=$movie['production_countries'][0]?></span>
            </div>
            <div class="mw-theme-movie-overview-column">
                <?=$movie['overview'][0]?>
            </div>

        </div>

<?php get_footer() ?>

