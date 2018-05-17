<?php
// функция подсчета задач
function task_count($task_array, $project_name) {
    // если $project_name = "Все" - считаем все задачи в массиве
    if ($project_name === 'Все') {
        return count($task_array);
    } else {
        $count = 0;
        // для остальных значений считаем количество задач, для проекта из $project_name
        foreach ($task_array as $item) {
            if ($item['project_name'] === $project_name) {
                $count += 1;
            }
        }
        return $count;    
    }
}

// функция-шаблонизатор
function render_template($template_path, $data) {
    if (!file_exists($template_path)) {
        return '';
    } else {
        ob_start();
        extract($data);
        require_once($template_path);
        return ob_get_clean();
    }
}

// функция для определения задач, у которых <24 часа до завершения
function task_near_finish($task_completion_date) {
    if ($task_completion_date != null) {
        $hours_to_finish = floor((strtotime($task_completion_date.' 00:00') - time())/3600);
        if ($hours_to_finish < 24) {
            return true;
        } 
    }    
    return false;
}
