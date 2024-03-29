<?php

	namespace Inteve\Translator;


	interface Translator
	{
		/**
		 * @param  string|MessageId|NotTranslate|Translate $message
		 * @param  array<string, mixed> $parameters
		 * @return string|\Stringable
		 */
		function translate($message, array $parameters = []);
	}
