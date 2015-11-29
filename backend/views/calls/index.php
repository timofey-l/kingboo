<?php
/* @var $this yii\web\View */

?>
<h1>Список зарегистрированных клиентов</h1>

<?= \yii\grid\GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        'date:datetime',
        'email',
        'phone',
        'company_name',
        array(
            'label' => 'Учетная запись клиента',
            'format' => 'raw',
            'value' => function($model) {
                $checked = ($model->partner->checked)
                    ? '<i class="glyphicon glyphicon-ok text-success"></i>'
                    : '<i class="glyphicon glyphicon-remove text-danger"></i>';
                $urlLogin = \yii\helpers\Url::toRoute(array('login-to', 'id' => $model->partner->id));
                $loginStr = ($model->partner->checked)
                    ? '<br>
                    <a target="_blank" disabled href="'.$urlLogin.'">Личный кабинет</a>'
                    : "";
                $out = <<<HTML
                    <b>id:{$model->partner->id}</b> $checked
                    $loginStr
HTML;
                foreach ($model->partner->hotels as $hotel) {
                    $out .= "<br><a href='{$hotel->local_url}' target='_blank'>{$hotel->name}</a>";
                }
                return $out;

            }
        )
    ],
]) ?>

