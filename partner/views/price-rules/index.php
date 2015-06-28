<?php
/* @var $this yii\web\View */
/** @var \common\models\PriceRules[] $price_rules */
$this->title = \Yii::t('partner_pricerules', 'Price rules');

?>

<div class="btn-group">
    <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
            aria-expanded="false">
        <?= \Yii::t('partner_pricerules', 'Добавить правило') ?> <span class="caret"></span>
    </button>
    <ul class="dropdown-menu">
        <?php foreach (\common\components\ListPriceRules::$_list as $item): ?>
            <li><?= \yii\helpers\Html::a(\common\components\ListPriceRules::getTitleById($item['id']), ['create', 'type' => $item['id']]) ?></li>
        <?php endforeach; ?>
    </ul>
</div>

<hr/>

<?php if (count($price_rules) > 0): ?>
    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">
                <?= \Yii::t('partner_pricerules', 'Partner rules') ?>
            </h3>
        </div>
        <div class="box-body">
            <table class="table">
                <thead>
                <tr>
                    <th><?= \Yii::t('partner_pricerules', 'Type') ?></th>
                    <th><?= \Yii::t('partner_pricerules', 'Rooms') ?></th>
                    <th><?= \Yii::t('partner_pricerules', 'Conditions') ?></th>
                    <th><?= \Yii::t('partner_pricerules', 'Status') ?></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($price_rules as $price_rule): ?>
                    <tr>
                        <td><?= \common\components\ListPriceRules::getTitleById($price_rule->type) ?></td>
                        <td>
                            <?php foreach ($price_rule->rooms as $room): ?>
                                <span
                                    class="label label-default"><?= $room->{'title_' . \common\models\Lang::$current->url} ?></span>
                                <br/>
                            <?php endforeach; ?>
                        </td>
                        <td></td>
                        <td></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php else: ?>

    <?= \Yii::t('partner_pricerules', 'Nothing was found') ?>

<?php endif; ?>
