<?php

	namespace Inteve\Translator;


	interface Locale
	{
		/**
		 * @return LanguageTag
		 */
		function getLanguageTag();


		/**
		 * @param  mixed $value
		 * @param  string[] $modifiers
		 * @return string
		 */
		function formatValue($value, array $modifiers);
	}
