<?php

use common\models\Lang;
use yii\web\View;
use yii\helpers\Html;
use yii\web\JqueryAsset;
use yii\helpers\Url;

/** @var View $this */

$this->registerJsFile(\Yii::$app->assetManager->publish('@bower/bootstrap')[1].'/js/transition.js', [
    'depends' => JqueryAsset::className(),
]);
$this->registerJsFile(\Yii::$app->assetManager->publish('@bower/bootstrap')[1].'/js/collapse.js', [
    'depends' => JqueryAsset::className(),
]);
$this->registerJs("
	if (document.location.hash.length > 0) {
		$(document.location.hash).collapse('show');
	}
");
$this->registerJs("
  function goTo(anchor) {
    $('#accordion>div>.collapse').collapse('hide');
    $(anchor).collapse('show');
    document.location.hash = anchor;
    var top = $(anchor).offset().top;
    return window.scroll(0, top);
  }
", View::POS_HEAD);

$lang = Lang::$current;

$this->title = $title;

?>
<h1><?= $title ?></h1>
<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
<?php 
	$n = 1;
	foreach ($FAQs as $faq) { 
?>
  <div class="panel panel-warning">
    <div class="panel-heading" role="tab" id="heading<?= $faq->id ?>">
      <h4 class="panel-title">
        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse<?= $faq->id ?>" name="collapse<?= $faq->id ?>" aria-expanded="true" aria-controls="collapse<?= $faq->id ?>">
          <?= $faq->{'title_' . $lang->url} ?>
        </a>
      </h4>
    </div>
    <div id="collapse<?= $faq->id ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading<?= $faq->id ?>">
      <div class="panel-body">
        <?= $faq->{'content_' . $lang->url} ?>
      </div>
    </div>
  </div>
<?php 
		$n++;
	} 
?>
</div>
