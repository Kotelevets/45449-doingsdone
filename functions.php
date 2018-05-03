<?php
// функция подсчета задач
function task_count($task_array, $project_name) {
    if ($project_name === 'Все') {
        return count($task_array);
    } else {
        $count = 0;
        foreach ($task_array as $item) {
            if ($item['project'] === $project_name) {
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