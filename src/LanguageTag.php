<?php

	namespace Inteve\Translator;

	use CzProject\Assert\Assert;
	use Nette\Utils\Strings;


	class LanguageTag
	{
		/** @var non-empty-string */
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
			Assert::true($tag !== '');

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
		 * @return non-empty-string
		 */
		public function getLang()
		{
			return substr($this->tag, 0, 2);
		}


		/**
		 * @param  string $country
		 * @return bool
		 */
		public function isCountry($country)
		{
			return substr_compare($this->tag, strtoupper($country), 3, 2, FALSE) === 0;
		}


		/**
		 * @return non-empty-string
		 */
		public function getCountry()
		{
			$country = substr($this->tag, 3, 2);
			assert($country !== '');
			return $country;
		}


		/**
		 * @return non-empty-string
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
