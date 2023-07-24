<?php

require __DIR__ . '/../vendor/autoload.php';

Tester\Environment::setup();


/**
 * @param  string $title
 * @param  callable $cb
 * @return void
 */
function test($title, callable $cb)
{
	$cb();
}
