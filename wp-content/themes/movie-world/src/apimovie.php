<?php


add_filter('the_content', 'apimovie');
function apimovie($the_content){
    return $the_content;
}

function wl_movies($params){

    $original_id = json_decode($params->get_param('original_id'));

    function queryArgument($param, $key){
        if(is_object($param)){
            if ($param->lt && $param->gt){
                return [
                    [
                        'key'=>$key,
                        'value'=>[$param->gt, $param->lt],
                        'type'=>'NUMERIC',
                        'compare'=>'BETWEEN'
                    ]
                ];
            }
        }

        if($param){
            return [
                [
                    'key'=>$key,
                    'value'=> $param,
                    'type'=> 'NUMERIC',
                    'compare'=>'='
                ]
            ];
        }
        return null;
    }

    $per_page = ($_REQUEST['per_page']);

    $args = [
        'posts_per_page'=> ($_REQUEST['per_page'] ? $_REQUEST['per_page'] : 10),
        'suppress_filters'=>false,
        'post_type'=>'movie',
        'paged' => ($_REQUEST['page'] ? $_REQUEST['page'] : 1),

        'meta_query' => [
            [
                'key' => 'genres',
                'value' => $_REQUEST['genre'],
                'compare' => 'LIKE'
            ]
        ]
        //'meta_query'=> queryArgument($original_id, 'original_id')
    ];

    $posts = new WP_Query($args);


    $data = [];
    $i = 0;

    foreach ($posts->posts as $post){

        $movie = get_post_custom($post->ID);

        foreach ($movie as $key => $value)
            $data[$i][$key] = $value[0];

        $i++;
    }

    return $data;
}





function wl_movie($slug){
    $argc = [
        'name'=>$slug['slug'],
        'post_type'=>'movie'
    ];
    $post = get_posts($argc);
    $movie = get_post_meta($post[0]->ID);

    foreach ($movie as $key => $value)
        $data[$key] = $value[0];

    return $data;
}




add_action('rest_api_init', function (){
    register_rest_route('wl/v1', 'movies', [
        'method'=>'GET',
        'callback'=>'wl_movies',
    ]);
    register_rest_route('wl/v1', 'movie/(?P<slug>[a-zA-Z0-9-]+)', [
        'methods'=>'GET',
        'callback'=>'wl_movie',
    ]);
});