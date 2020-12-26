<?php


try {
    $movies = unserialize(file_get_contents(__DIR__ .'/movies.txt'));
} catch(Exception $e) {
    $movie_world_errors = '
        <p style="margin:20px;"><strong>AddMoviesToDb</strong>.php application error.<br>
        Can\'t find database file <strong>movies.txt</strong><br>
        This file must be in <strong><em>'.__DIR__.'</em></strong><br>
        If you do not understand what this is all about, than just comment out<br> string
        <strong>10</strong>  in <em>'.dirname(__FILE__, 2).DIRECTORY_SEPARATOR.
        '<strong>functions.php</strong></em></p>'.PHP_EOL
    ;
}


if (!(get_option( 'movies_db_status' ) === 'loaded')) {

    add_option('movies_db_status', 'loaded', '', 'no');

    for ($i = 1; $i <= 1999; $i++) {

        $movie = array_shift($movies);

        $poster_path    = preparePoster('poster_path', 'https://image.tmdb.org/t/p/w342', 'отсутствует', $movie);
        $budget         = prepareFinance('budget', 'неизвестно', $movie);
        $revenue        = prepareFinance('revenue', 'неизвестно', $movie);
        $runtime        = prepareRuntime('runtime', 'неизвестно', $movie);
        $genres         = extractArrayParam('genres', 'name', 'неизвестно', $movie);
        $countries      = extractArrayParam('production_countries', 'iso_3166_1', 'неизвестно', $movie);
        $title          = checkAvailability('title', 'неизвестно', $movie);
        $original_title = checkAvailability('original_title', 'неизвестно', $movie);
        $overview       = checkAvailability('overview', 'Описание отсутствует', $movie);
        $release_date   = checkAvailability('release_date', 'неизвестно', $movie);

        $post_arr = [
            'post_title'   => $movie['title'],
            'post_content' => '<img src="' . $poster_path . '" alt="' . $original_title . '">' . '<p>' . $overview . '</p>',
            'post_name'    => $movie['original_title'],
            'post_status'  => 'publish',
            'post_type'    => 'movie',
            'meta_input'   => [
                'title'                => $title,
                'original_title'       => $original_title,
                'poster_path'          => $poster_path,
                'overview'             => $overview,
                'genres'               => $genres,
                'release_date'         => $release_date,
                'budget'               => $budget,
                'revenue'              => $revenue,
                'runtime'              => $runtime,
                'production_countries' => $countries,
                'original_id'          => $movie['id']
            ],
        ];

        wp_insert_post($post_arr, true);
    }
}

function preparePoster($type, $prefix, $substitute_word, $response): string
{
    return (!empty($response[$type])) ? $prefix.$response[$type] : $substitute_word;
}


function checkAvailability($type, $substitute_word, $response): string
{
    return (!empty($response[$type])) ? $response[$type] : $substitute_word;
}


function extractArrayParam($type, $subtype, $substitute_word, $response): string
{
    if (empty($response[$type]))
        return $substitute_word;
    $param = '';
    foreach ($response[$type] as $key => $value) {
        $param .= $value[$subtype].' ';
    }
    return str_replace(' ', ', ', trim($param));
}


function prepareFinance($type, $substitute_word, $response): string
{
    return (!empty($response[$type])) ? '$'.number_format($response[$type], 0, "", ", ") : $substitute_word;
}


function prepareRuntime($type, $substitute_word, $response): string
{
    if (empty($response[$type]))
        return $substitute_word;
    $runtime = $response[$type];
    return $runtime.' мин. / '.floor($runtime / 60) .' ч. '. $runtime % 60 .' мин.';
}

