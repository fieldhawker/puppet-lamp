<?php $this->setLayoutVar('title', 'アカウント') ?>

<div
  class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xs-offset-0 col-sm-offset-0 col-md-offset-3 col-lg-offset-3 toppad">


    <div class="panel panel-info">
        <div class="panel-heading">
            <h3 class="panel-title">名前欄</h3>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-3 col-lg-3 " align="center">
                    <img alt="User Pic" src="http://babyinfoforyou.com/wp-content/uploads/2014/10/avatar-300x300.png"
                         class="img-circle img-responsive">
                </div>

                <div class=" col-md-9 col-lg-9 ">
                    <table class="table table-user-information">
                        <tbody>
                        <tr>
                            <td>メールアドレス</td>
                            <td><a href="mailto:info@support.com"><?php echo $this->escape($user['email']); ?></a></td>
                        </tr>
                        <tr>
                            <td>xxx</td>
                            <td>xxx</td>
                        </tr>
                        <tr>
                            <td>test</td>
                            <td>test</td>
                        </tr>
                        <tr>
                            <td>yyy</td>
                            <td>yyy</td>
                        </tr>
                        </tbody>
                    </table>

                    <!--                    <a href="#" class="btn btn-primary">ボタン</a>-->
                    <!--                    <a href="#" class="btn btn-primary">ボタン</a>-->
                </div>
            </div>
        </div>
        <div class="panel-footer">
            <a data-original-title="Broadcast Message" data-toggle="tooltip" type="button"
               class="btn btn-sm btn-primary">
                <i class="glyphicon glyphicon-envelope"></i>
            </a>
            <span class="pull-right">
                <a href="#" data-original-title="Edit this user" data-toggle="tooltip" type="button"
                   class="btn btn-sm btn-warning">
                    <i class="glyphicon glyphicon-edit"></i>
                </a>
                <a data-original-title="Remove this user" data-toggle="tooltip" type="button"
                   class="btn btn-sm btn-danger">
                    <i class="glyphicon glyphicon-remove"></i>
                </a>
            </span>
        </div>

    </div>
</div>


<h2>アカウント</h2>
<p>
    ユーザID:
    <a href="<?php echo $base_urlp ?>/user/<?php echo $this->escape($user['user_
    name']); ?>">
        <strong><?php echo $this->escape($user['user_name']); ?></strong>
    </a>
</p>

<ul>
    <li>
        <a href="<?php echo $base_url; ?>/">ホーム</a>
    </li>
    <li>
        <a href="<?php echo $base_url; ?>/account/signout">ログアウト</a>
    </li>
</ul>

<h3>フォロー中</h3>

<?php if (count($followings) > 0): ?>
    <ul>
        <?php foreach ($followings as $following): ?>
            <li>
                <a href="<?php echo $base_url; ?>/user/<?php echo $this->escape($following['user_name']); ?>">
                    <?php echo $this->escape($following['user_name']); ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>
