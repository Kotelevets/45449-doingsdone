<!-- модальное окно для создания задачи -->
<div class="modal" <?= count($errors) !== 0 && isset($_POST['submit_task']) ? '' : 'hidden id="task_add"' ?> >
    <button class="modal__close" type="button" name="button" href="/">Закрыть</button>

    <h2 class="modal__heading">Добавление задачи</h2>

    <form class="form" action="index.php" enctype="multipart/form-data" method="post">
        <div class="form__row">
            <label class="form__label" for="name">Название <sup>*</sup></label>
            <?= isset($errors['name']) ? '<p class="form__message">' . $errors['name'] . '</p>' : '' ?>
            <input class="form__input <?= isset($errors['name']) ? ' form__input--error' : '' ?>" type="text" name="name" id="name" 
                value="<?= $values['name'] ?>" 
                placeholder="Введите название задачи"
            >
        </div>

        <div class="form__row">
            <label class="form__label" for="project">Проект <sup>*</sup></label>
            <?= isset($errors['project']) ? '<p class="form__message">' . $errors['project'] . '</p>' : '' ?>
            <select class="form__input <?= isset($errors['project']) ? ' form__input--error' : '' ?> form__input--select" name="project" id="project">
                <?php foreach ($projects as $key => $item) : ?>
                    <?= $item['project_name'] === 'Все' ? '' :
                        '<option value="' . $item['id'] . '"' . ($item['id'] === $values['project'] ? ' selected' : '') . '>' . $item['project_name'] . '</option>'  
                    ?>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form__row">
            <label class="form__label" for="date">Срок выполнения</label>
            <?= isset($errors['date']) ? '<p class="form__message">' . $errors['date'] . '</p>' : '' ?>
            <input class="form__input <?= isset($errors['date']) ? ' form__input--error' : '' ?> form__input--date" type="text" name="date" id="date"
                value="<?= $values['date'] ?>"
                placeholder="Введите дату и время"
            >
        </div>

        <div class="form__row">
            <label class="form__label" for="preview">Файл</label>
            <?= isset($errors['file']) ? '<p class="form__message">' . $errors['file'] . '</p>' : '' ?>
            <div class="form__input-file">
                <input class="visually-hidden" type="file" name="preview" id="preview" value="">

                <label class="button button--transparent" for="preview">
                    <span>Выберите файл</span>
                </label>
            </div>
        </div>

        <div class="form__row form__row--controls">
            <input class="button" type="submit" name="submit_task" value="Добавить">
        </div>
    </form>
</div>

<!-- модальное окно для создания проекта -->
<div class="modal" <?= count($errors) !== 0 && isset($_POST['submit_project'])? '' : 'hidden id="project_add"' ?>>
    <button class="modal__close" type="button" name="button">Закрыть</button>

    <h2 class="modal__heading">Добавление проекта</h2>

    <form class="form"  action="index.php" method="post">
        <div class="form__row">
            <label class="form__label" for="project_name">Название <sup>*</sup></label>
            <?= isset($errors['project_name']) ? '<p class="form__message">' . $errors['project_name'] . '</p>' : '' ?>
            <input class="form__input <?= isset($errors['project_name']) ? ' form__input--error' : '' ?>" 
                   type="text" name="name" id="project_name" value="<?= $values['project_name'] ?>" placeholder="Введите название проекта"
            >
        </div>

        <div class="form__row form__row--controls">
            <input class="button" type="submit" name="submit_project" value="Добавить">
        </div>
    </form>
</div>
