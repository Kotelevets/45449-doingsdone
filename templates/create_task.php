<div class="modal" <?= count($errors_task) !== 0 ? '' : 'hidden id="task_add"' ?> >
    <button class="modal__close" type="button" name="button" href="/">Закрыть</button>

    <h2 class="modal__heading">Добавление задачи</h2>

    <form class="form"  action="index.php" enctype="multipart/form-data" method="post">
        <div class="form__row">
            <label class="form__label" for="name">Название <sup>*</sup></label>
            <?= isset($errors_task['name']) ? '<p class="form__message">' . $errors_task['name'] . '</p>' : '' ?>
            <input class="form__input <?= isset($errors_task['name']) ? ' form__input--error' : '' ?>" type="text" name="name" id="name" 
                value="<?= $task_values['name'] ?>" 
                placeholder="Введите название задачи"
            >
        </div>

        <div class="form__row">
            <label class="form__label" for="project">Проект <sup>*</sup></label>
            <?= isset($errors_task['project']) ? '<p class="form__message">' . $errors_task['project'] . '</p>' : '' ?>
            <select class="form__input <?= isset($errors_task['project']) ? ' form__input--error' : '' ?> form__input--select" name="project" id="project">
                <?php foreach ($projects as $key => $item) : ?>
                    <?= $item['project_name'] === 'Все' ? '' :
                        '<option value="' . $item['id'] . '"' . ($item['id'] === $task_values['project'] ? ' selected' : '') . '>' . $item['project_name'] . '</option>'  
                    ?>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form__row">
            <label class="form__label" for="date">Срок выполнения</label>
            <?= isset($errors_task['date']) ? '<p class="form__message">' . $errors_task['date'] . '</p>' : '' ?>
            <input class="form__input <?= isset($errors_task['date']) ? ' form__input--error' : '' ?> form__input--date" type="text" name="date" id="date"
                value="<?= $task_values['date'] ?>"
                placeholder="Введите дату и время"
            >
        </div>

        <div class="form__row">
            <label class="form__label" for="preview">Файл</label>
            <?= isset($errors_task['file']) ? '<p class="form__message">' . $errors_task['file'] . '</p>' : '' ?>
            <div class="form__input-file">
                <input class="visually-hidden" type="file" name="preview" id="preview" value="">

                <label class="button button--transparent" for="preview">
                    <span>Выберите файл</span>
                </label>
            </div>
        </div>

        <div class="form__row form__row--controls">
            <input class="button" type="submit" name="" value="Добавить">
        </div>
    </form>
</div>
