<?php

use Inteve\Translator\DefaultHtmlTagFactory;
use Inteve\Translator\MessageElement;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


test('Formatting tags', function () {
	$htmlTagFactory = new DefaultHtmlTagFactory;
	$attrs = [
		'class' => 'myclass class2',
		'title' => 'Title of element',
		'unexpected' => 'unexpected',
		'id' => 'unexpected',
	];

	Assert::same('<b class="myclass class2" title="Title of element"></b>', (string) $htmlTagFactory->createTag(new MessageElement('b', $attrs)));
	Assert::same('<strong class="myclass class2" title="Title of element"></strong>', (string) $htmlTagFactory->createTag(new MessageElement('strong', $attrs)));
	Assert::same('<i class="myclass class2" title="Title of element"></i>', (string) $htmlTagFactory->createTag(new MessageElement('i', $attrs)));
	Assert::same('<em class="myclass class2" title="Title of element"></em>', (string) $htmlTagFactory->createTag(new MessageElement('em', $attrs)));
	Assert::same('<sub class="myclass class2" title="Title of element"></sub>', (string) $htmlTagFactory->createTag(new MessageElement('sub', $attrs)));
	Assert::same('<sup class="myclass class2" title="Title of element"></sup>', (string) $htmlTagFactory->createTag(new MessageElement('sup', $attrs)));
});


test('Links', function () {
	$htmlTagFactory = new DefaultHtmlTagFactory;
	$attrs = [
		'class' => 'myclass class2',
		'title' => 'Title of element',
		'href' => '/path/',
		'target' => '_blank',
		'unexpected' => 'unexpected',
		'id' => 'unexpected',
	];

	Assert::same('<a href="/path/" target="_blank" class="myclass class2" title="Title of element"></a>', (string) $htmlTagFactory->createTag(new MessageElement('a', $attrs)));
});
