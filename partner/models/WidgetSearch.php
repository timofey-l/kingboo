<?php

namespace partner\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Widget;

/**
 * WidgetSearch represents the model behind the search form about `common\models\Widget`.
 */
class WidgetSearch extends Widget
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'hotel_id'], 'integer'],
            [['hash_code', 'params', 'comment', 'compiled_js', 'compiled_css'], 'safe'],
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
        $query = Widget::find();

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
            'hotel_id' => $this->hotel_id,
        ]);

        $query->andFilterWhere(['like', 'hash_code', $this->hash_code])
            ->andFilterWhere(['like', 'params', $this->params])
            ->andFilterWhere(['like', 'comment', $this->comment])
            ->andFilterWhere(['like', 'compiled_js', $this->compiled_js])
            ->andFilterWhere(['like', 'compiled_css', $this->compiled_css]);

        return $dataProvider;
    }
}
