<?php

/* @var $this \yii\web\View */

use common\models\Page;
use yii\helpers\Url;

$items = [

    [
        'title' => 'Главная',
        'url' => ['/'],
    ],
    [
        'title' => 'О сервисе',
        'url' => ['pages/index', 'route' => 'about'],
    ],
    [
        'title' => 'Функционал системы',
        'url' => ['pages/index', 'route' => 'facilities'],
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
        'title' => 'Вопросы и ответы',
        'url' => ['faq/'],
    ],
    /*[
        'title' => 'Гарантия безопасности',
        'url' => ['pages/index', 'route' => 'guarantee'],
    ],
    [
        'title' => 'Акции и спецпредложения',
        'url' => ['pages/index', 'route' => 'special'],
    ],*/

];

$current_page_route = (Page::getCurrent() === null) ? null : Page::getCurrent()->route;

?>

<div class="mobile-menu-button"></div>
<nav class="main-menu">
    <ul>
        <?php foreach($items as $item): ?>
            <?php $active = ($current_page_route && isset($item['url']['route']) && $current_page_route === $item['url']['route']) 
                || (!$current_page_route && URL::current() == '/' . trim($item['url'][0],'/')); ?>
            <li class="<?= $active ? 'active' : '' ?>">
                <a class="<?= $active ? 'selected' : '' ?>"
                    href="<?= Url::toRoute($item['url']) ?>">
                    <?= $item['title'] ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>

