<?php

require dirname(__FILE__) . '/Core/MetaBoxClass.php';

use Core\MetaBoxClass;

class MetaBoxesInit
{
    public function __construct()
    {
        new MetaBoxClass('title', 'Title');
        new MetaBoxClass('original_title', 'Original Title');
        new MetaBoxClass('poster_path', 'Poster Image Path', 150, 'normal', 'poster');
        new MetaBoxClass('overview', 'Overview', null, 'normal', 'textarea');
        new MetaBoxClass('genres', 'Genres');
        new MetaBoxClass('release_date', 'Release Date');
        new MetaBoxClass('budget', 'Budget');
        new MetaBoxClass('revenue', 'Revenue');
        new MetaBoxClass('runtime', 'Runtime');
        new MetaBoxClass('production_countries', 'Country');
        new MetaBoxClass('original_id', 'Original ID');

    }
}