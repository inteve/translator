<?php

declare(strict_types=1);

use Inteve\Translator\Ast\Parameter;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


test('Basic', function () {
	$parameter = new Parameter('value');
	Assert::same('My Value', $parameter->format([
		'value' => 'My Value',
	], Tests::createLocale('en')));
});


test('Missing parameter', function () {
	$parameter = new Parameter('value');
	Assert::same('', $parameter->format([
		'value' => NULL,
	], Tests::createLocale('en')));
});
