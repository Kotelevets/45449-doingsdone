<?php

// подключаем файл с функциями
require_once('functions.php');

// подключение к БД
$connect = mysqli_connect("localhost", "root", "", "doingsdone");

// если подключение успешно проверяем данные на форме, нет - выводим ошибку
if ($connect === false) {
    http_response_code(503);
    print(render_template('templates/error.php'));
    exit();
} else {
    // указание, какую кодировку использовать
    mysqli_set_charset($connect, "utf8");

    // валидация формы для регистрации
    $errors_reg = [];
    $reg_values = [];

    // если была передача данных в сценарий методом POST идем дальше
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        
        // список обязательных полей, проверяем заполнены или нет
        $required_fields = ['email', 'password', 'name'];
        foreach ($required_fields as $field) {
                if (empty($_POST[$field])) {
                    $errors_reg[$field] = 'Поле обязательно для заполнения';
                }
        }

        // если был выбран адрес электронной почты, то проверяем, 
        // что формат соответствует адресу электронной почты
        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) && !empty($_POST['email'])) {
            $errors_reg['email'] = 'Введите корректный адрес электронной почты';
        }

        // проверяем, что в БД нет такого адреса электронной почты,
        // если есть - выдаем соответствующую ошибку
        if (!empty($_POST['email'])) {
            // выборка существующих адресов пользователей из БД
            $sql =  "SELECT email FROM users WHERE email = '" . mysqli_real_escape_string($connect, $_POST['email']) ."'";
            $result = mysqli_query($connect, $sql);
            if (!$result) {
                http_response_code(503);
                print(render_template('templates/error.php'));
                exit();
            }
            $email = mysqli_fetch_all($result, MYSQLI_ASSOC);
            
            if (count($email)) {        
        	   $errors_reg['email'] = 'Пользователь с таким адресом электронной почты уже существует';
            }
        }

        // если ошибок нет
        if (empty($errors_reg)) {
        	
            // получаем хэш пароля
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            
            // открываем транзакцию
            mysqli_query($connect, "START TRANSACTION");
            
            // производим запись в таблицу с пользователями
            $sql = "INSERT INTO users (reg_date, email, user_name, user_pass) " 
                  ."VALUES (NOW(), ?, ?, ?)";
            $stmt = mysqli_prepare($connect, $sql);
            mysqli_stmt_bind_param($stmt, 'sss', $_POST['email'], $_POST['name'], $password);
            $res = mysqli_stmt_execute($stmt);
            
            // получаем user_id нового пользователя            
            $last_uid = mysqli_insert_id($connect);
            
            // создаем проекты Все и Входящие
            $sql = "INSERT INTO projects (project_name, user_id) VALUES 
                    ('Все',      " . $last_uid . "),        
                    ('Входящие', " . $last_uid . ")";
            $res2 = mysqli_query($connect, $sql);

            // если запись в таблицы прошла успешно
            if ($res && $res2) {
                // коммитим транзакцию
                mysqli_query($connect, "COMMIT");
                // и переходим на Гостевую для входа на сайт
                header("Location: /guest.php");   
            } else {
                // если по каким-либо причинам запись не удалась
                // делаем rollback,
                // и возвращаем код ошибки 503 и рендерим страницу с ошибкой
                mysqli_query($connect, "ROLLBACK");
                http_response_code(503);
                print(render_template('templates/error.php'));
                exit();
            }
        } else {
            $reg_values['email']    = $_POST['email'];
            $reg_values['password'] = $_POST['password'];
            $reg_values['name']     = $_POST['name'];
        }
    }
}

// рендерим страницу регистрации,
// передаем шаблон, массив с данными из формы регистрации (если были ошибки),
// тексты ошибок (если были ошибки), заголовок
$register = render_template('templates/register.php', ['reg_values' => $reg_values,
                                                       'errors_reg' => $errors_reg,
	                                                   'title'      => 'Дела в порядке - Регистрация']);
// выводим страницу
print($register);
