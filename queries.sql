USE doingsdone;

INSERT INTO users 
SET id = 1, reg_date = NOW(), email = 'kotya.aa@gmail.com', user_name = 'Александр', user_pass = md5('qwerty'), contacts = null;
INSERT INTO users 
SET id = 2, reg_date = NOW(), email = 'vasya@vasya.net', user_name = 'Василий', user_pass = md5('qwerty'), contacts = null;

INSERT INTO projects
SET id = 1, project_name = 'Все', user_id = 1;
INSERT INTO projects
SET id = 2, project_name = 'Входящие', user_id = 1;
INSERT INTO projects
SET id = 3, project_name = 'Учеба', user_id = 1;
INSERT INTO projects
SET id = 4, project_name = 'Работа', user_id = 1;
INSERT INTO projects
SET id = 5, project_name = 'Домашние дела', user_id = 1;
INSERT INTO projects
SET id = 6, project_name = 'Авто', user_id = 1;
INSERT INTO projects
SET id = 7, project_name = 'Все', user_id = 2;
INSERT INTO projects
SET id = 8, project_name = 'Хобби', user_id = 2;
INSERT INTO projects
SET id = 9, project_name = 'Учеба', user_id = 2;

INSERT INTO tasks
SET id = 1, creation_date = NOW(), done_date = '2018-06-01', task_name = 'Собеседование в IT компании', file_name = 'Home.psd', completion_date = null, user_id = 1, project_id = 4;
INSERT INTO tasks
SET id = 2, creation_date = NOW(), done_date = '2018-05-25', task_name = 'Выполнить тестовое задание', file_name = 'Home.psd', completion_date = null, user_id = 1, project_id = 4;
INSERT INTO tasks
SET id = 3, creation_date = NOW(), done_date = '2018-04-21', task_name = 'Сделать задание первого раздела', file_name = 'Home.psd', completion_date = '2018-04-20', user_id = 1, project_id = 3;
INSERT INTO tasks
SET id = 4, creation_date = NOW(), done_date = '2018-04-22', task_name = 'Встреча с другом', file_name = 'Home.psd', completion_date = null, user_id = 1, project_id = 2;
INSERT INTO tasks
SET id = 5, creation_date = NOW(), done_date = null, task_name = 'Купить корм для кота', file_name = 'Home.psd', completion_date = null, user_id = 1, project_id = 5;
INSERT INTO tasks
SET id = 6, creation_date = NOW(), done_date = null, task_name = 'Заказать пиццу', file_name = 'Home.psd', completion_date = null, user_id = 1, project_id = 5;

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