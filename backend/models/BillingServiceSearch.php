<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\BillingService;

/**
 * BillingServiceSearch represents the model behind the search form about `\common\models\BillingService`.
 */
class BillingServiceSearch extends BillingService
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'currency_id'], 'integer'],
            [['name_ru', 'description_ru', 'name_en', 'description_en'], 'safe'],
            [['archived', 'default', 'monthly', 'unique'], 'boolean'],
            [['enable_cost', 'monthly_cost'], 'number'],
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
        $query = BillingService::find();

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
            'archived' => $this->archived,
            'default' => $this->default,
            'monthly' => $this->monthly,
            'unique' => $this->unique,
            'currency_id' => $this->currency_id,
            'enable_cost' => $this->enable_cost,
            'monthly_cost' => $this->monthly_cost,
        ]);

        $query->andFilterWhere(['like', 'name_ru', $this->name_ru])
            ->andFilterWhere(['like', 'description_ru', $this->description_ru])
            ->andFilterWhere(['like', 'name_en', $this->name_en])
            ->andFilterWhere(['like', 'description_en', $this->description_en]);

        return $dataProvider;
    }
}
