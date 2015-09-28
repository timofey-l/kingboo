<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\BillingIncome;

/**
 * BillingIncomeSearch represents the model behind the search form about `\common\models\BillingIncome`.
 */
class BillingIncomeSearch extends BillingIncome
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'account_id', 'invoice_id', 'currency_id'], 'integer'],
            [['sum'], 'number'],
            [['date'], 'safe'],
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
        $query = BillingIncome::find();

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
            'sum' => $this->sum,
            'date' => $this->date,
            'account_id' => $this->account_id,
            'invoice_id' => $this->invoice_id,
            'currency_id' => $this->currency_id,
        ]);

        return $dataProvider;
    }
}
