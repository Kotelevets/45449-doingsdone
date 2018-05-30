<h2 class="content__main-heading">Список задач</h2>

                <form class="search-form" action="index.php" method="get">
                    <input class="search-form__input" type="text" name="search" value="" placeholder="Поиск по задачам">

                    <input class="search-form__submit" type="submit" name="" value="Искать">
                </form>

                <div class="tasks-controls">
                    <nav class="tasks-switch">
                        <a href="/index.php<?= $link ?>&task_filter=1" class="tasks-switch__item <?= ($task_filter === 1) ? 'tasks-switch__item--active' : '' ?>">Все задачи</a>
                        <a href="/index.php<?= $link ?>&task_filter=2" class="tasks-switch__item <?= ($task_filter === 2) ? 'tasks-switch__item--active' : '' ?>">Повестка дня</a>
                        <a href="/index.php<?= $link ?>&task_filter=3" class="tasks-switch__item <?= ($task_filter === 3) ? 'tasks-switch__item--active' : '' ?>">Завтра</a>
                        <a href="/index.php<?= $link ?>&task_filter=4" class="tasks-switch__item <?= ($task_filter === 4) ? 'tasks-switch__item--active' : '' ?>">Просроченные</a>
                    </nav>

                    <label class="checkbox">
                        <!--добавить сюда аттрибут "checked", если переменная $show_complete_tasks равна единице-->
                        <input class="checkbox__input visually-hidden show_completed" 
                               type="checkbox" value="<?= isset($project_id)  ? '&project_id=' . $project_id : '' ?>"
                               <?= ($show_complete_tasks === 1) ? "checked" : "" ?>
                        >
                        <span class="checkbox__text">Показывать выполненные</span>
                    </label>
                </div>

                <table class="tasks">
                <?php foreach ($tasks as $item) : ?>
                    
                    <?php if ($show_complete_tasks === 1 || !empty($search) || ($show_complete_tasks !== 1 && !$item['completion_date'])) : ?>

                        <tr class="tasks__item task 
                                    <?= $item['completion_date'] ? 'task--completed' : '' ?>
                                    <?= !$item['completion_date'] && task_near_finish($item['done_date']) ? 'task--important' : '' ?>
                        ">
                            <td class="task__select">
                                <label class="checkbox task__checkbox">
                                    <!-- добавляем к ссылке выбранные ранее параметры $show_complte, $project_id ит.д. -->
                                    <!-- исправляем баг скрипта и шаблона -->
                                    <input class="checkbox__input visually-hidden task__checkbox" type="checkbox" 
                                    value="<?= $link ?><?= '&task_id=' . $item['id'] ?>"
                                    <?= $item['completion_date'] ? 'checked' : '' ?>
                                    >
                                    <span class="checkbox__text"><?= htmlspecialchars($item['task_name']); ?></span>
                                </label>
                            </td>

                            <td class="task__file">
                                <!-- если имя файла в таблице пустое, то ссылку не выводим -->
                                <?= $item['file_name'] ? '<a class="download-link" href="'.$item['file_name'].'">'.$item['file_name'].'</a>' : '' ?>
                            </td>

                            <td class="task__date"><?= (htmlspecialchars($item['done_date']) != null) ? htmlspecialchars($item['done_date']) : 'Нет'; ?></td>
                        </tr>

                    <?php endif; ?>

                <?php endforeach; ?>

                <?php if (empty($tasks) && !empty($search)) : ?>

                        <tr class="tasks__item task">
                            <td class="task__select">
                               <b>По вашему запросу "<?= $search ?>" задачи не найдены</b>
                            </td>
                        </tr>

                <?php endif; ?>

                </table>
