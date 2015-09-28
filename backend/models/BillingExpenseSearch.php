<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\BillingExpense;

/**
 * BillingExpenseSearch represents the model behind the search form about `\common\models\BillingExpense`.
 */
class BillingExpenseSearch extends BillingExpense
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'account_id', 'service_id', 'sum_currency_id', 'currency_id'], 'integer'],
            [['sum'], 'number'],
            [['date', 'comment'], 'safe'],
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
        $query = BillingExpense::find();

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
            'service_id' => $this->service_id,
            'sum_currency_id' => $this->sum_currency_id,
            'currency_id' => $this->currency_id,
        ]);

        $query->andFilterWhere(['like', 'comment', $this->comment]);

        return $dataProvider;
    }
}
