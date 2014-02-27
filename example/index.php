<?php
require dirname(__DIR__) . '/vendor/autoload.php';
use W4Y\Dom\Selector;

$htmlBody = '
<html>
    <body>
        <p>Hello World!</p>
        <br class="breakClass">
        <a class="example one" href="http://www.example.com">Example 1</a>
        <a class="example two" href="http://www.example.com">Example 2</a>
        <a class="example three" href="http://www.example.com">Example 3</a>
        <a class="example four" href="http://www.example.com">Example 4</a>
        <a class="example five" href="http://www.example.com">Example 5</a>
        <p class="title black">
            <a class="anchorP one" href="http://www.example.com">anchorP 1</a>
            <a class="anchorP two" href="http://www.example.com">anchorP 2</a>
            <a class="anchorP three" href="http://www.example.com">anchorP 3</a>
        </p>
        <p class="body">
            <a class="anchorP one" href="http://www.example.com">anchorP 1</a>
            <a class="anchorP two" href="http://www.example.com">anchorP 2</a>
            <a class="anchorP three" href="http://www.example.com">anchorP 3</a>
        </p>
    </body>
</html>';

$selector = new Selector();
$selector->setBody($htmlBody);

$res = $selector->query('p.body');
$res = $selector->query('a.two', $res->current());

foreach ($res as $v) {
    echo '<pre>' . print_r($v->toArray(), 1);
}
