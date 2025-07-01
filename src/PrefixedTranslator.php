<?php

	declare(strict_types=1);

	namespace Inteve\Translator;


	class PrefixedTranslator implements Translator
	{
		/** @var string */
		private $prefix;

		/** @var Translator */
		private $translator;


		/**
		 * @param string $prefix
		 */
		public function __construct(
			$prefix,
			Translator $translator
		)
		{
			$this->prefix = $prefix;
			$this->translator = $translator;
		}


		public function translate($message, array $parameters = [])
		{
			if (is_string($message)) {
				$message = $this->prefix . '.' . $message;
			}

			return $this->translator->translate($message, $parameters);
		}


		/**
		 * @param  string $prefix
		 * @return self
		 */
		public function prefix($prefix)
		{
			return new self($this->prefix . '.' . $prefix, $this->translator);
		}
	}
