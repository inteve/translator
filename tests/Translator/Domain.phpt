<?php

declare(strict_types=1);

use Inteve\Translator\Domain;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


test('isValid', function () {
	Assert::false(Domain::isValid(''));
	Assert::false(Domain::isValid('my-domain'));

	Assert::true(Domain::isValid('mydomain'));
	Assert::true(Domain::isValid('myDomain'));
});
