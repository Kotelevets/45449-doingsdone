<div class="modal" <?= count($errors_user) !== 0 ? '' : 'hidden id="user_login"' ?> >
    <button class="modal__close" type="button" name="button" href="/">Закрыть</button>

    <h2 class="modal__heading">Вход на сайт</h2>

    <form class="form" action="index.php" method="post">
        <div class="form__row">
            <label class="form__label" for="email">E-mail <sup>*</sup></label>
            <?= isset($errors_user['email']) ? '<p class="form__message">' . $errors_user['email'] . '</p>' : '' ?>
            <input class="form__input <?= isset($errors_user['email']) ? ' form__input--error' : '' ?>" 
             type="text" name="email" id="email" value="<?= $user_values['email'] ?>" placeholder="Введите e-mail"
            >
        </div>

        <div class="form__row">
            <label class="form__label" for="password">Пароль <sup>*</sup></label>
            <?= isset($errors_user['password']) ? '<p class="form__message">' . $errors_user['password'] . '</p>' : '' ?>
            <input class="form__input <?= isset($errors_user['password']) ? ' form__input--error' : '' ?>" 
             type="password" name="password" id="password" value="<?= $user_values['password'] ?>" placeholder="Введите пароль"
            >
        </div>

        <div class="form__row form__row--controls">
            <input class="button" type="submit" name="" value="Войти">
        </div>
    </form>
</div>
