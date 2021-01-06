<?php


namespace Core;


class RestRoutesClass
{
    private $rest_namespace;
    private $rest_route;
    private $single_endpoint;
    private $rest_query_params_list;
    private $rest_query_sort_by_list;
    private $rest_query_order_list;
    private $genres;
    private $production_countries;

    public function __construct(
        $rest_namespace,
        $rest_route,
        $single_endpoint = true,
        $rest_query_params_list = null,
        $rest_query_sort_by_list = null,
        $rest_query_order_list = null,
        $genres = null,
        $production_countries = null
    )
    {
        $this->rest_namespace = $rest_namespace;
        $this->rest_route = $rest_route;
        $this->single_endpoint = $single_endpoint;
        $this->rest_query_params_list = $rest_query_params_list;
        $this->rest_query_sort_by_list = $rest_query_sort_by_list;
        $this->rest_query_order_list = $rest_query_order_list;
        $this->genres = $genres;
        $this->production_countries = $production_countries;

        add_filter('wp_is_application_passwords_available', '__return_true');
        add_action('rest_api_init', [$this, 'registerRestRouteForMovie']);
        add_action('shutdown', [$this, 'time_elapsed']);
    }

    public function registerRestRouteForMovie()
    {
        $args_movies_rest_route = [
            'methods'             => 'GET',
            'callback'            => [$this, 'getResult'],
            'permission_callback' => '__return_true'
        ];
        register_rest_route($this->rest_namespace, $this->rest_route, $args_movies_rest_route);
    }


    public function getResult(): array
    {
        $is_single = $this->single_endpoint;

        $page     = (!$is_single) ? $this->validateIntegerParams('page', 1, 1, 200) : null;
        $per_page = (!$is_single) ? $this->validateIntegerParams('per_page', 20, 1, 200) : null;
        $meta_key = (!$is_single) ? $this->validateParams('sort_by', 'release_date') : null;
        $order    = (!$is_single) ? $this->validateParams('order', 'desc') : null;
        $orderby  = (!$is_single) ? 'meta_value' : null;

        $query_args = [
            'posts_per_page'   => $per_page,
            'paged'            => $page,
            'orderby'          => $orderby,
            'order'            => $order,
            'meta_key'         => $meta_key,
            'post_type'        => 'movie',
            'meta_query'       => $this->metaQuery()
        ];

        if (!$is_single) {
            //быстрый подготовительный запрос с 1 возвращаемым постом для эндпоинта списка сущностей
            //для предварительного анализа и коррекции параметров основного запроса
            $preparing_query_args                   = $query_args;
            $preparing_query_args['paged']          = 1;
            $preparing_query_args['posts_per_page'] = 1;
            $preparing_query = new \WP_Query($preparing_query_args);

            //рассчет крайней возможнуой страницы с результатами запроса
            $max_page_num = (int) ceil($preparing_query->found_posts / $per_page);

            //врезка в основной запрос и генерация поля с ошибкой, если пользователь
            //ввел некорректный номер страницы, для возможности автоматического
            //перенаправления на крайнюю возможную страницу с результатами запроса
            if ($page > $max_page_num) {

                $error = 'You have requested page number ' . $page . ', but your current query conditions ' .
                         'have ' . $max_page_num . ' page(s) of results, so you have been automatically ' .
                         'redirected to page ' . $max_page_num;

                $query_args['paged'] = $max_page_num;
                $page                = $max_page_num;

            }
        }

        if (!$is_single || ($query_args['meta_query'] != null && $is_single)) {
            //основной запрос в базу данных
            $posts = new \WP_Query($query_args);

            foreach ($posts->posts as $post) {
                $movie_raw = get_post_custom($post->ID);
                foreach ($movie_raw as $key => $value) {
                    $movie[$key] = $value[0];
                }
                $movies_raw[] = $movie;
            }
        }

        //генерация конечного результата для JSON, если фильм(ы) найден(ы)
        if (!empty($movies_raw)) {
            $movies = [
                'status'                  => 'found',
                'execution_time'          =>  $this->time_elapsed(),
            ];
            //добавляем подсказку, если есть не критическая ошибка запроса
            if (!empty($error))
                $movies += ['query_error' => $error];

            //генерация результата при успешном нахождении фильм(а/ов)
            if (!$is_single) {
                $movies += [
                    'movies_found'        =>  $preparing_query->found_posts,
                    'max_page_number'     =>  $max_page_num,
                    'page'                =>  $page,
                    'per_page'            =>  $per_page,
                    'movies_on_page'      =>  $posts->post_count,
                ];
            }
            $movies += [
                 'result'                 =>  $movies_raw
            ];

        //генерация конечного результата для JSON, если фильм(ы) невозможно найти
        } else {
            $movies = [
                'status'                  =>  'not found',
                'execution_time'          =>  $this->time_elapsed()
            ];
            //подсказка пользователю для сингл сущности
            if ($is_single) {
                $hint = 'You can request a movie using one of these three parameters '.
                        '\'original_id\', \'title\' or \'original_title\', '.
                        'so if you know at least one of them, try again. Do not use all three '.
                        'together, try one by one.';
                $movies += ['hint'        =>  $hint];
            //подсказка пользователю для списка сущностей
            } else {
                $hint = 'You can try to soften up your query parameters. For example, remove '.
                        'one, or more filtering parameters (\'genres\', \'production_countries\', '.
                        '\'release_date\', \'title\', \'original_title\') if you use them in your query.';
                $movies += ['hint'        =>  $hint];
            }
        }
        return $movies;
    }

