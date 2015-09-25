<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\BillingPaysYandex;

/**
 * BillingPaysYandexSearch represents the model behind the search form about `\common\models\BillingPaysYandex`.
 */
class BillingPaysYandexSearch extends BillingPaysYandex
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'invoiceId', 'billing_invoice_id'], 'integer'],
            [['payed', 'checked'], 'boolean'],
            [['check_post_dump', 'avisio_post_dump'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = BillingPaysYandex::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'invoiceId' => $this->invoiceId,
            'payed' => $this->payed,
            'checked' => $this->checked,
            'billing_invoice_id' => $this->billing_invoice_id,
        ]);

        $query->andFilterWhere(['like', 'check_post_dump', $this->check_post_dump])
            ->andFilterWhere(['like', 'avisio_post_dump', $this->avisio_post_dump]);

        return $dataProvider;
    }
}
