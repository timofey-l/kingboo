<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\OrderItem;

/**
 * OrderItemSearch represents the model behind the search form about `\common\models\OrderItem`.
 */
class OrderItemSearch extends OrderItem
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'room_id', 'order_id', 'adults', 'children', 'kids', 'guest_address', 'sum_currency_id', 'pay_sum_currency_id', 'payment_system_sum_currency_id'], 'integer'],
            [['sum', 'pay_sum', 'payment_system_sum'], 'number'],
            [['guest_name', 'guest_surname'], 'safe'],
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
        $query = OrderItem::find();

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
            'room_id' => $this->room_id,
            'order_id' => $this->order_id,
            'adults' => $this->adults,
            'children' => $this->children,
            'kids' => $this->kids,
            'sum' => $this->sum,
            'guest_address' => $this->guest_address,
            'sum_currency_id' => $this->sum_currency_id,
            'pay_sum' => $this->pay_sum,
            'pay_sum_currency_id' => $this->pay_sum_currency_id,
            'payment_system_sum' => $this->payment_system_sum,
            'payment_system_sum_currency_id' => $this->payment_system_sum_currency_id,
        ]);

        $query->andFilterWhere(['like', 'guest_name', $this->guest_name])
            ->andFilterWhere(['like', 'guest_surname', $this->guest_surname]);

        return $dataProvider;
    }
}
