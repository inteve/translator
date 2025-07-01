<?php

	declare(strict_types=1);

	namespace Inteve\Translator\Loaders;

	use Inteve\Translator\Domain;
	use Inteve\Translator\LanguageTag;
	use Inteve\Translator\MessageId;
	use Nette\Utils\Strings;


	class DefaultFileFinder implements FileFinder
	{
		/** @var non-empty-string */
		private $defaultFileName;


		/**
		 * @param non-empty-string $defaultFileName
		 */
		public function __construct($defaultFileName = '_messages')
		{
			$this->defaultFileName = $defaultFileName;
		}


		public function findDomainFiles(
			$directory,
			Domain $domain = NULL,
			LanguageTag $languageTag,
			$extension
		)
		{
			$domainName = $domain !== NULL ? $domain->toString() : NULL;
			$files = $this->findFiles(
				$directory,
				$domainName,
				$languageTag->toString() . '.' . $extension
			);

			if (count($files) === 0) {
				return $this->findFiles(
					$directory,
					$domainName,
					$languageTag->getLang() . '.' . $extension
				);
			}

			return $files;
		}


		/**
		 * @param  non-empty-string $directory
		 * @param  non-empty-string|NULL $domainName
		 * @param  non-empty-string $fileNameSuffix
		 * @return File[]
		 */
		private function findFiles(
			$directory,
			$domainName,
			$fileNameSuffix
		)
		{
			$paths = [];

			if ($domainName === NULL) {
				$paths = [
					$directory . '/' . $this->defaultFileName . '.' . $fileNameSuffix => NULL,
					$directory . '/' . $fileNameSuffix => NULL,
				];

			} else {
				$paths = [
					$directory . '/' . $domainName . '.' . $fileNameSuffix => $domainName,
					$directory . '/' . $domainName . '/' . $fileNameSuffix => $domainName,
				];

				$subDir = $directory . '/' . $domainName;

				if (is_dir($subDir)) {
					$items = scandir($subDir, SCANDIR_SORT_ASCENDING);

					if (!is_array($items)) {
						throw new \Inteve\Translator\InvalidStateException("Scandir of $subDir failed.");
					}

					$subSuffix = '.' . $fileNameSuffix;

					foreach ($items as $item) {
						if ($item === '' || $item === '.' || $item === '..' || Strings::startsWith($item, '.')) {
							continue;
						}

						if (Strings::endsWith($item, $subSuffix)) {
							$prefix = Strings::substring($item, 0, -Strings::length($subSuffix));

							if ($prefix !== '' && MessageId::isValid($prefix)) {
								$paths[$subDir . '/' . $item] = $domainName . '.' . $prefix;
							}
						}
					}
				}
			}

			$files = [];

			foreach ($paths as $path => $prefix) {
				if (is_file($path)) {
					$files[] = new File($path, $prefix);
				}
			}

			return $files;
		}


		public function findDomains($directory, LanguageTag $languageTag, $extension)
		{
			$suffix = $languageTag->toString() . '.' . $extension;
			$suffixWithDot = '.' . $suffix;
			$alternativeSuffix = $languageTag->getLang() . '.' . $extension;
			$alternativeSuffixWithDot = '.' . $alternativeSuffix;
			$domains = [];

			$items = scandir($directory, SCANDIR_SORT_ASCENDING);

			if (!is_array($items)) {
				throw new \Inteve\Translator\InvalidStateException("Scandir of $directory failed.");
			}

			foreach ($items as $item) {
				if ($item === '' || $item === '.' || $item === '..' || Strings::startsWith($item, '.')) {
					continue;
				}

				if (is_dir($directory . '/' . $item)) {
					if (!isset($domains[$item]) && Domain::isValid($item)) {
						$domains[$item] = new Domain($item);
					}

					continue;
				}

				if (!is_file($directory . '/' . $item)) {
					continue;
				}

				$domain = NULL;

				if ($item === $suffix) {
					$domain = '';

				} elseif (Strings::endsWith($item, $suffixWithDot)) {
					$domain = Strings::substring($item, 0, -Strings::length($suffixWithDot));

				} elseif ($item === $alternativeSuffix) {
					$domain = '';

				} elseif (Strings::endsWith($item, $alternativeSuffixWithDot)) {
					$domain = Strings::substring($item, 0, -Strings::length($alternativeSuffixWithDot));

				} else {
					continue;
				}

				if (($domain === $this->defaultFileName || $domain === '')) {
					if (!isset($domains[''])) {
						$domains[''] = NULL;
					}

				} elseif (!isset($domains[$domain]) && Domain::isValid($domain)) {
					$domains[$domain] = new Domain($domain);
				}
			}

			return array_values($domains);
		}
	}
