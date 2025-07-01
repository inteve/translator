<?php

declare(strict_types=1);

use Inteve\Translator\Ast\Element;
use Inteve\Translator\Ast\Parameter;
use Inteve\Translator\MessageElement;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


test('Formatting', function () {
	$element = new Element('tag', [
		'only-string' => 'String',
		'only-parameter' => new Parameter('param1'),
		'only-bool' => FALSE,
		'string-and-parameter' => [
			'Lorem',
			new Parameter('param2'),
		]
	]);
	$element->addNode(new Parameter('param3'));
	$element->addText('/Text content');

	$messageElement = $element->format([
		'param1' => 'Value 1',
		'param2' => 'Value 2',
		'param3' => 'Value 3',
	], Tests::createLocale('en'));

	assert($messageElement instanceof MessageElement);

	Assert::same([
		'only-string' => 'String',
		'only-parameter' => 'Value 1',
		'only-bool' => FALSE,
		'string-and-parameter' => 'LoremValue 2',
	], $messageElement->getAttributes());

	Assert::same([
		'Value 3',
		'/Text content',
	], $messageElement->getChildren());
});
