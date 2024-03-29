<?php

	namespace Inteve\Translator\Loaders;

	use Inteve\Translator\Domain;
	use Inteve\Translator\LanguageTag;


	interface FileFinder
	{
		/**
		 * @param  non-empty-string $directory
		 * @param  LanguageTag $languageTag
		 * @param  non-empty-string $extension
		 * @return File[]
		 */
		function findDomainFiles(
			$directory,
			Domain $domain = NULL,
			LanguageTag $languageTag,
			$extension
		);


		/**
		 * @param  non-empty-string $directory
		 * @param  LanguageTag $languageTag
		 * @param  non-empty-string $extension
		 * @return array<Domain|NULL>
		 */
		function findDomains(
			$directory,
			LanguageTag $languageTag,
			$extension
		);
	}
