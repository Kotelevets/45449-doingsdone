USE doingsdone;

INSERT INTO users (reg_date, email, user_name, user_pass, contacts)
VALUES 
(NOW(), 'kotya.aa@gmail.com', 'Александр', md5('qwerty'), null),
(NOW(), 'vasya@vasya.net',    'Василий',   md5('qwerty'), null);

INSERT INTO projects (project_name, user_id)
VALUES 
('Все',      1),		-- 1
('Входящие', 1),		-- 2
('Учеба',    1),		-- 3
('Работа',   1),		-- 4
('Домашние дела', 1),   -- 5
('Авто',     1),        -- 6
('Все',      2),		-- 7
('Входящие', 2),        -- 8
('Хобби',    2),        -- 9
('Учеба',    2),        -- 10
('Конструирование', 2); -- 11

INSERT INTO tasks (creation_date, done_date, task_name, file_name, completion_date, user_id, project_id)
VALUES 
(NOW(), '2018-06-01', 'Собеседование в IT компании',     'Резюме Котелевец.doc',  null,         1, 4),   -- 1
(NOW(), '2018-05-25', 'Выполнить тестовое задание',      'Home.psd',              null,         1, 4),   -- 2
(NOW(), '2018-04-21', 'Сделать задание первого раздела', 'Home.psd',              '2018-04-20', 1, 3),   -- 3
(NOW(), '2018-04-22', 'Встреча с другом',                 null,                   null,         1, 2),   -- 4
(NOW(),  null,        'Купить корм для кота',             null,                   null,         1, 5),   -- 5
(NOW(),  null,        'Заказать пиццу',                   null,                   null,         1, 5),   -- 6
(NOW(), '2018-06-05', 'Нарисовать рассвет',              'test.bmp',              null,         2, 9),   -- 7
(NOW(), '2018-05-28', 'Сходить на рыбалку',               null,                   '2018-05-17', 2, 9),   -- 8
(NOW(), '2018-04-21', 'Сделать задание первого раздела', 'Home.psd',              '2018-04-20', 2, 10),  -- 9
(NOW(), '2018-05-27', 'Передать проект на защиту',       'Home.psd',              null,         2, 10),  -- 10
(NOW(), '2018-06-05', 'Разработать плату БП',            'Sch_Diagram_105.sch',   null,         2, 11),  -- 11
(NOW(), '2018-06-05', 'Доработать CPU-модуль',           'Concept.doc',           null,         2, 11),  -- 12
(NOW(),  null,        'Разобрать протокол передачи',      null,                   null,         2, 11),  -- 13
(NOW(), '2018-04-29', 'Встреча с другом',                 null,                   null,         2, 8),   -- 14
(NOW(),  null,        'Заказать пиццу',                   null,                   null,         2, 8);   -- 15

-- получить список из всех проектов для одного пользователя
SELECT p.id, p.project_name 
  FROM projects p 
 WHERE p.user_id = 1;

-- получить список из всех задач для одного проекта
SELECT t.task_name, t.file_name, date_format(t.done_date, '%d.%m.%Y') AS done_date, p.id, p.project_name, t.completion_date
  FROM tasks t JOIN projects p ON t.project_id = p.id where t.user_id = 1
   and project_id = 4;

-- пометить задачу как выполненную
UPDATE tasks SET completion_date = NOW()
WHERE id = 3;

-- получить все задачи для завтрашнего дня
SELECT task_name, file_name, date_format(done_date, '%d.%m.%Y') AS done_date from tasks
WHERE done_date = adddate(curdate(), 1);

-- обновить название задачи по её идентификатору
UPDATE tasks SET task_name = 'Заказать пиццу "Четыре сыра"'
WHERE id = 6;
