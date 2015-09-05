<?php

use common\models\Lang;
use common\models\Page;
use yii\helpers\Url;
use yii\web\View;

/** @var View $this */
/** @var Page $page */

$lang = Lang::$current;

$this->title = $page->{'title_' . $lang->url};

?>

<?= $page->{'content_' . $lang->url} ?>

