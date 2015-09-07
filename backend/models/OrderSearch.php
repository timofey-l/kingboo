<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Order;

/**
 * OrderSearch represents the model behind the search form about `\common\models\Order`.
 */
class OrderSearch extends Order
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status', 'contact_address', 'partial_pay_percent', 'hotel_id', 'checkin_fullpay', 'payment_via_bank_transfer', 'sum_currency_id', 'pay_sum_currency_id', 'payment_system_sum_currency_id'], 'integer'],
            [['created_at', 'updated_at', 'number', 'contact_email', 'contact_phone', 'contact_name', 'contact_surname', 'dateFrom', 'dateTo', 'lang', 'payment_url', 'code', 'partner_number', 'additional_info'], 'safe'],
            [['sum', 'pay_sum', 'payment_system_sum'], 'number'],
            [['partial_pay', 'viewed'], 'boolean'],
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
        $query = Order::find();

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
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'status' => $this->status,
            'contact_address' => $this->contact_address,
            'dateFrom' => $this->dateFrom,
            'dateTo' => $this->dateTo,
            'sum' => $this->sum,
            'partial_pay' => $this->partial_pay,
            'partial_pay_percent' => $this->partial_pay_percent,
            'pay_sum' => $this->pay_sum,
            'hotel_id' => $this->hotel_id,
            'viewed' => $this->viewed,
            'checkin_fullpay' => $this->checkin_fullpay,
            'payment_via_bank_transfer' => $this->payment_via_bank_transfer,
            'sum_currency_id' => $this->sum_currency_id,
            'pay_sum_currency_id' => $this->pay_sum_currency_id,
            'payment_system_sum' => $this->payment_system_sum,
            'payment_system_sum_currency_id' => $this->payment_system_sum_currency_id,
        ]);

        $query->andFilterWhere(['like', 'number', $this->number])
            ->andFilterWhere(['like', 'contact_email', $this->contact_email])
            ->andFilterWhere(['like', 'contact_phone', $this->contact_phone])
            ->andFilterWhere(['like', 'contact_name', $this->contact_name])
            ->andFilterWhere(['like', 'contact_surname', $this->contact_surname])
            ->andFilterWhere(['like', 'lang', $this->lang])
            ->andFilterWhere(['like', 'payment_url', $this->payment_url])
            ->andFilterWhere(['like', 'code', $this->code])
            ->andFilterWhere(['like', 'partner_number', $this->partner_number])
            ->andFilterWhere(['like', 'additional_info', $this->additional_info]);

        return $dataProvider;
    }
}
