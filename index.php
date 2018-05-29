<?php
session_start();

// подключаем файл с функциями
require_once('functions.php');

// подключение к БД
$connect = mysqli_connect("localhost", "root", "", "doingsdone");

// если подключение завершилось с ошибкой - 
// выводим ошибку и останавливаем сценарий
if ($connect === false) {
    http_response_code(503);
    print(render_template('templates/error.php'));
    exit();
}

// выбор часового пояса
date_default_timezone_set('Europe/Kiev');

// путь к файлам, которые будут загружены
$file_path = __DIR__.'/';

// указание, какую кодировку использовать
mysqli_set_charset($connect, "utf8");

// проверка на существование сессии пользователя
if (!isset($_SESSION['id'])) {
    
    // если сессия не открыта -
    // проверяем выполнялась ли отправка формы
    // если отправлялась, то подключаем шаблон с модальным окном формы входа
    // и выполняем валидацию формы
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        
        // валидация формы аутентификации пользователя
        $user_values = [];
        $errors_user = [];
        $user = [];
        // список обязательных полей, проверяем заполнены или нет
        $required_fields = ['email', 'password'];
        foreach ($required_fields as $field) {
                if (empty($_POST[$field])) {
                    $errors_user[$field] = 'Поле обязательно для заполнения';
                }
        }
        
        // если поле email заполнено, то проверяем существует ли такой адрес
        if (!empty($_POST['email'])) {
            
            // выборка существующих адресов пользователей из БД
            $sql =  "SELECT id, email, user_name, user_pass FROM users WHERE email = '" . mysqli_real_escape_string($connect, $_POST['email']) ."'";
            $result = mysqli_query($connect, $sql);
            
            if (!$result) {
                http_response_code(503);
                print(render_template('templates/error.php'));
                exit();
            }
            $user = mysqli_fetch_assoc($result);
            
            if (!count($user)) {        
            
            // Если в базе не найден указанный email - выводим ошибку
            $errors_user['email'] = 'Введен несуществующий в системе e-mail';

            } elseif (!empty($_POST['password']) && password_verify($_POST['password'], $user['user_pass'])) {
                
                // если email найден и пароль совпадает, то 
                // передаем в сессию данные пользователя
                $_SESSION['id']        = $user['id'];
                $_SESSION['email']     = $user['email'];
                $_SESSION['user_name'] = $user['user_name'];
                
                // переход на Главную
                header("Location: /");

            } elseif (!empty($_POST['password']) && !password_verify($_POST['password'], $user['user_pass'])) {
                
                // если есть email и введен пароль, но проверка пароля неудачна -
                // то выводим ошибку, о том что пароль неверный
                $errors_user['password'] = 'Пароль неверный';

            }
        }
        
        $user_values['email']    = $_POST['email'];
        $user_values['password'] = $_POST['password'];
        
        // рендерим гостевую страницу,
        // передаем шаблон
        $guest = render_template('templates/guest.php');
        
        // рендерим модальное окно с формой входа
        // передаем шаблон
        $modal = render_template('templates/auth.php', ['user_values' => $user_values,
                                                        'errors_user' => $errors_user]);
        // рендерим основную страницу,
        // передаем контент гостевой страницы,
        // признак для вывода в шаблоне класса body_background в теге <body>,
        // модальное окно с формой для аутентификации пользователя
        // и title страницы
        $layout = render_template('templates/layout.php', ['content'         => $guest,
                                                           'body_background' => true,
                                                           'modal'           => $modal,
                                                           'error_modal'     => count($errors_user),
                                                           'title'           => 'Дела в порядке - Гостевая']);
        // выводим страницу
        print($layout);
    
    } else {
        // если форма не отправлялась - то подключаем шаблон гостевой страницы    
        
        // рендерим гостевую страницу,
        // передаем шаблон
        $guest = render_template('templates/guest.php');
        
        // рендерим модальное окно с формой входа
        // передаем шаблон
        $modal = render_template('templates/auth.php');
        
        // рендерим основную страницу,
        // передаем контент гостевой страницы,
        // признак для вывода в шаблоне класса body_background в теге <body>,
        // модальное окно с формой для аутентификации пользователя
        // и title страницы
        $layout = render_template('templates/layout.php', ['content'         => $guest,
                                                           'body_background' => true,
                                                           'modal'           => $modal,
                                                           'title'           => 'Дела в порядке - Гостевая']);
        // выводим страницу
        print($layout);
    }

} else {
    // если пользователь прошел аутентификацию
    // выбор данных пользователя из данных в сессии
    $user = [];
    $user['id']        = $_SESSION['id'];
    $user['email']     = $_SESSION['email'];
    $user['user_name'] = $_SESSION['user_name'];

    // проверка на существование параметра запроса show_completed
    // если параметр присутствует, то сохраняем в $show_completed
    // если параметр не указан, то по умолчанию 
    // показываем только невыполненные задачи (п.4.1 ТЗ)
    $show_completed = isset($_GET['show_completed']) ? intval($_GET['show_completed']) : 0;

    // проверка на существование параметра запроса task_filter
    // если параметр присутствует, то сохраняем в $task_filter
    // если параметр не указан, то по умолчанию
    // показываем все задачи
    $task_filter = isset($_GET['task_filter']) ? intval($_GET['task_filter']) : 1;

    // формируем условие для выборки задач
    // по переданному в параметре $task_filter фильтру, 
    // по умолчанию выбираем все задачи
    $sql_filter = '';
    switch ($task_filter) {
        // 
        case 2:
            $sql_filter = ' and done_date = curdate() ';
            break;
        
        case 3:
            $sql_filter = ' and done_date = adddate(curdate(), 1) ';
            break;
        
        case 4:
            $sql_filter = ' and completion_date is null and done_date < curdate() ';
            break;
    }

    // проверка на существование параметра запроса с идентификатором проекта
    // если параметр присутствует, то показываем только те задачи, что относятся к этому проекту
    // если параметра нет, то показываем все задачи
    $project_id = isset($_GET['project_id']) ? intval($_GET['project_id']) : null;

    // выборка списка(массива) всех проектов текущего пользователя
    $sql = "SELECT p.id, p.project_name FROM projects p WHERE p.user_id = " . $user['id'];
    $result = mysqli_query($connect, $sql);
    if (!$result) {
        http_response_code(503);
        print(render_template('templates/error.php'));
        exit();
    }
    $projects = mysqli_fetch_all($result, MYSQLI_ASSOC);

    // Если значение параметра project_id отсутствует, 
    // либо если по этому project_id не нашли ни одной записи,
    // то вместо содержимого страницы возвращать код ответа 404
    if (!in_array($project_id, array_column($projects, 'id')) && isset($_GET['project_id'])) {
        http_response_code(404);
        print(render_template('templates/error404.php'));
        exit();
    }

    // выборка списка(массива) задач текущего пользователя с условиями (для выбранного проекта)
    // если проект не указан, то выводим все задачи пользователя
    // если указан, то выводим задачи только для выбранного проекта
    $sql =  "SELECT t.id, t.task_name, t.file_name, date_format(t.done_date, '%d.%m.%Y') AS done_date, p.id AS project_id, p.project_name, t.completion_date"
           ."  FROM tasks t JOIN projects p ON t.project_id = p.id where t.user_id = " . $user['id'] . $sql_filter
           . (is_int($project_id) ? " and project_id = " . $project_id : "")
           ." ORDER BY date(done_date) DESC, completion_date DESC";
    $result = mysqli_query($connect, $sql);
    if (!$result) {
        http_response_code(503);
        print(render_template('templates/error.php'));
        exit();
    }
    $tasks_cond = mysqli_fetch_all($result, MYSQLI_ASSOC);

    // выборка списка(массива) всех задач текущего пользователя
    $sql =  "SELECT t.id, t.task_name, t.file_name, date_format(t.done_date, '%d.%m.%Y') AS done_date, p.id AS project_id, p.project_name, t.completion_date"
           ."  FROM tasks t JOIN projects p ON t.project_id = p.id where t.user_id = " . $user['id'];
    $result = mysqli_query($connect, $sql);
    if (!$result) {
        http_response_code(503);
        print(render_template('templates/error.php'));
        exit();
    }
    $tasks_all = mysqli_fetch_all($result, MYSQLI_ASSOC);

    // проверка на существование параметра запроса с идентификатором задачи
    // если параметр присутствует, то сохраняем код задачи в $task_id
    $task_id = isset($_GET['task_id']) ? intval($_GET['task_id']) : null;

    // Если значение параметра task_id отсутствует, 
    // либо если по этому task_id не нашли ни одной записи для пользователя,
    // по выбранному в фильтре проекту
    // то вместо содержимого страницы возвращать код ответа 404
    if (!in_array($task_id, array_column($tasks_cond, 'id')) && isset($_GET['task_id'])) {
        http_response_code(404);
        print(render_template('templates/error404.php'));
        exit();
    }

    // проверка на существование параметра запроса с идентификатором CHECK
    // если параметр присутствует, то сохраняем значение в $check
    $check = isset($_GET['check']) ? intval($_GET['check']) : null;

    $link = '?show_completed=' . $show_completed
          . (isset($project_id) ? '&project_id=' . $project_id : '');

    // если были переданы параметры task_id и check,
    // то модифицируем поле completion_date
    // у выбранной задачи, согласно параметру check
    if ($task_id && isset($check)) {

        $sql =  "UPDATE tasks SET completion_date="
              . ($check === 1 ? "'" . date("Y-m-d H:i") . "'" : "NULL")
              . " WHERE id = " . intval($task_id) . "";
        $result = mysqli_query($connect, $sql);
        
        if (!$result) {
            // скрипт завершился с ошибкой - выводим ошибку
            http_response_code(503);
            print(render_template('templates/error.php'));
            exit();
        } else {
            // скрипт завершился без ошибок
            // переход на Главную, с учетом выбранного проекта
            header("Location: /index.php" 
                   . $link);
        }
    }

    // валидация формы для создания новой задачи
    $errors = [];
    $values = [];

    // если была передача данных в сценарий методом POST из формы создания задачи
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_task'])) {

        // список обязательных полей, проверяем заполнены или нет
        $required_fields = ['name', 'project'];
        foreach ($required_fields as $field) {
                if (empty($_POST[$field])) {
                    $errors[$field] = 'Поле обязательно для заполнения';
                }
        }

        // если был выбран проект, то проверяем,
        // что идентификатор выбранного проекта ссылается на реально существующий проект
        if (isset($_POST['project']) && !in_array($_POST['project'], array_column($projects, 'id'))) {
            $errors['project'] = 'Выбран несуществующий проект';
        }

        // проверка содержимого поля «Срок выполнения» 
        // на соответствие формату «ГГГГ-ММ-ДД ЧЧ:ММ»
        // и далее, проверка, что указанная дата реальна в принципе
        // (исключение ситуаций с 2018-06-31 или 2018-02-29 ит.п.)
        if (!empty($_POST['date']) && !preg_match("/^([0-9]{4})-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1]) (0[0-9]|1[0-9]|2[0-3]):([0-5][0-9])$/", $_POST['date'], $matches)) {
            $errors['date'] = 'Введите дату в формате «ГГГГ-ММ-ДД ЧЧ:ММ»';
        } elseif (!empty($_POST['date']) && !checkdate($matches[2], $matches[3], $matches[1])) {
            $errors['date'] = 'Введите правильную дату';
        }
        $date = !empty($_POST['date']) ? $_POST['date'] : null;

        // если был указан файл
        // проверяем существует ли в директории файл с указанным именем,
        // если да - возвращаем ошибку, что такой файл уже есть на сайте
        if (isset($_FILES['preview']) && !empty($_FILES['preview']['name'])) {
            $file = $_FILES['preview']['name'];
            if (file_exists ($file_path . $file)) {
                $errors['file'] = 'Файл уже существует на сайте, укажите другое имя файла';
            }
        }

        // если ошибок нет, производим запись в таблицу с задачами
        // если есть ошибки - сохраняем флаг ошибки,
        // и массив с заранее введенными значениями в окне создания задачи
        if (empty($errors)) {

            $sql = "INSERT INTO tasks (creation_date, done_date, task_name, file_name, user_id, project_id) "
                  ."VALUES (NOW(), ?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($connect, $sql);
            mysqli_stmt_bind_param($stmt, 'sssii', $date, $_POST['name'], $file, intval($user['id']), intval($_POST['project']));
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
            $values['name']    = $_POST['name'];
            $values['project'] = $_POST['project'];
            $values['date']    = $_POST['date'];
        }
    }

    // если была передача данных в сценарий методом POST из формы создания проекта
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_project'])) {

        // проверяем заполнен проект или нет
        if (empty($_POST['name'])) {
            $errors['project_name'] = 'Поле обязательно для заполнения';
        }

        // если был заполнен проект, то проверяем,
        // что проекта с указанным именем нет в БД для пользователя на текущий момент
        if (isset($_POST['project']) && in_array($_POST['name'], array_column($projects, 'project_name'))) {
            $errors['project_name'] = 'Выбран уже существующий проект<br/>Введите другое название проекта';
        }

        // если ошибок нет, производим запись в таблицу с проектами
        // если есть ошибки - сохраняем массив с заранее введенным 
        // значением в окне создания проекта
        if (empty($errors)) {

            $sql = "INSERT INTO projects (project_name, user_id) "
                  ."VALUES (?, ?)";
            $stmt = mysqli_prepare($connect, $sql);
            mysqli_stmt_bind_param($stmt, 'si', $_POST['name'], intval($user['id']));
            $res = mysqli_stmt_execute($stmt);

            // если запись в таблицу прошла успешно
            if ($res) {
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
            $values['project_name'] = $_POST['name'];
        }
    }

    // получаем(рендерим) основные данные (отображаем список задач) для страницы,
    // передаем шаблон для основных данных, список задач и выбранный в фильтре проект
    $main = render_template('templates/index.php', ['tasks'               => $tasks_cond,
                                                    'project_id'          => $project_id,
                                                    'show_complete_tasks' => $show_completed,
                                                    'task_filter'         => $task_filter,
                                                    'link'                => $link]);

    // получаем(рендерим) страницу для создания задачи,
    // передаем список проектов, список ошибок
    $modal = render_template('templates/modals.php', ['projects' => $projects,
                                                      'values'   => $values,
                                                      'errors'   => $errors]);

    // рендерим основную страницу,
    // передаем шаблон, основные данные $main, форму для создания задач,
    // признак ошибки при создании задач, список проектов, текущий проект,
    // список задач и title страницы
    $layout = render_template('templates/layout.php', ['user'                => $user,
                                                       'content'             => $main,
                                                       'modal'               => $modal,
                                                       'error_modal'         => count($errors),
                                                       'projects'            => $projects,
                                                       'project_id'          => $project_id,
                                                       'tasks'               => $tasks_all,
                                                       'show_complete_tasks' => $show_completed,
                                                       'title'               => 'Дела в порядке']);
    // выводим полученную страницу
    print($layout);
}
