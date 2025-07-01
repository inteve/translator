<?php

	declare(strict_types=1);

	namespace Inteve\Translator;


	interface TranslatorFactory
	{
		/**
		 * @return Translator
		 */
		function create(Locale $locale);
	}
