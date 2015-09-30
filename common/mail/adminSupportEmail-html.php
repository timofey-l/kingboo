<p>
	<b>Partner:</b> <?= $model->partner->username ?> (<?= $model->partner->email ?>, ID=<?= $model->partner->id ?>)<br />
	<b>Thread:</b> <?= (isset($model->parent_id) && $model->parent_id) ? $model->parent_id : $model->id ?>
</p>
<b><?= $model->title ?></b>
<p><?= $model->text ?></p>