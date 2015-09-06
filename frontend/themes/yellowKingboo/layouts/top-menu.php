<?php

/* @var $this \yii\web\View */

use common\models\Page;
use yii\helpers\Url;

$items = [

    [
        'title' => 'О сервисе',
        'url' => ['pages/index', 'route' => 'about'],
    ],
    [
        'title' => 'Расширенные возможности',
        'url' => ['pages/index', 'route' => 'extended'],
    ],
    [
        'title' => 'Тарифы',
        'url' => ['pages/index', 'route' => 'plans'],
    ],
    [
        'title' => 'Как подключить',
        'url' => ['pages/index', 'route' => 'how-to-join'],
    ],
    [
        'title' => 'Гарантия безопасности',
        'url' => ['pages/index', 'route' => 'guarantee'],
    ],
    [
        'title' => 'Акции и спецпредложения',
        'url' => ['pages/index', 'route' => 'special'],
    ],

];

$current_page_route = (Page::getCurrent() === null) ? null : Page::getCurrent()->route;

?>

<div class="mobile-menu-button"></div>
<nav class="main-menu">
    <ul>
        <?php foreach($items as $item): ?>
            <li class="<?= ($current_page_route == $item['url']['route']) ? 'active' : '' ?>">
                <a class="<?= ($current_page_route == $item['url']['route']) ? 'selected' : '' ?>"
                    href="<?= Url::toRoute($item['url']) ?>">
                    <?= $item['title'] ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>

