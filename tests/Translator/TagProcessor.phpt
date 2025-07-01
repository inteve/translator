<?php

declare(strict_types=1);

use Inteve\Translator\Ast;
use Inteve\Translator\Processors\TagProcessor;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

test('Only text', function () {
	$processor = new TagProcessor;
	Assert::equal(new Ast\MessageText(['lorem ipsum']), $processor->processMessage('lorem ipsum'));
});


test('Text & element', function () {
	$processor = new TagProcessor;
	Assert::equal(new Ast\MessageText([
		'lorem ',
		Ast\Element::create(
			'b',
			[
				'disabled' => TRUE,
			],
			[
				'ipsum',
			]
		)
	]), $processor->processMessage('lorem <b disabled>ipsum</b>'));
});


test('Parameter', function () {
	$processor = new TagProcessor;
	Assert::equal(new Ast\MessageText([
		new Ast\Parameter('param'),
	]), $processor->processMessage('{$param}'));
});


test('Complex', function () {
	$processor = new TagProcessor;
	Assert::equal(new Ast\MessageText([
		new Ast\Parameter('param'),
		' lorem, ',
		Ast\Element::create('b', [
			'title' => [
				'Ipsum ',
				new Ast\Parameter('title')
			],
		])
			->addNode(new Ast\Parameter('name'))
			->addText(' ')
			->addNode(Ast\Element::create('i')
				->addNode(new Ast\Parameter('count'))
			),
	]), $processor->processMessage('{$param} lorem, <b title="Ipsum {$title}">{$name} <i>{$count}</i></b>'));
});


test('Empty elements', function () {
	$processor = new TagProcessor;
	Assert::equal(new Ast\MessageText([
		'Lorem ipsum',
		Ast\Element::create('br'),
		'dolor sit amet',
	]), $processor->processMessage('Lorem ipsum<br>dolor sit amet'));
});


test('Self-closing elements', function () {
	$processor = new TagProcessor;
	Assert::equal(new Ast\MessageText([
		'Lorem ipsum',
		Ast\Element::create('b'),
		'dolor sit amet',
	]), $processor->processMessage('Lorem ipsum<b />dolor sit amet'));
});
