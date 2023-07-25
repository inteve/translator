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
			'cs' => 'CZ',
			'de' => 'DE',
			'en' => 'US',
		];


		/**
		 * @param string $tag
		 */
		public function __construct($tag)
		{
			Assert::string($tag);
			Assert::true((bool) Strings::match($tag, '~^[a-z]{2}\\-[A-Z]{2}\\z~'), 'Invalid language tag.');

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
		public function getLang()
		{
			return substr($this->tag, 0, 2);
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
			$tag = str_replace('_', '-', $tag);

			if (strpos($tag, '-') === FALSE && isset(self::$defaults[$tag])) {
				$tag .= '-' . self::$defaults[$tag];
			}

			return new self($tag);
		}
	}
