<?php

/* @var $this yii\web\View */

$this->title = \Yii::t('support', 'Support');

?>

<?= \yii\helpers\Html::a(\Yii::t('support', 'Send new message'), ['create'],[
    'class' => 'btn btn-success'
]) ?>

<?= \yii\grid\GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        [
            'label' => \Yii::t('support', 'Text'),
            'format' => 'raw',
            'value' => function($item) {
                return \yii\helpers\Html::a(\yii\helpers\Html::encode($item->text), ['thread', 'id' => $item->id]);
            }
        ],
        [
            'label' => \Yii::t('support', 'Created at'),
            'value' => function($item) {
                return (new DateTime($item->created_at))->format(\Yii::t('support', 'd/m/Y H:i:s'));
            }
        ],
        [
            'label' => \Yii::t('support', 'New messages'),
            'value' => function($item) {
                return $item->newMessages;
            }
        ]
    ]
]) ?>