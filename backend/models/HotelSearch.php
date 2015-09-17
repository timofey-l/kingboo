<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Hotel;

/**
 * HotelSearch represents the model behind the search form about `\common\models\Hotel`.
 */
class HotelSearch extends Hotel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'partner_id', 'category', 'currency_id', 'partial_pay_percent'], 'integer'],
            [['name', 'address_ru', 'address_en', 'description_ru', 'timezone', 'description_en', 'title_ru', 'title_en', 'domain', 'less', 'css', 'contact_phone', 'contact_email'], 'safe'],
            [['lng', 'lat'], 'number'],
            [['allow_partial_pay'], 'boolean'],
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
        $query = Hotel::find();

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
            'partner_id' => $this->partner_id,
            'lng' => $this->lng,
            'lat' => $this->lat,
            'category' => $this->category,
            'currency_id' => $this->currency_id,
            'allow_partial_pay' => $this->allow_partial_pay,
            'partial_pay_percent' => $this->partial_pay_percent,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'address_ru', $this->address_ru])
            ->andFilterWhere(['like', 'address_en', $this->address_en])
            ->andFilterWhere(['like', 'description_ru', $this->description_ru])
            ->andFilterWhere(['like', 'timezone', $this->timezone])
            ->andFilterWhere(['like', 'description_en', $this->description_en])
            ->andFilterWhere(['like', 'title_ru', $this->title_ru])
            ->andFilterWhere(['like', 'title_en', $this->title_en])
            ->andFilterWhere(['like', 'domain', $this->domain])
            ->andFilterWhere(['like', 'less', $this->less])
            ->andFilterWhere(['like', 'css', $this->css])
            ->andFilterWhere(['like', 'contact_phone', $this->contact_phone])
            ->andFilterWhere(['like', 'contact_email', $this->contact_email]);

        return $dataProvider;
    }
}
