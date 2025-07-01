<?php

	declare(strict_types=1);

	namespace Inteve\Translator\Ast;

	use Inteve\Translator\MessageElement;
	use Inteve\Translator\Locale;


	interface Node
	{
		/**
		 * @param  array<string, mixed> $parameters
		 * @return string|MessageElement
		 */
		function format(array $parameters, Locale $locale);
	}
