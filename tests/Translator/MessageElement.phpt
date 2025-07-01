<?php

declare(strict_types=1);

use Inteve\Translator\MessageElement;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


test('Name', function () {
	$element = new MessageElement('my-Tag');

	Assert::true($element->is('MY-TAG'));
	Assert::false($element->is('my-tag2'));

	Assert::same('my-tag', $element->getName());
});


test('Attributes', function () {
	$element = new MessageElement('tag', [
		'string' => 'value',
		'true' => TRUE,
		'false' => FALSE,
	]);

	Assert::true($element->hasAttribute('string'));
	Assert::true($element->hasAttribute('true'));
	Assert::true($element->hasAttribute('false'));
	Assert::false($element->hasAttribute('non-exists'));

	Assert::same('value', $element->getAttribute('string'));
	Assert::true($element->getAttribute('true'));
	Assert::false($element->getAttribute('false'));

	Assert::exception(function () use ($element) {
		$element->getAttribute('non-exists');
	}, \Inteve\Translator\InvalidStateException::class, "Missing attribute 'non-exists'.");

	Assert::same([
		'string' => 'value',
		'true' => TRUE,
		'false' => FALSE,
	], $element->getAttributes());

	Assert::same([
		'string' => 'value',
	], $element->getAttributes(['non-exists', 'string']));
});


test('Children', function () {
	$empty = new MessageElement('empty-tag');

	Assert::same([], $empty->getChildren());
});


test('ToText', function () {
	$empty = new MessageElement('empty-tag');
	Assert::same('', $empty->toText());

	$strings = new MessageElement('tag', [], ['Lorem', 'Ipsum dolor']);
	Assert::same('LoremIpsum dolor', $strings->toText());

	$elements = new MessageElement('empty-tag', [], [
		MessageElement::el('tag', [], ['Lorem']),
		MessageElement::el('tag', [], ['Ipsum dolor']),
	]);
	Assert::same('LoremIpsum dolor', $elements->toText());
});
