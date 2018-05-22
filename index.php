<?php
// выбор часового пояса
date_default_timezone_set('Europe/Kiev');

// подключаем файл с функциями
require_once('functions.php');

// выбор пользователя
$user_id = 1;

// проверка на существование параметра запроса с идентификатором проекта
// если параметр присутствует, то показываем только те задачи, что относятся к этому проекту
// если параметра нет, то показываем все задачи
isset($_GET['project_id']) ? $project_id = intval($_GET['project_id']) : $project_id = null;

// подключение к БД
$connect = mysqli_connect("localhost", "root", "", "doingsdone");

// если подключение успешно делаем выборки, нет - выводим ошибку
if ($connect === false) {
    print(render_template('templates/error.php'));
    exit();
} else {
    // указание, какую кодировку использовать
    mysqli_set_charset($connect, "utf8");

    // выборка списка(массива) всех проектов текущего пользователя
    $sql = "SELECT p.id, p.project_name FROM projects p WHERE p.user_id = " . $user_id;
    $result = mysqli_query($connect, $sql);
    if (!$result) {
        print(render_template('templates/error.php'));
        exit();
    }
    $projects = mysqli_fetch_all($result, MYSQLI_ASSOC);

    // Если параметр запроса отсутствует, либо если по этому id не нашли ни одной записи, 
    // то вместо содержимого страницы возвращать код ответа 404
    $cnt = 0;
    foreach ($projects as $key => $item) {
        if ($project_id === intval($item['id']) or !isset($_GET['project_id'])) {
            $cnt++;
        }  
    }
    if ($cnt === 0) {
        http_response_code(404);
        print(render_template('templates/error404.php'));
        exit();
    };

    // выборка списка(массива) задач текущего пользователя с условиями (для выбранного проекта)
    // если проект не указан, то выводим все задачи пользователя
    // если указан, то выводим задачи только для выбранного проекта
    $sql =  "SELECT t.task_name, t.file_name, date_format(t.done_date, '%d.%m.%Y') AS done_date, p.id, p.project_name, t.completion_date"
           ."  FROM tasks t JOIN projects p ON t.project_id = p.id where t.user_id = " . $user_id
           . (is_int($project_id) ? " and project_id = " . $project_id : "");
    $result = mysqli_query($connect, $sql);
    if (!$result) {
        print(render_template('templates/error.php'));
        exit();
    }
    $tasks_cond = mysqli_fetch_all($result, MYSQLI_ASSOC);

    // выборка списка(массива) всех задач текущего пользователя
    $sql =  "SELECT t.task_name, t.file_name, date_format(t.done_date, '%d.%m.%Y') AS done_date, p.id, p.project_name, t.completion_date"
           ."  FROM tasks t JOIN projects p ON t.project_id = p.id where t.user_id = " . $user_id;
    $result = mysqli_query($connect, $sql);
    if (!$result) {
        print(render_template('templates/error.php'));
        exit();
    }
    $tasks_all = mysqli_fetch_all($result, MYSQLI_ASSOC);
}

// получаем(рендерим) основные данные (отображаем список задач) для страницы,
// передаем список задач и шаблон для основных данных
$main = render_template('templates/index.php', ['tasks' => $tasks_cond]);

// рендерим основную страницу, 
// передаем основные данные $main, список проектов и задач, проект, шаблон и title страницы
$layout = render_template('templates/layout.php', ['content'    => $main, 
                                                   'projects'   => $projects,
                                                   'project_id' => $project_id,
                                                   'tasks'      => $tasks_all,
                                                   'title'      => 'Дела в порядке'
                                                  ]);
// выводим полученную страницу
print($layout);
