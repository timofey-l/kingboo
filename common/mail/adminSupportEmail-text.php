Partner: <?= $model->partner->username ?> (<?= $model->partner->email ?>, ID=<?= $model->partner->id ?>)
Thread: <?= (isset($model->parent_id) && $model->parent_id) ? $model->parent_id : $model->id ?>
-------------------------
<?= $model->title ?>
-------------------------
<?= $model->text ?>