    //время выполнения запроса в секундах (float)
    public function time_elapsed(): float
    {
        return round(microtime(true) - $_SERVER['REQUEST_TIME'], 5);
    }

    //метод формирует и обслуживает все запросы по кастомным мета-полям
    private function metaQuery(): ?array
    {
        $compare_type = $this->single_endpoint ? '=' : 'LIKE';

        foreach ($_REQUEST as $key => $value){
            if (in_array($key, $this->rest_query_params_list)){
                $requested_meta_params[$key] = $_REQUEST[$key];
            }
        }

        $this->validateMetaParams($requested_meta_params);

        if (!empty($requested_meta_params)) {
            foreach ($requested_meta_params as $key => $value)
                    $meta_queries[] = [
                        'key' => $key,
                        'value' => $value,
                        'compare' => $compare_type,
                    ];
        }
        return $meta_queries ?? null;
    }


    //автоматическая валидация данных мета-параметров при наличии
    //списков разрешенных значений параметров;
    //проверка наличия и подстановка данных списков выполняется автоматически
    private function validateMetaParams(&$requested_meta_params)
    {
        if (!empty($requested_meta_params)){

            foreach ($requested_meta_params as $key => $value) {
                if (!empty($this->{$key})) {
                    if (in_array($_REQUEST[$key], $this->{$key})) {
                        $validated_meta_params[$key] = $value;
                    }
                } else {
                    $validated_meta_params[$key] = sanitize_text_field($value);
                }
            }

            if (!empty($validated_meta_params))
                $requested_meta_params = $validated_meta_params;

        }
    }

    //метод вадидации для параметров запроса через WP_Query
    //проверка наличия и подстановка списков валидации выполняется автоматически
    //списки задаются в классе инициализации RestRoutesInit.php
    //при именовании списки должны соответствовать шаблону: $rest_имяпараметра_list
    //где "имяпараметра" должно соотвествовать названию мета-поля, например $rest_query_sort_by_list
    private function validateParams($param_name, $param_default_value): string
    {
        if (!empty($_REQUEST[$param_name])) {
            $variable_list_name = 'rest_query_'.$param_name.'_list';
            if(!empty($this->$variable_list_name)){
                $meta_key = (in_array($_REQUEST[$param_name], $this->$variable_list_name))
                    ? $_REQUEST[$param_name]
                    : $param_default_value;
            }
        } else {
            $meta_key = $param_default_value;
        }
        return $meta_key;
    }


    //метод валидации числовых параметров ($page, $per_page)
    private function validateIntegerParams($param_name, $default, $min, $max): int
    {
        $options = ['options' => [
            'default' => $default,
            'min_range' => $min,
            'max_range' => $max
        ]];

        if (!empty($_REQUEST[$param_name]))
            return filter_var($_REQUEST[$param_name], FILTER_VALIDATE_INT, $options);

        return $default;
    }

}

