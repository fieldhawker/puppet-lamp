<?php $this->setLayoutVar('title', '住所登録') ?>


<div class="container">
  <div class="panel panel-info">
    <div class="panel-heading">
      <h3 class="panel-title">住所登録</h3>
    </div>
    <div class="panel-body">

      <?php if (isset($errors) && count($errors) > 0): ?>
        <?php echo $this->render('errors', array('errors' => $errors)); ?>
      <?php endif; ?>

      <form action="<?php echo $base_url; ?>/address/post" method="post">

        <input type="hidden" name="_token" value="<?php echo $this->escape($_token); ?>"/>
        <input type="hidden" name="id" value="<?php echo $this->escape($id); ?>"/>

        <div class="form-group">
          <label>name</label>
          <input type="text" name="name" class="form-control" value="<?php echo $name; ?>">
        </div>
        <div class="form-group">
          <label>address</label>
          <input type="text" name="address" class="form-control" value="<?php echo $address; ?>">
        </div>
        <button type="submit" class="btn btn-success btn-block">登録</button>
      </form>
    </div>
  </div>
</div>


