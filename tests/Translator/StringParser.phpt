<?php

declare(strict_types=1);

use Inteve\Translator\Utils\StringParser;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

test('isCurrent', function () {
	$parser = new StringParser('abcdef');
	Assert::true($parser->isCurrent('a'));
	Assert::true($parser->isCurrent('abc'));
	Assert::true($parser->isCurrent('abcdef'));
});


test('consume', function () {
	$parser = new StringParser('abcdef');
	Assert::true($parser->isCurrent('a'));

	$parser->consume(2);
	Assert::true($parser->isCurrent('cd'));

	$parser->consume(2);
	Assert::true($parser->isCurrent('ef'));

	$parser->consume(1);
	Assert::true($parser->isCurrent('f'));

	$parser->consume(1);
	Assert::false($parser->isCurrent('f'));
});
