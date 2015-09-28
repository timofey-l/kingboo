<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\BillingAccountServices;

/**
 * BillingAccountServicesSearch represents the model behind the search form about `\common\models\BillingAccountServices`.
 */
class BillingAccountServicesSearch extends BillingAccountServices
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'account_id', 'service_id'], 'integer'],
            [['add_date', 'end_date'], 'safe'],
            [['active'], 'boolean'],
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
        $query = BillingAccountServices::find();

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
            'service_id' => $this->service_id,
            'add_date' => $this->add_date,
            'end_date' => $this->end_date,
            'active' => $this->active,
        ]);

        return $dataProvider;
    }
}
