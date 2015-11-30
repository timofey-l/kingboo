<?php
/* @var $this yii\web\View */

?>
<h1>Список зарегистрированных клиентов</h1>

<?= \yii\grid\GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        [
            'label' => 'Дата',
            'format' => ['date', 'php:d.m.Y H:i'],
            'attribute' => 'date',
        ],
        [
            'label' => 'Демо',
            'format' => 'raw',
            'value' => function($model) {
                $now = new DateTime();
                $demo = new DateTime($model->partner->demo_expire);
                $color = '#000';
                if ($now >= $demo) {
                    $color = '#ff0000';
                } elseif ($now->add(date_interval_create_from_date_string(Yii::$app->params['partner.warning.daysBeforeDemoEnd'] . ' days')) >= $demo) {
                    $color = 'orange';
                }
                $d = Yii::$app->formatter->asDatetime($model->partner->demo_expire, 'php:d.m.Y');
                $out = "<span style=\"color:{$color}\">{$d}</span>";
                return $out;
            }
        ],
        'email',
        'phone',
        'company_name',
        'contact_person',
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

