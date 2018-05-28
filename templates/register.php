<div class="page-wrapper">
  <div class="container container--with-sidebar">
    <header class="main-header">
      <a href="/">
        <img src="./img/logo.png" width="153" height="42" alt="Логитип Дела в порядке">
      </a>
    </header>

    <div class="content">
      <section class="content__side">
        <p class="content__side-info">Если у вас уже есть аккаунт, авторизуйтесь на сайте</p>
        
        <a class="button button--transparent content__side-button open-modal" href="javascript:;" target="user_login">Войти</a>
      </section>

      <main class="content__main">
        <h2 class="content__main-heading">Регистрация аккаунта</h2>

        <form class="form" action="registration.php" method="post">
          <div class="form__row">
            <label class="form__label" for="email">E-mail <sup>*</sup></label>

            <input class="form__input <?= isset($errors_reg['email']) ? ' form__input--error' : '' ?>"
                  type="text" name="email" id="email" value="<?= $reg_values['email'] ?>" placeholder="Введите e-mail">

            <?= isset($errors_reg['email']) ? '<p class="form__message">' . $errors_reg['email'] . '</p>' : '' ?>
          </div>

          <div class="form__row">
            <label class="form__label" for="password">Пароль <sup>*</sup></label>

            <input class="form__input <?= isset($errors_reg['password']) ? ' form__input--error' : '' ?>"
                  type="password" name="password" id="password" value="<?= $reg_values['password'] ?>" placeholder="Введите пароль">

            <?= isset($errors_reg['password']) ? '<p class="form__message">' . $errors_reg['password'] . '</p>' : '' ?>
          </div>

          <div class="form__row">
            <label class="form__label" for="name">Имя <sup>*</sup></label>

            <input class="form__input <?= isset($errors_reg['name']) ? ' form__input--error' : '' ?>"
                  type="text" name="name" id="name" value="<?= $reg_values['name'] ?>" placeholder="Введите имя">

            <?= isset($errors_reg['name']) ? '<p class="form__message">' . $errors_reg['name'] . '</p>' : '' ?>
          </div>

          <div class="form__row form__row--controls">
            <?= !empty($errors_reg) ? '<p class="error-message">Пожалуйста, исправьте ошибки в форме</p>' : '' ?>

            <input class="button" type="submit" name="" value="Зарегистрироваться">
          </div>
        </form>
      </main>
    </div>
  </div>
</div>
