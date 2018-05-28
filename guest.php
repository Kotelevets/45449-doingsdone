<?php

// подключаем файл с функциями
require_once('functions.php');

// рендерим гостквую страницу,
// передаем шаблон и заголовок
$guest = render_template('templates/guest.php', ['title' => 'Дела в порядке - Гостевая']);

// выводим гостевую страницу
print($guest);
