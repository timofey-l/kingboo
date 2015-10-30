<?php

use partner\models\PartnerUser;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\CallsStatsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'Calls Stats');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="calls-stats-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('backend', 'Create Calls Stats'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            [
                'format' => 'raw',
                'label' => "Партнёр",
                'value' => function ($model) {
                    $partner = PartnerUser::findOne(['email' => $model->email]);
                    if ($partner) {
                        return Html::a('id:' . $partner->id, ['partner-user/update', 'id' => $partner->id])
                            . "<br><span>Checked:&nbsp;" . (($partner->checked) ? '<i class="glyphicon glyphicon-ok text-success"></i>' : '<i class="glyphicon glyphicon-remove text-danger"></i>') . '</span>';
                    } else {
                        return "";
                    }
                }
            ],
            'date:datetime',
            'company_name',
            'email:email',
            'phone',
            // 'contact_person',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
