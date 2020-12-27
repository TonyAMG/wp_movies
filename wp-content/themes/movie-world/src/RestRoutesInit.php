<?php



require dirname(__FILE__) . '/Core/RestRoutesClass.php';

use Core\RestRoutesClass;

class RestRoutesInit
{
    ###########################################
    ###### ПАРАМЕТРЫ ДЛЯ СИНГЛ СУЩНОСТЕЙ ######
    ###########################################

    //список разрешенных параметров для сингл сущностей
    private $rest_query_params_single = ['title', 'original_title', 'original_id'];



    #############################################
    ###### ПАРАМЕТРЫ ДЛЯ СПИСКОВ СУЩНОСТЕЙ ######
    #############################################

    //список разрешенных параметров фильтрации для списков сущностей
    private $rest_query_params_list = ['genres', 'production_countries', 'release_date', 'title', 'original_title'];
    //список разрешенных свойств параметра сортировки 'sort_by'
    private $rest_query_sort_by_list = ['title', 'original_title', 'release_date'];
    //список разрешенных свойств параметра сортировки 'order'
    private $rest_query_order_list = ['asc', 'desc'];
    //список разрешенных свойств параметра genres
    private $genres = [
        'боевик', 'комедия', 'приключения', 'драма', 'триллер', 'мультфильм', 'фэнтези',
        'семейный', 'фантастика', 'ужасы', 'мелодрама', 'криминал', 'детектив', 'военный',
        'музыка', 'история', 'документальный', 'вестерн'
    ];
    //список разрешенных свойств параметра production_countries
    private $production_countries = [
        'US', 'GB', 'JP', 'CA', 'FR', 'DE', 'CN', 'ES', 'AU', 'MX', 'KR', 'BE', 'IT', 'NZ',
        'IN', 'AR', 'HK', 'RU', 'ZA', 'DK', 'IE', 'CZ', 'NL', 'TH', 'NO', 'HU', 'BR', 'PL',
        'AE', 'BG', 'UA', 'SE', 'FI', 'CO', 'TW', 'PR', 'RO', 'AT', 'IL', 'MT', 'CL', 'SG'
    ];



    public function __construct()
    {
        //инициализация эндпоинта для получения списка сущностей (с фильтрацией, сортировкой, поиском и пагинацией)
        new RestRoutesClass(
            'mw/v1',
            '/movies/',
            false,
            $this->rest_query_params_list,
            $this->rest_query_sort_by_list,
            $this->rest_query_order_list,
            $this->genres,
            $this->production_countries
        );

        //инициализация эндпоинта для получения сингл сущности (с поиском по трем возможным парамтрам)
        new RestRoutesClass(
            'mw/v1',
            '/movie/',
            true,
            $this->rest_query_params_single
        );
    }
}