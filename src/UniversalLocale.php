<?php

	namespace Inteve\Translator;

	use Nette\Utils\Strings;


	class UniversalLocale implements Locale
	{
		/** @var LanguageTag */
		private $languageTag;

		/** @var string */
		private $dateTimeFormat;

		/** @var string */
		private $dateFormat;

		/** @var string */
		private $timeFormat;


		/**
		 * @param string $dateTimeFormat
		 * @param string $dateFormat
		 * @param string $timeFormat
		 */
		public function __construct(
			LanguageTag $languageTag,
			$dateTimeFormat = 'Y-m-d H:i:s',
			$dateFormat = 'Y-m-d',
			$timeFormat = 'H:i:s'
		)
		{
			$this->languageTag = $languageTag;
			$this->dateTimeFormat = $dateTimeFormat;
			$this->dateFormat = $dateFormat;
			$this->timeFormat = $timeFormat;
		}


		/**
		 * @return LanguageTag
		 */
		public function getLanguageTag()
		{
			return $this->languageTag;
		}


		/**
		 * @param  mixed $value
		 * @param  string[] $modifiers
		 * @return string
		 */
		public function formatValue($value, array $modifiers)
		{
			foreach ($modifiers as $modifier) {
				if ($modifier === 'date') {
					$value = $this->formatDateTime($value, $this->dateFormat);

				} elseif ($modifier === 'datetime') {
					$value = $this->formatDateTime($value, $this->dateTimeFormat);

				} elseif ($modifier === 'time') {
					$value = $this->formatDateTime($value, $this->timeFormat);

				} elseif ($modifier === 'lower') {
					$value = Strings::lower($this->toString($value));

				} elseif ($modifier === 'upper') {
					$value = Strings::upper($this->toString($value));

				} elseif ($modifier === 'firstUpper') {
					$value = Strings::firstUpper($this->toString($value));
				}
			}

			return $this->toString($value);
		}


		/**
		 * @param  mixed $value
		 * @return string
		 */
		private function toString($value)
		{
			if (is_scalar($value) || $value instanceof \Stringable) {
				return (string) $value;
			}

			if ($value instanceof \DateTimeInterface) {
				return $value->format($this->dateTimeFormat);
			}

			return ''; // cannot be converted
		}


		/**
		 * @param  mixed $value
		 * @param  string $format
		 * @return string
		 */
		private function formatDateTime($value, $format)
		{
			if ($value instanceof \DateTimeInterface) {
				return $value->format($format);
			}

			return $this->toString($value);
		}
	}
