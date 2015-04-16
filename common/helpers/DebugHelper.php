<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace common\helpers;

class DebugHelper 
{
    public static function grid($model,$where=[]) {
        $rows = $model::find()->where($where)->all();
        $fields = $model->attributeLabels();
        $s = '<table class="table"><tr>';
        foreach ($fields as $k=>$v) {
            $s .= "<td>$k</td>";
        }
        $s .= '</tr>';
        foreach ($rows as $row) {
            $s .= '<tr>';
            foreach ($fields as $k=>$v) {
                $s .= "<td>{$row[$k]}</td>";
            }
            $s .= '</tr>';
        }
        $s .= '</table>';
        return $s;
    }

}
