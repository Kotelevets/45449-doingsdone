<?php
// выбор часового пояса
date_default_timezone_set('Europe/Kiev');

// путь к файлам, которые будут загружены
$file_path = __DIR__.'/';

// подключаем файл с функциями
require_once('functions.php');

// выбор пользователя
$user_id = 1;

// проверка на существование параметра запроса с идентификатором проекта
// если параметр присутствует, то показываем только те задачи, что относятся к этому проекту
// если параметра нет, то показываем все задачи
$project_id = isset($_GET['project_id']) ? intval($_GET['project_id']) : null;

// подключение к БД
$connect = mysqli_connect("localhost", "root", "", "doingsdone");

// если подключение успешно делаем выборки, нет - выводим ошибку
if ($connect === false) {
    http_response_code(503);
    print(render_template('templates/error.php'));
    exit();
} else {
    // указание, какую кодировку использовать
    mysqli_set_charset($connect, "utf8");

    // выборка списка(массива) всех проектов текущего пользователя
    $sql = "SELECT p.id, p.project_name FROM projects p WHERE p.user_id = " . $user_id;
    $result = mysqli_query($connect, $sql);
    if (!$result) {
        http_response_code(503);
        print(render_template('templates/error.php'));
        exit();
    }
    $projects = mysqli_fetch_all($result, MYSQLI_ASSOC);

    // Если параметр запроса отсутствует, либо если по этому id не нашли ни одной записи, 
    // то вместо содержимого страницы возвращать код ответа 404
    if (!array_search($project_id, array_column($projects, 'id')) && isset($_GET['project_id'])) {
        http_response_code(404);
        print(render_template('templates/error404.php'));
        exit();
    }

    // выборка списка(массива) задач текущего пользователя с условиями (для выбранного проекта)
    // если проект не указан, то выводим все задачи пользователя
    // если указан, то выводим задачи только для выбранного проекта
    $sql =  "SELECT t.task_name, t.file_name, date_format(t.done_date, '%d.%m.%Y') AS done_date, p.id, p.project_name, t.completion_date"
           ."  FROM tasks t JOIN projects p ON t.project_id = p.id where t.user_id = " . $user_id
           . (is_int($project_id) ? " and project_id = " . $project_id : "");
    $result = mysqli_query($connect, $sql);
    if (!$result) {
        http_response_code(503);
        print(render_template('templates/error.php'));
        exit();
    }
    $tasks_cond = mysqli_fetch_all($result, MYSQLI_ASSOC);

    // выборка списка(массива) всех задач текущего пользователя
    $sql =  "SELECT t.task_name, t.file_name, date_format(t.done_date, '%d.%m.%Y') AS done_date, p.id, p.project_name, t.completion_date"
           ."  FROM tasks t JOIN projects p ON t.project_id = p.id where t.user_id = " . $user_id;
    $result = mysqli_query($connect, $sql);
    if (!$result) {
        http_response_code(503);
        print(render_template('templates/error.php'));
        exit();
    }
    $tasks_all = mysqli_fetch_all($result, MYSQLI_ASSOC);

    // валидация формы для создания новой задачи
    $errors_task = [];
    $task_values = [];
    // если была передача данных в сценарий методом POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        
        // список обязательных полей, проверяем заполнены или нет
        $required_fields = ['name', 'project'];
        foreach ($required_fields as $field) {
                if (empty($_POST[$field])) {
                    $errors_task[$field] = 'Поле обязательно для заполнения';
                }
        }
        
        // если был выбран проект, то проверяем, 
        // что идентификатор выбранного проекта ссылается на реально существующий проект
        if (isset($_POST['project']) && !array_search($_POST['project'], array_column($projects, 'id'))) {
            $errors_task['project'] = empty($errors_task['project']) ? 'Выбран несуществующий проект' : $errors_task['project'].'<br/>Выбран несуществующий проект';
        }

        // проверка содержимого поля «Срок выполнения» 
        // на соответствие формату «ГГГГ-ММ-ДД ЧЧ:ММ»
        // и далее, проверка, что указанная дата реальна в принципе
        // (исключение ситуаций с 2018-06-31 или 2018-02-29 ит.п.)
        if (!empty($_POST['date']) && !preg_match("/^([0-9]{4})-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1]) (0[0-9]|1[0-9]|2[0-3]):([0-5][0-9])$/", $_POST['date'], $matches)) {
            $errors_task['date'] = empty($errors_task['date']) ? 'Введите дату в формате «ГГГГ-ММ-ДД ЧЧ:ММ»' : $errors_task['project'].'<br/>Введите дату в формате «ГГГГ-ММ-ДД ЧЧ:ММ»';
        } elseif (!empty($_POST['date']) && !checkdate($matches[2], $matches[3], $matches[1])) {
            $errors_task['date'] = empty($errors_task['date']) ? 'Введите правильную дату' : $errors_task['project'].'<br/>Введите правильную дату';
        }
        $date = !empty($_POST['date']) ? $_POST['date'] : null;

        // если был указан файл
        // проверяем существует ли в директории файл с указанным именем,
        // если да - возвращаем ошибку, что такой файл уже есть на сайте
        if (isset($_FILES['preview']) && !empty($_FILES['preview']['name'])) {
            $file = $_FILES['preview']['name'];
            if (file_exists ($file_path . $file)) {
                $errors_task['file'] = empty($errors_task['file']) ? 'Файл уже существует на сайте, укажите другое имя файла' : $errors_task['project'].'<br/>Файл уже существует на сайте, укажите другое имя файла';
            }
        }

        // если ошибок нет, производим запись в таблицу с задачами
        // если есть ошибки - сохраняем флаг ошибки, 
        // и массив с заранее введенными значениями в окне создания задачи
        if (empty($errors_task)) {

            $sql = "INSERT INTO tasks (creation_date, done_date, task_name, file_name, user_id, project_id) " 
                  ."VALUES (NOW(), ?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($connect, $sql);
            mysqli_stmt_bind_param($stmt, 'sssii', $date, $_POST['name'], $file, intval($user_id), intval($_POST['project']));
            $res = mysqli_stmt_execute($stmt);

            // если запись в таблицу прошла успешно
            if ($res) {
                // если был указан файл - перемещаем его из временной папки
                // в папку $file_path на сайте
                if (isset($_FILES['preview']) && !empty($_FILES['preview']['name'])) {
                    move_uploaded_file($_FILES['preview']['tmp_name'], $file_path . $file);
                }
                // переход на Главную
                header("Location: /");   
            } else {
                // если по каким-либо причинам запись не удалась
                // возвращаем код ошибки 503 и рендерим страницу с ошибкой
                http_response_code(503);
                print(render_template('templates/error.php'));
                exit();
            }
        } else {
            $task_values['name']    = $_POST['name'];
            $task_values['project'] = $_POST['project'];
            $task_values['date']    = $_POST['date'];
        }
    }
}

// получаем(рендерим) основные данные (отображаем список задач) для страницы,
// передаем список задач и шаблон для основных данных
$main = render_template('templates/index.php', ['tasks' => $tasks_cond]);

// получаем(рендерим) страницу для создания задачи,
// передаем список проектов, список ошибок
$modal = render_template('templates/create_task.php', ['projects'    => $projects,
                                                       'task_values' => $task_values,
                                                       'errors_task' => $errors_task]);

// рендерим основную страницу, 
// передаем шаблон, основные данные $main, форму для создания задач,
// признак ошибки при создании задач, список проектов, текущий проект, 
// список задач и title страницы
$layout = render_template('templates/layout.php', ['content'     => $main,
                                                   'create_task' => $create_task,
                                                   'error_task'  => count($errors_task),
                                                   'projects'    => $projects,
                                                   'project_id'  => $project_id,
                                                   'tasks'       => $tasks_all,
                                                   'title'       => 'Дела в порядке']);
// выводим полученную страницу
print($layout);
