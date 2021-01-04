<?php

//если данные уже есть в базе, просто завершаем скрипт
if (get_option( 'movies_db_status' ) === 'loaded')
    return;

//устанавливаем максимальное время выполнения скрипта - 10 минут,
//чтобы наверняка успели завершиться все операции
ini_set('max_execution_time', 600);

//локации основного файла с данными 2000 фильмов
$locations  = [
    'https://mvc-project.online/movies.txt',
    'http://ec2-18-219-233-220.us-east-2.compute.amazonaws.com/wpr/movies.txt',
    __DIR__ . '/movies.txt'
];

//локации опциального пака с постерами для 2000 фильмов
$posters_pack_locations = [
    'https://mvc-project.online/Posters.zip',
    'http://ec2-18-219-233-220.us-east-2.compute.amazonaws.com/wpr/Posters.zip',
    __DIR__ . '/Posters.zip'
];


$movies = getDataForDB($locations, $movie_world_errors);


//если файл базы успешно загружен и фильмы еще не добавлены в базу WordPress,
//запускаем процесс добавления, может длиться несколько минут
if (!(get_option( 'movies_db_status' ) === 'loaded') && $movies) {

    if (add_option('movies_db_status', 'loaded', '', 'no')) {

        //пытаемся получить пак с постерами
        $is_posters_local = getPostersForDB($posters_pack_locations);

        foreach ($movies as $movie) {

            $poster_path    = preparePoster('poster_path', 'https://image.tmdb.org/t/p/w342', 'отсутствует', $is_posters_local, $movie);
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
}






//генерация URL для постера
function preparePoster($type, $prefix, $substitute_word, $is_posters_local, $response): string
{
    //TODO : generate poster image (nice grey gradient) substitute with title text
    if ($is_posters_local)
        return wp_upload_dir()['baseurl'] . '/posters/' . $response['id'] . '_poster.jpg';
    return (!empty($response[$type])) ? $prefix.$response[$type] : $substitute_word;
}


//если какой-то параметр пуст (null) добавляем в базу WP замещающее слово, например 'неизвестно'
function checkAvailability($type, $substitute_word, $response): string
{
    return (!empty($response[$type])) ? $response[$type] : $substitute_word;
}


//форматируем массив с данными для красивого вывода
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


//форматируем финансы для красивого вывода
function prepareFinance($type, $substitute_word, $response): string
{
    return (!empty($response[$type])) ? '$'.number_format($response[$type], 0, "", ", ") : $substitute_word;
}


//форматируем время для красивого вывода
function prepareRuntime($type, $substitute_word, $response): string
{
    if (empty($response[$type]))
        return $substitute_word;
    $runtime = $response[$type];
    return $runtime.' мин. / '.floor($runtime / 60) .' ч. '. $runtime % 60 .' мин.';
}


//скачиваем файл базы данных из возможных указанных локаций
//и извлекаем из него данные в массив
function getDataForDB($db_locations, &$movie_world_errors)
{
    foreach ($db_locations as $location) {
        if (is_file_exists($location))
            return unserialize(file_get_contents($location));
    }

    $movie_world_errors = '
        <p style="margin:20px;"><strong>AddMoviesToDb</strong>.php application error.<br>
        Can\'t find database file <strong>movies.txt</strong><br>
        This file must be in <strong><em>' . __DIR__ . '</em></strong><br>
        If you do not understand what this is all about, than just comment out<br> string
        <strong>12</strong>  in <em>' . dirname(__FILE__, 2) . DIRECTORY_SEPARATOR .
                          '<strong>functions.php</strong></em></p>' . PHP_EOL
    ;

    return false;
}


//пытаемся скачать и применить пак постеров для фильмов (2 000 штук)
//если на этом этапе что-то пошло не так, не беда, скрипт это учтет
//и при добавленеии в базу данных WordPress будет добавлена ссылка
//на удаленную версию постеров, вместо локальной
function getPostersForDB($posters_pack_locations, $posters_dir = '/posters/', $posters_pack_name = 'Posters.zip')
{
    $uploads_dir = wp_upload_dir()['basedir'];

    foreach ($posters_pack_locations as $posters_pack_location) {
        if (is_file_exists($posters_pack_location)) {
            if (file_put_contents($uploads_dir . '/' . $posters_pack_name, file_get_contents($posters_pack_location)) === false)
                return false;
            break;
        }
    }

    //пытаемся извлечь скачанный архив с постерами
    if (class_exists('ZipArchive')) {
        $zip = new ZipArchive;
        if ($zip->open( $uploads_dir . '/' . $posters_pack_name) === true) {
            $zip->extractTo($uploads_dir);
            $zip->close();
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}


//проверка на существование удаленного/локального файла
function is_file_exists($file_location)
{
    try {
        return (file_get_contents($file_location, false, null, 0, 1)) ? true : false;
    } catch (Exception $e) {
        null;
    }
}