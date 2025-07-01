<?php

declare(strict_types=1);

use Inteve\Translator\Ast;
use Inteve\Translator\Processors\EntityProcessor;
use Inteve\Translator\Processors\ParametersProcessor;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

test('Only text', function () {
	$processor = new EntityProcessor;
	Assert::equal(new Ast\MessageText(['lorem ipsum']), $processor->processMessage('lorem ipsum'));
});


test('Named & codeded entity', function () {
	$processor = new EntityProcessor;
	Assert::equal(new Ast\MessageText([
		"lorem \xC2\xA0 \" ' ' \r \x20",
	]), $processor->processMessage('lorem &nbsp; &#34; &#39; &apos; &#13; &#32;'));
});


test('With parameters', function () {
	$processor = new EntityProcessor(new ParametersProcessor);
	Assert::equal(new Ast\MessageText([
		'{$test}',
		new Ast\Parameter('name'),
	]), $processor->processMessage('&#x7B;&#x24;test}{$name}'));
});
