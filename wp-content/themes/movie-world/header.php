<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->

    <link rel="icon" href="../../favicon.ico">
    <link rel="canonical" href="https://getbootstrap.com/docs/3.4/examples/blog/">

    <title><?=get_bloginfo('name')?></title>

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="<?=bloginfo('stylesheet_directory').'/bootstrap/bootstrap.css'?>">

    <!-- Custom styles for this template -->
    <link rel="stylesheet" href="<?=bloginfo('stylesheet_url').'?'.time()?>">

    <?php wp_head(); ?>
</head>

<body>

<div class="blog-masthead">
    <div class="container">
        <nav class="blog-nav">
            <a class="blog-nav-item active" href="#">Новости</a>
            <a class="blog-nav-item" href="#">Фильмы</a>
            <a class="blog-nav-item" href="#">Режиссеры</a>
            <a class="blog-nav-item" href="#">Статьи</a>
            <a class="blog-nav-item" href="#">О нас</a>
        </nav>
    </div>
</div>

<div class="container">

    <div class="blog-header">
        <h1 class="blog-title"><a href="<?=get_bloginfo('url')?>"><?=get_bloginfo('name')?></a> </h1>
        <p class="lead blog-description"><?=get_bloginfo('description')?></p>
    </div>