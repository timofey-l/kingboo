<?php

/* @var $this yii\web\View */

$this->title = \Yii::t('support', 'Support');

?>

<?= \yii\grid\GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        [
            'label' => \Yii::t('support', 'Text'),
            'format' => 'raw',
            'value' => function($item) {
                $result = \yii\helpers\Html::a(\yii\helpers\Html::encode($item->title), ['thread', 'id' => $item->id]);
                $count = \common\models\SupportMessage::find()->where(['parent_id' => $item->id, 'unread_admin' => 1])->count() + $item->unread_admin;
                if ($count) {
                    $result = \yii\helpers\Html::tag('span', \Yii::t('support', 'Unread messages: {n}', ['n' => $count]), [
                        'class' => 'label label-warning',
                    ]) . "<br>" . $result;
                }

                return $result;
            }
        ],
        [
            'label' => \Yii::t('support_backend', 'Partner'),
            'value' => function ($item) {
                return $item->partner->username . ' (' . $item->partner->email . ')';
            }
        ],
        [
            'label' => \Yii::t('support', 'Created at'),
            'value' => function($item) {
                return (new DateTime($item->created_at))->format(\Yii::t('support', 'd/m/Y H:i:s'));
            }
        ],
        [
            'label' => \Yii::t('support', 'Updated at'),
            'value' => function($item) {
                return (new DateTime($item->updated_at))->format(\Yii::t('support', 'd/m/Y H:i:s'));
            }
        ],
        [
            'label' => \Yii::t('support', 'Total messages'),
            'value' => function($item) {
                return $item->totalMessages;
            }
        ]
    ]
]) ?>