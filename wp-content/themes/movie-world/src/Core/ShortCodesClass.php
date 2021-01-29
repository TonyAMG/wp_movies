<?php


namespace Core;


class ShortCodesClass
{
    private $show_tiles;
    private $titles_max_length;
    private $titles_max_length_cjk;
    private $movies_number;
    private $order_by;
    private $order;


    public function __construct(
        $show_tiles = 'true',
        $titles_max_length = 17,
        $titles_max_length_cjk = 10,
        $movies_number = 50,
        $order_by = 'rand',
        $order = 'desc'
    )
    {
        $this->show_tiles = $show_tiles;
        $this->titles_max_length = $titles_max_length;
        $this->titles_max_length_cjk = $titles_max_length_cjk;
        $this->movies_number = $movies_number;
        $this->order_by = $order_by;
        $this->order = $order;

        add_shortcode('movies_gallery', [$this, 'movies_gallery_shortcode_handler']);
    }


    public function movies_gallery_shortcode_handler($atts): string
    {
        $atts = shortcode_atts([
            'movies_number'    => $this->movies_number,
            'order_by'         => $this->order_by,
            'order'            => $this->order,
            'show_titles'      => $this->show_tiles,
            'show_date_rating' => $this->show_tiles
        ], $atts);

        $movies = get_posts([
            'numberposts' => $atts['movies_number'],
            'orderby'     => $atts['order_by'],
            'order'       => $atts['order'],
            'post_type'   => 'movie'
        ]);

        $out = '<section class="regular slider">';

        foreach ($movies as $movie_post) {

            $movie = get_post_meta($movie_post->ID);

            $title  = $this->titlePrepare(
                $movie['title'][0],
                $this->titles_max_length,
                $this->titles_max_length_cjk, $atts
            );
            $poster = $movie['poster_path'][0];
            $link   = get_the_permalink($movie_post->ID);
            $date   = $this->datePrepare($movie['release_date'][0], 'неизвестно');
            $vote   = '<b>' . $movie['vote_average'][0] . '</b> [ ' . $movie['vote_count'][0] . ' ] ';

            $date_rating = ($atts['show_date_rating'] === 'true')
                ? '<div class="movie-info-bottom">
                       <span class="date">' . $date . '</span>
                       <span class="budget">' . $vote . '</span>
                   </div>'
                : null ;

            $out .= '
            <div>
                <h2 class="blog-post-title"><a href="' . $link . '" target="_blank">' . $title . '</a></h2>
                <a href="' . $link . '" target="_blank"><img src="' . $poster . '" alt="Poster not available"></a>
                '. $date_rating .'
            </div>
            ';
        }

        $out .= '</section>';

        return $out;
    }


    //делаем красивые заголовки:
    //* отсекаем лишнюю длину (для иероглифов отсекаем еще больше)
    //* добавляем '...' в конце усеченных заголовков
    //* отключаем заголовки полностью, если в шорткоде был параметр show_titles="false"
    private function titlePrepare($title, $max_length, $max_length_cjk_characters, $atts): ?string
    {
        if ($atts['show_titles'] === 'false')
            return null;

        $chinese  = '\p{Han}+';
        $japanese = '\x{4E00}-\x{9FBF}\x{3040}-\x{309F}\x{30A0}-\x{30FF}';
        $korean   = '\x{3130}-\x{318F}\x{AC00}-\x{D7AF}';

        //определяем если заголовок на китайском, японском, или корейском
        if(preg_match("/([$japanese]|[$korean]|$chinese)/u", $title))
            $max_length = $max_length_cjk_characters;

        //обрезаем до указанной длины, добавляем '...' если нужно
        $title_raw = mb_substr($title, 0, $max_length);
        return (mb_strlen($title_raw) > ($max_length - 1)) ? $title_raw . '...' : $title_raw;
    }


    //делаем красивую дату
    private function datePrepare($date_raw, $substitute_word): string
    {
        if (empty($date_raw))
            return $substitute_word;
        return preg_replace('/(\d{4})-(\d{2})-(\d{2})/', '<b>$1</b>-$2-$3', $date_raw);
    }

}