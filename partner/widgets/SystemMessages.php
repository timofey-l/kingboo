<?php
namespace partner\widgets;

use \Yii;
use yii\base\Widget;
use yii\bootstrap\Alert;
use yii\helpers\Html;

class SystemMessages extends \yii\bootstrap\Widget
{
    public $alertTypes = [
        'error'   => 'alert-danger',
        'danger'  => 'alert-danger',
        'success' => 'alert-success',
        'info'    => 'alert-info',
        'warning' => 'alert-warning'
    ];

    public $faIcons = [
        'error'   => 'fa fa-ban',
        'danger'  => 'fa fa-exclamation-circle',
        'success' => 'fa fa-check',
        'info'    => 'fa fa-info-circle',
        'warning' => 'fa fa-exclamation-triangle'
    ];

    public $faIcon = 'exclamation-circle';
    public $labelType = 'default';
    public $width = '285';

    public function init()
    {
        parent::init();

        $messages = \Yii::$app->automaticSystemMessages->actualMessages();

        if (count($messages) == 0) {
            return '';
        }

        $message_items = '';
        foreach ($messages as $id => $message) {
            if (isset($this->alertTypes[$message['type']])) {
                $message_items .= 
<<<HTML
                    <li id="w-sysmsg-$id"><!-- start message -->
                        <div class="alert {$this->alertTypes[$message['type']]} system-message-alert">
                            <i class="{$this->faIcons[$message['type']]}"></i>
                            {$message['title']}<br />
                            <small>{$message['text']}</small>
                        </div>
                    </li><!-- end message -->
HTML;


//                $message_items .= Html::tag('li', Html::tag('div', $message['text'], ['class' => $this->alertTypes[$message['type']]]) , []);
            }
        }
        $count = count($messages);
        $out = 
<<<HTML
            <li class="dropdown messages-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <i class="fa fa-{$this->faIcon}"></i>
                    <span class="label label-{$this->labelType}">{$count}</span>
                </a>
                <ul class="dropdown-menu" style="width: {$this->width}px;">
                    <li>
                        <ul class="menu">
                            {$message_items}
                        </ul>
                    </li>
                </ul>
            </li>
HTML;

        echo $out;
        return '';
    }

}