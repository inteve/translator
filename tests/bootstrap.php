<?php

declare(strict_types=1);

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


class Tests
{
	/**
	 * @param  string $langTag
	 * @return \Inteve\Translator\Locale
	 */
	public static function createLocale($langTag)
	{
		return new \Inteve\Translator\UniversalLocale(
			\Inteve\Translator\LanguageTag::fromString($langTag),
			'j.n.Y / H:i',
			'j.n.Y',
			'H:i'
		);
	}
}
