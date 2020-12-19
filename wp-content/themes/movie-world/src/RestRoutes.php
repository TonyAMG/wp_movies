<?php

add_action('rest_api_init', 'register_movies_rest_route');
function register_movies_rest_route() {
    $args_movies_rest_route = [
        'methods'             => 'GET',            // метод запроса: GET, POST ...
        'callback'            => 'get_movies_rest_api',  // функция обработки запроса. Должна вернуть ответ на запрос
        'permission_callback' => '__return_true'
    ];
    register_rest_route('movies/v1','/popular/', $args_movies_rest_route);

}
function get_movies_rest_api( WP_REST_Request $request ) {

    $movies = get_posts( array(
        'numberposts' => 10,
        'category'    => 0,
        'orderby'     => 'date',
        'order'       => 'DESC',
        'post_type'   => 'film',
        'suppress_filters' => true, // подавление работы фильтров изменения SQL запроса
    ) );

    //$movies = ["foo" => "bar", "one" => "two"];

    $response["movies"] =  $movies;

    return $response;
}