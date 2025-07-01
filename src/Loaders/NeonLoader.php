<?php

	declare(strict_types=1);

	namespace Inteve\Translator\Loaders;

	use Inteve\Translator\Domain;
	use Inteve\Translator\LanguageTag;
	use Inteve\Translator\MessageId;
	use Inteve\Translator\Utils;
	use Nette\Neon\Neon;


	class NeonLoader implements \Inteve\Translator\MessageLoader
	{
		/** @var non-empty-string|NULL */
		private $prefix;

		/** @var non-empty-string */
		private $directory;

		/** @var FileFinder */
		private $fileFinder;

		/** @var array<string, array<string, string|NULL>> */
		private $messages = [];

		/** @var array<string, TRUE> */
		private $loadedFiles = [];

		/** @var array<string, TRUE> */
		private $loadedAllLangTag = [];


		/**
		 * @param  string|NULL $prefix
		 * @param  non-empty-string $directory
		 */
		public function __construct($prefix, $directory, FileFinder $fileFinder = NULL)
		{
			$this->prefix = $prefix !== '' ? $prefix : NULL;
			$this->directory = $directory;
			$this->fileFinder = $fileFinder !== NULL ? $fileFinder : new DefaultFileFinder;
		}


		public function getMessage(LanguageTag $languageTag, MessageId $messageId)
		{
			if ($this->prefix !== NULL && !$messageId->isUnder($this->prefix)) {
				return NULL;
			}

			$langTag = $languageTag->toString();
			$messageStrId = $messageId->toString();

			if (isset($this->messages[$langTag][$messageStrId])) {
				return $this->messages[$langTag][$messageStrId];
			}

			if (isset($this->messages[$langTag]) && array_key_exists($messageStrId, $this->messages[$langTag])) { // maybe NULL (missing translation)
				return $this->messages[$langTag][$messageStrId];
			}

			$this->messages[$langTag][$messageStrId] = NULL;
			$domain = $messageId->getDomain($this->prefix);
			$this->loadFiles($languageTag, $domain);

			return isset($this->messages[$langTag][$messageStrId]) ? $this->messages[$langTag][$messageStrId] : NULL;
		}


		public function getAllMessages(LanguageTag $languageTag)
		{
			$langTag = $languageTag->toString();

			if (!isset($this->loadedAllLangTag[$langTag])) {
				foreach ($this->fileFinder->findDomains($this->directory, $languageTag, 'neon') as $domain) {
					$this->loadFiles($languageTag, $domain);
				}

				$this->loadedAllLangTag[$langTag] = TRUE;
			}

			if (isset($this->messages[$langTag])) {
				$messages = array_filter($this->messages[$langTag], function ($value) {
					return $value !== NULL;
				});
				ksort($messages);
				return $messages;
			}

			return [];
		}


		/**
		 * @return void
		 */
		private function loadFiles(LanguageTag $languageTag, Domain $domain = NULL)
		{
			foreach ($this->fileFinder->findDomainFiles($this->directory, $domain, $languageTag, 'neon') as $file) {
				$this->loadFile($file, $languageTag);
			}
		}


		/**
		 * @return void
		 */
		private function loadFile(File $file, LanguageTag $languageTag)
		{
			$path = $file->getPath();

			if (isset($this->loadedFiles[$path])) {
				return;
			}

			$content = $file->read();
			$data = Neon::decode($content);

			if (is_array($data)) {
				$messages = Utils\Arrays::flattenAssoc($data, $file->getMessagePrefix($this->prefix));
				$langTag = $languageTag->toString();

				if (!isset($this->messages[$langTag])) {
					$this->messages[$langTag] = $messages;

				} else {
					$this->messages[$langTag] = array_merge($this->messages[$langTag], $messages);
				}


			} elseif ($data !== NULL) {
				throw new \Inteve\Translator\InvalidStateException("Invalid content in file $path.");
			}

			$this->loadedFiles[$path] = TRUE;
		}
	}
