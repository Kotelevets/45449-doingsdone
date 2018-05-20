<?php
// выбор часового пояса
date_default_timezone_set('Europe/Kiev');

// выбор пользователя
$user_id = 2;

// выбор проекта
$project_id = 6;

// подключение к БД
$connect = mysqli_connect("localhost", "root", "","doingsdone");

// если подключение успешно делаем выборки, нет - выводим ошибку
if ($connect === false) {
    require_once('pages/error.html');
    exit();
} else {
    // указание, какую кодировку использовать
    mysqli_set_charset($connect, "utf8");

    // выборка списка(массива) всех проектов текущего пользователя
    $sql = "SELECT 0 AS id, 'Все' AS project_name, count(*) AS task_count"
          ."  FROM projects p JOIN tasks t ON p.id = t.project_id WHERE p.user_id = " . $user_id
          ." UNION "
          ."SELECT p.id, p.project_name, count(*) AS task_count"
          ."  FROM projects p JOIN tasks t ON p.id = t.project_id WHERE p.user_id = " . $user_id . " GROUP BY p.id, p.project_name";
    $result = mysqli_query($connect, $sql);
    if (!$result) {
        require_once('pages/error.html');
        exit();
    }
    $projects = mysqli_fetch_all($result, MYSQLI_ASSOC);

    // выборка списка(массива) задач текущего пользователя с условиями (по проекту)
    // если проект == Все, выводим все задачи пользователя
    // если !== Все, то выводим задачи только для соответствующего проекта
    $sql =  "SELECT t.task_name, t.file_name, date_format(t.done_date, '%d.%m.%Y') AS done_date, p.project_name, t.completion_date"
           ."  FROM tasks t JOIN projects p ON t.project_id = p.id where t.user_id = " . $user_id;

    if ($project_id === 0) {
        $cond = "";
    } else {
        $cond = " and project_id = " . $project_id;
    }
    $sql = $sql . $cond;
    
    $result = mysqli_query($connect, $sql);
    if (!$result) {
        require_once('pages/error.html');
        exit();
    }
    $tasks = mysqli_fetch_all($result, MYSQLI_ASSOC);
}

require_once('functions.php');

// получаем(рендерим) основные данные (отображаем список задач) для страницы, 
// передаем список задач и шаблон для основных данных
$main = render_template('templates/index.php', ['tasks' => $tasks]);

// рендерим основную страницу, 
// передаем основные данные $main, список проектов и задач, шаблон и title страницы
$layout = render_template('templates/layout.php', ['content'    => $main,
                                                   'projects'   => $projects,
                                                   'project_id' => $project_id,
                                                   'title'      => 'Дела в порядке'
                                                  ]);
// выводим полученную страницу
print($layout);
