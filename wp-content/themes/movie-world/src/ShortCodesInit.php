<?php

require dirname(__FILE__) . '/Core/ShortCodesClass.php';

use Core\ShortCodesClass;

class ShortCodesInit
{
    public function __construct()
    {
        new ShortCodesClass('true', 17, 10, 50, 'rand', 'asc');
    }
}