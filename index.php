<?php
// выбор часового пояса
date_default_timezone_set('Europe/Kiev');

// выбор пользователя
$user_id = 2;

// выбор проекта
$project = "Все";

// подключение к БД
$connect = mysqli_connect("localhost", "root", "","doingsdone");

// если подключение успешно делаем выборки, нет - выводим ошибку
if ($connect == false) {
    print("Ошибка подключения: ". mysqli_connect_error());
} else {
    // указание, какую кодировку использовать
    mysqli_set_charset($connect, "utf8");

    // выборка списка(массива) всех проектов текущего пользователя
    $sql = "SELECT project_name FROM projects WHERE user_id = $user_id";
    $result = mysqli_query($connect, $sql);
    if (!$result) {
        $error = mysqli_error($con);
        print("Ошибка MySQL: ". $error);
    }
    $projects = mysqli_fetch_all($result, MYSQLI_ASSOC);

    // выборка списка(массива) всех задач текущего пользователя
    $sql =  "SELECT t.task_name, t.file_name, date_format(t.done_date, '%d.%m.%Y') AS done_date, p.project_name, t.completion_date"
           ."  FROM tasks t JOIN projects p ON t.project_id = p.id where t.user_id = " . $user_id;
    $result = mysqli_query($connect, $sql);
    if (!$result) {
        $error = mysqli_error($con);
        print("Ошибка MySQL: ". $error);
    }
    $tasks = mysqli_fetch_all($result, MYSQLI_ASSOC);

    // выборка списка(массива) задач текущего пользователя с условиями (по категории)
    // если проект == Все, выводим все задачи пользователя
    // если !== Все, то выводим задачи только для соответствующего проекта
    if ($project === 'Все') {
        $cond = "";
    } else {
        $cond = " and project_name = '" . mysqli_real_escape_string($connect, $project) . "'";
    }
    $sql = $sql . $cond;
    
    $result = mysqli_query($connect, $sql);
    if (!$result) {
        $error = mysqli_error($con);
        print("Ошибка MySQL: ". $error);
    }
    $tasks_cond = mysqli_fetch_all($result, MYSQLI_ASSOC);
}

require_once('functions.php');

// получаем(рендерим) основные данные (отображаем список задач) для страницы, 
// передаем список задач и шаблон для основных данных
$main = render_template('templates/index.php', ['tasks' => $tasks_cond]);

// рендерим основную страницу, 
// передаем основные данные $main, список проектов и задач, шаблон и title страницы
$layout = render_template('templates/layout.php', ['content'  => $main,
                                                   'projects' => $projects,
                                                   'tasks'    => $tasks,
                                                   'project'  => $project,
                                                   'title'    => 'Дела в порядке'
                                                  ]);
// выводим полученную страницу
print($layout);
