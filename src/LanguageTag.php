<?php

	namespace Inteve\Translator;

	use CzProject\Assert\Assert;
	use Nette\Utils\Strings;


	class LanguageTag
	{
		/** @var string */
		private $tag;

		/** @var array<string, string> */
		private static $defaults = [
			'en' => 'US',
			'cs' => 'CZ',
		];


		/**
		 * @param string $tag
		 */
		public function __construct($tag)
		{
			Assert::string($tag);
			Assert::true((bool) Strings::match($tag, '~^[a-z]{2}_[A-Z]{2}\\z~'), 'Invalid language tag.');

			$this->tag = $tag;
		}


		/**
		 * @return bool
		 */
		public function equals(self $languageTag)
		{
			return $this->tag === $languageTag->tag;
		}


		/**
		 * @param  string $lang
		 * @return bool
		 */
		public function isLang($lang)
		{
			return strncmp($this->tag, $lang, 2) === 0;
		}


		/**
		 * @return string
		 */
		public function toString()
		{
			return $this->tag;
		}


		/**
		 * @param  string $tag
		 * @return self
		 */
		public static function fromString($tag)
		{
			$tag = str_replace('-', '_', $tag);

			if (strpos($tag, '_') === FALSE && isset(self::$defaults[$tag])) {
				$tag .= '_' . self::$defaults[$tag];
			}

			return new self($tag);
		}
	}
