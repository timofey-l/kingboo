<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\BillingInvoice;

/**
 * BillingInvoiceSearch represents the model behind the search form about `\common\models\BillingInvoice`.
 */
class BillingInvoiceSearch extends BillingInvoice
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'account_id', 'currency_id', 'system'], 'integer'],
            [['sum'], 'number'],
            [['created_at'], 'safe'],
            [['payed'], 'boolean'],
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
        $query = BillingInvoice::find();

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
            'account_id' => $this->account_id,
            'sum' => $this->sum,
            'currency_id' => $this->currency_id,
            'created_at' => $this->created_at,
            'payed' => $this->payed,
            'system' => $this->system,
        ]);

        return $dataProvider;
    }
}
