<?php

// исходный массив проектов
$projects = ["Все", "Входящие", "Учеба", "Работа", "Домашние дела", "Авто"];

/*
// массив проектов, как из выборки MySql
 $projects = [
 ['project_name' => 'Все'],
 ['project_name' => 'Входящие'],
 ['project_name' => 'Учеба'],
 ['project_name' => 'Работа'],
 ['project_name' => 'Домашние дела'],
 ['project_name' => 'Авто']
];
*/

// исходный массив задач
$tasks = [
         ['task' => 'Собеседование в IT компании', // task_name
          'completion_date' => '01.06.2018', // done_date
          'project' => 'Работа', // project_name
          'done' => false], // completion_date
         ['task' => 'Выполнить тестовое задание',
          'completion_date' => '25.05.2018',
          'project' => 'Работа',
          'done' => false],
         ['task' => 'Сделать задание первого раздела',
          'completion_date' => '21.04.2018',
          'project' => 'Учеба',
          'done' => true],
         ['task' => 'Встреча с другом',
          'completion_date' => '22.04.2018',
          'project' => 'Входящие',
          'done' => false],
         ['task' => 'Купить корм для кота',
          'completion_date' => null,
          'project' => 'Домашние дела',
          'done' => false],
         ['task' => 'Заказать пиццу',
          'completion_date' => null,
          'project' => 'Домашние дела',
          'done' => false]
         ];
/*
// массив задач, как из выборки MySql
$tasks = [
         ['task_name' => 'Собеседование в IT компании',
          'done_date' => '01.06.2018',
          'project_name' => 'Работа',
          'completion_date' => false],
         ['task_name' => 'Выполнить тестовое задание',
          'done_date' => '25.05.2018',
          'project_name' => 'Работа',
          'completion_date' => false],
         ['task_name' => 'Сделать задание первого раздела',
          'done_date' => '21.04.2018',
          'project_name' => 'Учеба',
          'completion_date' => true],
         ['task_name' => 'Встреча с другом',
          'done_date' => '22.04.2018',
          'project_name' => 'Входящие',
          'completion_date' => false],
         ['task_name' => 'Купить корм для кота',
          'done_date' => null,
          'project_name' => 'Домашние дела',
          'completion_date' => false],
         ['task_name' => 'Заказать пиццу',
          'done_date' => null,
          'project_name' => 'Домашние дела',
          'completion_date' => false]
         ];
*/
