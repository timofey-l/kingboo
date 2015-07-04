<?php
/* @var $this yii\web\View */
/** @var \common\models\PriceRules[] $price_rules */
$this->title = \Yii::t('partner_pricerules', 'Price rules');
$this->params['breadcrumbs'][] = $this->title;
?>

<?= \yii\helpers\Html::a(
    '<i class="fa text-bold">%</i>'.\Yii::t('partner_pricerules', 'Add discount'), ['create', 'type' => 0],[
    'class' => 'btn btn-app'
]) ?>

<?php if (count($price_rules) > 0): ?>
    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">
                <?= \Yii::t('partner_pricerules', 'Price rules') ?>
            </h3>
        </div>
        <div class="box-body">
            <table class="table table-hover">
                <thead>
                <tr>
                    <th><?= \Yii::t('partner_pricerules', 'Type') ?></th>
                    <th><?= \Yii::t('partner_pricerules', 'Rooms') ?></th>
                    <th><?= \Yii::t('partner_pricerules', 'Options') ?></th>
                    <th><?= \Yii::t('partner_pricerules', 'Status') ?></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($price_rules as $price_rule): ?>
                    <tr>
                        <td>
                            <?= \common\components\ListPriceRules::getTitleById($price_rule->type) ?><?php if ($price_rule->type == 0): ?>
                                <span><?= $price_rule->value ?><?php if ($price_rule->valueType == 0): ?>
                                    %
                                <?php else: ?>
                                    <br/>
                                    <small
                                        class="text-muted"><?= \Yii::t('partner_pricerules', 'Fixed sum. Currency depends from hotel\'s currency.') ?></small>
                                <?php endif; ?>
                                </span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php foreach ($price_rule->rooms as $room): ?>
                                <span
                                    class="label label-default"><?= $room->{'title_' . \common\models\Lang::$current->url} ?></span>
                                <br/>
                            <?php endforeach; ?>
                        </td>
                        <td>
                            <?php if ($price_rule->type == 0): ?>
                                <?php if ($price_rule->additive): ?>
                                    <span class="label label-info">
                                        <?= \Yii::t('partner_pricerules', 'Additive') ?>
                                    </span>
                                <?php endif; ?>
                                <?php if ($price_rule->dateFrom && $price_rule->dateTo): ?>
                                    <div>
                                        <?php if ($price_rule->applyForCheckIn): ?>
                                            <?= \Yii::t('partner_pricerules', 'Check-in date range') ?>:
                                        <?php else: ?>
                                            <?= \Yii::t('partner_pricerules', 'Living dates range') ?>:
                                        <?php endif; ?>
<!--                                        <br/>-->
                                        <span class="label label-warning">
                                        <?= (new DateTime($price_rule->dateFrom))->format(\Yii::t('partner_pricerules', 'd/m/Y')) ?>
                                    </span>&ndash;<span class="label label-warning">
                                        <?= (new DateTime($price_rule->dateTo))->format(\Yii::t('partner_pricerules', 'd/m/Y')) ?>
                                    </span>
                                    </div>
                                <?php endif; ?>

                                <?php if ($price_rule->dateFromB && $price_rule->dateToB): ?>
                                    <div>
                                        <?= \Yii::t('partner_pricerules', 'Booking dates range') ?>:
                                    <span class="label label-warning">
                                        <?= (new DateTime($price_rule->dateFromB))->format(\Yii::t('partner_pricerules', 'd/m/Y')) ?>
                                    </span>&ndash;<span class="label label-warning">
                                        <?= (new DateTime($price_rule->dateToB))->format(\Yii::t('partner_pricerules', 'd/m/Y')) ?>
                                    </span>
                                    </div>
                                <?php endif; ?>

                                <?php if ($price_rule->minSum): ?>
                                    <div>
                                        <?= \Yii::t('partner_pricerules', 'Minimal discount sum limit') ?>:
                                        <span class="label label-warning">
                                            <?= $price_rule->minSum ?>
                                        </span>
                                    </div>
                                <?php endif; ?>

                                <?php if ($price_rule->maxSum): ?>
                                    <div>
                                        <?= \Yii::t('partner_pricerules', 'Maximal discount sum limit') ?>:
                                        <span class="label label-warning">
                                            <?= $price_rule->maxSum ?>
                                        </span>
                                    </div>
                                <?php endif; ?>

                                <?php if ($price_rule->code): ?>
                                    <div>
                                        <?= \Yii::t('partner_pricerules', 'Promo code') ?>:
                                        <span class="label label-warning">
                                            <?= $price_rule->code ?>
                                        </span>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($price_rule->active): ?>
                                <span class="label label-success">
                                <?= \Yii::t('partner_pricerules', 'active') ?>
                                </span><?= \yii\helpers\Html::a(\Yii::t('partner_pricerules', 'deactivate'), ['deactivate', 'id' => $price_rule->id], [
                                    'class' => 'btn btn-sm btn-link',
                                    'data' => [
                                        'confirm' => Yii::t('partner_pricerules', 'Are you sure you want to deactivate this price rule?'),
                                        'method' => 'post',
                                    ],
                                ]) ?>
                            <?php else: ?>
                                <span class="label label-danger">
                                    <?= \Yii::t('partner_pricerules', 'inactive') ?>
                                </span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php else: ?>

    <?= \Yii::t('partner_pricerules', 'Nothing was found') ?>

<?php endif; ?>
