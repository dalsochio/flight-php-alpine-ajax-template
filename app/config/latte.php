<?php

namespace App\Config;

use Latte\Bridges\Tracy\TracyExtension as LatteTracyExtension;
use Latte\Engine as LatteEngine;
use Latte\Loaders\FileLoader as LatteFileLoader;
use Flight;

$app = Flight::app();

// detect Alpine AJAX request once at config level
$isAlpineRequest = isset($_SERVER['HTTP_X_ALPINE_REQUEST']) && $_SERVER['HTTP_X_ALPINE_REQUEST'] === 'true';

// configure Latte engine once
$latte = new LatteEngine();
$latte->setTempDirectory(__DIR__ . '/../storage/temp');
$latte->setAutoRefresh(true);
$latte->setLoader(new LatteFileLoader(Flight::get('flight.views.path')));
$latte->addExtension(new LatteTracyExtension());

try {
    $app->map('render', function ($template, $data = [], $block = null) use ($isAlpineRequest, $latte) {
        static $pageRendered = false;

        $templateObj = $latte->createTemplate($template, $data);
        $isPage = $templateObj->hasBlock('page');

        // block explicitly requested - always render
        if ($block !== null) {
            echo $latte->renderToString($template, $data, $block) . \PHP_EOL;
            return;
        }

        // component (no content block)
        if (!$isPage) {
            if ($isAlpineRequest) {
                echo $latte->renderToString($template, $data) . \PHP_EOL;
            }
            return;
        }

        // page (has content block)
        if (!$isAlpineRequest) {
            if ($pageRendered) return;
            echo $latte->renderToString($template, $data) . \PHP_EOL;
            $pageRendered = true;
            return;
        }

        // AJAX page - render only content + title
        $title = $data['title'] ?? '';
        echo '<title x-sync id="title">' . ($title ? "$title - " : '') . 'FlightPHP</title>' . \PHP_EOL;
        echo '<main x-target id="main" class="bg-gray-900 p-4 rounded-md">';
        echo $templateObj->capture(fn() => $templateObj->render('page'));
        echo '</main>' . \PHP_EOL;
    });
} catch (\Exception $exception) {
    echo $exception->getMessage();
}
