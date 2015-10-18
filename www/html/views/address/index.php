<?php $this->setLayoutVar('title', '住所一覧') ?>

<div class="container">
    <div class="panel panel-info">
        <div class="panel-heading">
            <h3 class="panel-title">住所一覧</h3>
        </div>
        <div class="panel-body">

            <div class="row row-20 col-lg-6">
                <button type="button" class="btn btn-success btn-block"
                        onClick="location.href='<?php echo $base_url; ?>/address/register'">新規登録
                </button>
            </div>
            <br/>
            <table class="table table-striped table-bordered table-hover">
                <thead>
                <tr>
                    <th>名前</th>
                    <th>住所</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($addresses as $address): ?>
                    <tr>
                        <td><?php echo $this->escape($address["name"]); ?></td>
                        <td><?php echo $this->escape($address["address"]); ?></td>
                        <td>
                            <button type="button" class="btn btn-primary btn-xs"
                                    onClick="location.href='<?php echo $base_url; ?>/address/update/<?php echo $this->escape($address["id"]); ?>'">
                                編集
                            </button>
                            <button type="button" class="btn btn-danger btn-xs"
                                    onClick="location.href='<?php echo $base_url; ?>/address/delete/<?php echo $this->escape($address["id"]); ?>'">
                                削除
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


