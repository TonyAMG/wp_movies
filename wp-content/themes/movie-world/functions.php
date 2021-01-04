<?php

require dirname(__FILE__) . '/src/ErrorHandler.php';
require dirname(__FILE__) . '/src/ThemeStyling.php';
require dirname(__FILE__) . '/src/PostTypes.php';
require dirname(__FILE__) . '/src/MetaBoxesInit.php';
require dirname(__FILE__) . '/src/RestRoutesInit.php';
require dirname(__FILE__) . '/src/ShortCodesInit.php';
require dirname(__FILE__) . '/src/AddMoviesToDb.php';

new MetaBoxesInit();
new RestRoutesInit();
new ShortCodesInit();

