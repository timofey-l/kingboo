<?php
use yii\widgets\Breadcrumbs;
use dmstr\widgets\Alert;
?>
<aside class="right-side">
    <section class="content-header">
        <h1>
            <?php
            if ($this->title !== null) {
                echo $this->title;
            } else {
                echo \yii\helpers\Inflector::camel2words(\yii\helpers\Inflector::id2camel($this->context->module->id));
                echo ($this->context->module->id !== \Yii::$app->id) ? '<small>Module</small>' : '';
            } ?>
        </h1>
        <?=
        Breadcrumbs::widget(
            [
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]
        ) ?>
    </section>

    <section class="content">
        <?= Alert::widget() ?>
        <?= $content ?>
    </section>

</aside>