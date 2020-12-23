<?php

require dirname(__FILE__) . '/src/ThemeStyling.php';
//require dirname(__FILE__) . '/src/RestFields.php';
require dirname(__FILE__) . '/src/PostTypes.php';
require dirname(__FILE__) . '/src/MetaBoxesInit.php';
require dirname(__FILE__) . '/src/ShortCodes.php';

#### !!! ПОДКЛЮЧАТЬ ЕДИНОРАЗОВО ТОЛЬКО ДЛЯ ПАКЕТНОГО ДОБАВЛЕНИЯ БАЗЫ ФИЛЬМОВ В БАЗУ ДАННЫХ WORDPRESS !!! ####
//require dirname(__FILE__) . '/src/AddMoviesFromDb.php';

new MetaBoxesInit();

require dirname(__FILE__) . '/src/apimovie.php';