<?php
namespace partner\widgets;

use \Yii;
use yii\base\Widget;
use yii\bootstrap\Alert;
use yii\helpers\Html;

class MessagesToUser extends \yii\bootstrap\Widget
{
    public $alertTypes = [
        'error'   => 'alert-danger',
        'danger'  => 'alert-danger',
        'success' => 'alert-success',
        'info'    => 'alert-info',
        'warning' => 'alert-warning'
    ];

    public $faIcons = [
        'error'   => 'fa fa-exclamation-circle',
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

        $session = \Yii::$app->getSession();
        $messages = $session->get('messagesToUser', []);

        if (count($messages) == 0) {
            return '';
        }

        $message_items = '';
        foreach ($messages as $id => $message) {
            if (isset($this->alertTypes[$message['type']])) {
                $message_items .= <<<HTML
<li><!-- start message -->
    <p class="callout callout-{$message['type']} message">
        <i class="{$this->faIcons[$message['type']]} pull-left"></i>
        <small>{$message['text']}</small>
    </p>
</li><!-- end message -->
HTML;


//                $message_items .= Html::tag('li', Html::tag('div', $message['text'], ['class' => $this->alertTypes[$message['type']]]) , []);
            }
        }
        $count = count($messages);
        $out = <<<HTML
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