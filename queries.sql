USE doingsdone;

INSERT INTO users (reg_date, email, user_name, user_pass, contacts)
VALUES 
(NOW(), 'kotya.aa@gmail.com', 'Александр', md5('qwerty'), null),
(NOW(), 'vasya@vasya.net',    'Василий',   md5('qwerty'), null);

INSERT INTO projects (project_name, user_id)
VALUES 
('Все',      1),
('Входящие', 1),
('Учеба',    1),
('Работа',   1),
('Домашние дела', 1),
('Авто',     1),
('Все',      2),
('Хобби',    2),
('Учеба',    2);

INSERT INTO tasks (creation_date, done_date, task_name, file_name, completion_date, user_id, project_id)
VALUES 
(NOW(), '2018-06-01', 'Собеседование в IT компании',     'Home.psd',  null,        1, 4),
(NOW(), '2018-05-25', 'Выполнить тестовое задание',      'Home.psd',  null,        1, 4),
(NOW(), '2018-04-21', 'Сделать задание первого раздела', 'Home.psd', '2018-04-20', 1, 3),
(NOW(), '2018-04-22', 'Встреча с другом',                'Home.psd',  null,        1, 2),
(NOW(),  null,        'Купить корм для кота',            'Home.psd',  null,        1, 5),
(NOW(),  null,        'Заказать пиццу',                  'Home.psd',  null,        1, 5);

-- получить список из всех проектов для одного пользователя
SELECT project_name FROM projects
WHERE user_id = 1;

-- получить список из всех задач для одного проекта
SELECT task_name, file_name, date_format(done_date, '%d.%m.%Y') AS done_date FROM tasks
WHERE project_id = 4;

-- пометить задачу как выполненную
UPDATE tasks SET completion_date = NOW()
WHERE id = 3;

-- получить все задачи для завтрашнего дня
SELECT task_name, file_name, date_format(done_date, '%d.%m.%Y') AS done_date from tasks
WHERE done_date = adddate(curdate(), 1);

-- обновить название задачи по её идентификатору
UPDATE tasks SET task_name = 'Заказать пиццу "Четыре сыра"'
WHERE id = 6;
