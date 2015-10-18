<?php $this->setLayoutVar('title', 'アカウント登録') ?>


<form action="<?php echo $base_url; ?>/account/register" method="post" class="form-signin">
    <h2 class="form-signin-heading">アカウント登録</h2>

    <input type="hidden" name="_token" value="<?php echo $this->escape($_token); ?>"/>

    <?php if (isset($errors) && count($errors) > 0): ?>
        <?php echo $this->render('errors', array('errors' => $errors)); ?>
    <?php endif; ?>

    <?php echo $this->render('account/inputs', array(
      'email'    => $email,
      'password' => $password,
      'submit'   => 'アカウント登録'
    )); ?>
    <!---->
    <!--    <label for="inputEmail" class="sr-only">Email address</label>-->
    <!--    <input type="email" id="inputEmail" class="form-control" placeholder="Email address" required autofocus>-->
    <!--    <label for="inputPassword" class="sr-only">Password</label>-->
    <!--    <input type="password" id="inputPassword" class="form-control" placeholder="Password" required>-->


</form>

