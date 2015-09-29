<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Pay;

/**
 * PaySearch represents the model behind the search form about `\common\models\Pay`.
 */
class PaySearch extends Pay
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'invoiceId', 'orderSumCurrencyPaycash', 'orderSumBankPaycash', 'shopSumCurrencyPaycash', 'shopSumBankPaycash', 'paymentType'], 'integer'],
            [['checked', 'payed'], 'boolean'],
            [['order_number', 'customerNumber', 'orderCreatedDatetime', 'paymentDatetime', 'paymentPayerCode', 'postParams', 'shopId'], 'safe'],
            [['orderSumAmount', 'shopSumAmount'], 'number'],
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
        $query = Pay::find();

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
            'checked' => $this->checked,
            'payed' => $this->payed,
            'invoiceId' => $this->invoiceId,
            'orderCreatedDatetime' => $this->orderCreatedDatetime,
            'orderSumAmount' => $this->orderSumAmount,
            'orderSumCurrencyPaycash' => $this->orderSumCurrencyPaycash,
            'orderSumBankPaycash' => $this->orderSumBankPaycash,
            'shopSumAmount' => $this->shopSumAmount,
            'shopSumCurrencyPaycash' => $this->shopSumCurrencyPaycash,
            'shopSumBankPaycash' => $this->shopSumBankPaycash,
            'paymentType' => $this->paymentType,
        ]);

        $query->andFilterWhere(['like', 'order_number', $this->order_number])
            ->andFilterWhere(['like', 'customerNumber', $this->customerNumber])
            ->andFilterWhere(['like', 'paymentDatetime', $this->paymentDatetime])
            ->andFilterWhere(['like', 'paymentPayerCode', $this->paymentPayerCode])
            ->andFilterWhere(['like', 'postParams', $this->postParams])
            ->andFilterWhere(['like', 'shopId', $this->shopId]);

        return $dataProvider;
    }
}
