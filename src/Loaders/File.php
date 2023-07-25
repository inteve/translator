<?php

	namespace Inteve\Translator\Loaders;

	use Nette\Utils\FileSystem;


	class File
	{
		/** @var non-empty-string */
		private $path;

		/** @var non-empty-string|NULL */
		private $messagePrefix;


		/**
		 * @param non-empty-string $path
		 * @param non-empty-string|NULL $messagePrefix
		 */
		public function __construct(
			$path,
			$messagePrefix
		)
		{
			$this->path = $path;
			$this->messagePrefix = $messagePrefix;
		}


		/**
		 * @return string
		 */
		public function read()
		{
			return FileSystem::read($this->path);
		}


		/**
		 * @return non-empty-string
		 */
		public function getPath()
		{
			return $this->path;
		}


		/**
		 * @param  non-empty-string|NULL $prefix
		 * @return non-empty-string|NULL
		 */
		public function getMessagePrefix($prefix = NULL)
		{
			if ($prefix !== NULL) {
				if ($this->messagePrefix === NULL) {
					return $prefix;
				}

				return $prefix . '.' . $this->messagePrefix;
			}

			return $this->messagePrefix;
		}
	}
