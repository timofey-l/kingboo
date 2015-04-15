<?php

use yii\helpers\Html;
use yii\widgets\ListView;
use yii\web\ForbiddenHttpException;

/* @var $this yii\web\View */
/* @var $searchModel partner\models\RoomSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

//Получаем отель из УРЛа, если он есть 
$hotel_id = Yii::$app->request->get('hotel_id',false);
if ($hotel_id) {
    $hotel = \common\models\Hotel::findOne($hotel_id);
    if ($hotel->partner_id != \Yii::$app->user->id) { //проверяем права доступа
        throw new ForbiddenHttpException();
    }
}

$this->title = Yii::t('rooms', 'Rooms');    //var_dump($this->itemTemplate);
if ($hotel_id) {
    $this->params['breadcrumbs'][] = ['label' => $hotel->{'title_' . \common\models\Lang::$current->url}, 'url' => 'index', 'template' => 'hotel'];
}
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="room-index">

    <h1><?= Html::encode($this->title) ?></h1>
    
    <?php 
        if ($hotel_id) {
            echo $this->render('_hotel_rooms_list',['hotel' => $hotel]);
        } else {
            echo $this->render('_search', ['model' => $searchModel]);
            echo '<p>'.Html::a(Yii::t('rooms', 'Create Room'), ['create'], ['class' => 'btn btn-success']).'</p>';
            echo ListView::widget([
                'dataProvider' => $dataProvider,
                'itemOptions' => ['class' => 'item'],
                'itemView' => function ($model, $key, $index, $widget) {
                    return Html::a(Html::encode($model->id), ['view', 'id' => $model->id]);
                },
            ]);            
        }
    ?>

</div>
