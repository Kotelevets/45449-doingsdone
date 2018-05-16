USE doingsdone;

INSERT INTO users (id, reg_date, email, user_name, user_pass, contacts)
VALUES (1, NOW(), 'kotya.aa@gmail.com', 'Александр', md5('qwerty'), null);
INSERT INTO users (id, reg_date, email, user_name, user_pass, contacts)
VALUES (2, NOW(), 'vasya@vasya.net', 'Василий', md5('qwerty'), null);

INSERT INTO projects (id, project_name, user_id)
VALUES (1, 'Все', 1);
INSERT INTO projects (id, project_name, user_id)
VALUES (2, 'Входящие', 1);
INSERT INTO projects (id, project_name, user_id)
VALUES (3, 'Учеба', 1);
INSERT INTO projects (id, project_name, user_id)
VALUES (4, 'Работа', 1);
INSERT INTO projects (id, project_name, user_id)
VALUES (5, 'Домашние дела', 1);
INSERT INTO projects (id, project_name, user_id)
VALUES (6, 'Авто', 1);
INSERT INTO projects (id, project_name, user_id)
VALUES (7, 'Все', 2);
INSERT INTO projects (id, project_name, user_id)
VALUES (8, 'Хобби', 2);
INSERT INTO projects (id, project_name, user_id)
VALUES (9, 'Учеба', 2);

INSERT INTO tasks (id, creation_date, done_date, task_name, file_name, completion_date, user_id, project_id)
VALUES (1, NOW(), '2018-06-01', 'Собеседование в IT компании', 'Home.psd', null, 1, 4);
INSERT INTO tasks (id, creation_date, done_date, task_name, file_name, completion_date, user_id, project_id)
VALUES (2, NOW(), '2018-05-25', 'Выполнить тестовое задание', 'Home.psd', null, 1, 4);
INSERT INTO tasks (id, creation_date, done_date, task_name, file_name, completion_date, user_id, project_id)
VALUES (3, NOW(), '2018-04-21', 'Сделать задание первого раздела', 'Home.psd', '2018-04-20', 1, 3);
INSERT INTO tasks (id, creation_date, done_date, task_name, file_name, completion_date, user_id, project_id)
VALUES (4, NOW(), '2018-04-22', 'Встреча с другом', 'Home.psd', null, 1, 2);
INSERT INTO tasks (id, creation_date, done_date, task_name, file_name, completion_date, user_id, project_id)
VALUES (5, NOW(), null, 'Купить корм для кота', 'Home.psd', null, 1, 5);
INSERT INTO tasks (id, creation_date, done_date, task_name, file_name, completion_date, user_id, project_id)
VALUES (6, NOW(), null, 'Заказать пиццу', 'Home.psd', null, 1, 5);

-- получить список из всех проектов для одного пользователя
SELECT * FROM projects
WHERE user_id = 1;

-- получить список из всех задач для одного проекта
SELECT * FROM tasks
WHERE project_id = 4;

-- пометить задачу как выполненную
UPDATE tasks SET completion_date = NOW()
WHERE id = 3;

-- получить все задачи для завтрашнего дня
SELECT * from tasks
WHERE done_date = adddate(curdate(), 1);

-- обновить название задачи по её идентификатору
UPDATE tasks SET task_name = 'Заказать пиццу "Четыре сыра"'
WHERE id = 6;