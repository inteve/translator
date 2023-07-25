<?php

	namespace Inteve\Translator;


	interface TranslatorFactory
	{
		/**
		 * @return Translator
		 */
		function create(Locale $locale);
	}
