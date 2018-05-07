<?php
date_default_timezone_set('Europe/Kiev');
require_once('functions.php');
require_once('data.php');

$main = render_template('templates/index.php', ['tasks' => $tasks]);
$layout = render_template('templates/layout.php', ['content' => $main,
                                                   'projects' => $projects,
                                                   'tasks' => $tasks, 
                                                   'title' => 'Дела в порядке'
                                                  ]);
print($layout);
