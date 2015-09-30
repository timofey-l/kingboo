<p><?= \Yii::t('support_backend', 'King-Boo support answered your request (thread #{n}). You can look it in <a href="{url}">your personal cabinet</a>.', 
['n' => $model->parent_id, 'url' => 
\Yii::$app->params['partnerProtocol'] . '://' . \Yii::$app->params['partnerDomain'] . '/support/thread?id=' . $model->parent_id]) ?></p>