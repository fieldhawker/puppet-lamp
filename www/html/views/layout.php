<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="jp">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>
    <?php if (isset($title)): echo $this->escape($title) . ' - ';endif; ?> ADDRESS
  </title>

  <!–- jQuery読み込み -–>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <!–- BootstrapのJS読み込み -–>
  <script src="/js/bootstrap.min.js"></script>

  <!–- BootstrapのCSS読み込み -–>
  <link href="/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="/css/style.css" rel="stylesheet" type="text/css" media="screen"/>
</head>
<body>

<div class="navbar navbar-default" role="navigation">
  <div class="navbar-inner">
    <a class="navbar-brand" href="<?php echo $base_url; ?>/">
      <span>ADDRESS</span>
    </a>

    <div id="navbar" class="collapse navbar-collapse">
      <ul class="nav navbar-nav">
        <?php if ($session->isAuthenticated()): ?>
        <li><a href="<?php echo $base_url; ?>/account/signout">ログアウト</a>
          <?php else: ?>
        <li><a href="<?php echo $base_url; ?>/account/signin">ログイン</a>
        <li><a href="<?php echo $base_url; ?>/account/signup">アカウント登録</a>
          <?php endif; ?>
      </ul>
    </div>
  </div>
</div>

<div class="container">
  <?php echo $_content; ?>
</div>

</body>
</html>
