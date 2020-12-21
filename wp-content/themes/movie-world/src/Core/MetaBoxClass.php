<?php


namespace Core;


class MetaBoxClass
{
    private $id;
    private $title;
    private $input_size;
    private $context;
    private $html;
    private $html_code;


    public function __construct(
        $id,
        $title,
        $input_size = '50',
        $context = 'normal',
        $html = 'default',
        $html_code = null
    )
    {
        $this->id = $id;
        $this->title = $title;
        $this->input_size = $input_size;
        $this->context = $context;
        $this->html = $html;
        $this->html_code = $html_code;

        add_action('add_meta_boxes', [$this, 'registerMetaBoxForMovie']);
        add_action('save_post', [$this, 'saveMetaBoxForMovie']);
        add_action('rest_api_init', [$this, 'registerRestMetaBoxForMovie']);
    }


    public function registerMetaBoxForMovie()
    {
        add_meta_box(
            $this->id,
            $this->title,
            [$this, 'renderMetaBoxForMovie'],
            'movie',
            $this->context
        );
    }


    public function renderMetaBoxForMovie($post)
    {
        $value = get_post_meta($post->ID, $this->id, true);

        switch ($this->html) {
            case 'default':
                echo '<input name="'.$this->id.'" type="text" min="2" max="100" size="'.$this->input_size.'" value="'.$value.'">';
                break;
            case 'textarea':
                echo '<textarea id="overview" name="overview" rows="10" cols="70">'.$value.'</textarea>';
                break;
            case 'poster':
                echo '<input name="'.$this->id.'" type="text" size="'.$this->input_size.'" min="2" max="200" value="'.$value.'"><br>';
                echo '<br><img src="'.$value.'" alt="Poster not available">';
                break;
            case 'custom':
                echo $this->html_code;
        }

    }


    public function saveMetaBoxForMovie($post_id)
    {
        if (array_key_exists($this->id, $_POST)) {

            $post_data = ($this->html === 'textarea')
                ? sanitize_textarea_field($_POST[$this->id])
                : sanitize_text_field($_POST[$this->id]);

            update_post_meta(
                $post_id,
                $this->id,
                $post_data
            );
        }
    }


    public function registerRestMetaBoxForMovie()
    {
        register_rest_field(
            'movie',
            $this->id,
            [
                'get_callback'    => [$this, 'getMovieMetaBox'],
                'update_callback' => null,
                'schema'          => null,
            ]
        );
    }

    public function getMovieMetaBox()
    {
        return get_post_meta( get_the_ID(), $this->id, true );
    }
}