<?php

	namespace Inteve\Translator\Utils;

	use CzProject\Assert\Assert;
	use Nette\Utils\Strings;


	class StringParser
	{
		/** @var non-empty-string */
		private $text;

		/** @var int */
		private $length;

		/** @var int */
		private $offset;

		/** @var bool */
		private $inTryParse = FALSE;


		/**
		 * @param non-empty-string $text
		 */
		public function __construct($text)
		{
			if ($text === '') {
				throw new \Inteve\Translator\InvalidArgumentException('String cannot be empty.');
			}

			$this->text = $text;
			$this->length = strlen($text);
			$this->offset = 0;
		}


		/**
		 * @return bool
		 */
		public function isEnd()
		{
			return $this->offset >= $this->length;
		}


		/**
		 * @param  string $text
		 * @return bool
		 */
		public function isCurrent($text)
		{
			if ($this->isEnd()) {
				return FALSE;
			}

			$length = strlen($text);

			if (($this->offset + $length) >= $this->length) {
				return FALSE;
			}

			if ($this->offset < $this->length) {
				return substr_compare($this->text, $text, $this->offset, $length) === 0;
			}

			throw new \Inteve\Translator\ParseException('No text, there is end of string.');
		}


		/**
		 * @param  string $text
		 * @return string
		 */
		public function consumeText($text)
		{
			Assert::true($text !== '');

			if ($this->isCurrent($text)) {
				return $this->consume(strlen($text));
			}

			throw new \Inteve\Translator\ParseException('No text to consume.');
		}


		/**
		 * @param  string $text
		 * @return string
		 */
		public function consumeToText($text)
		{
			Assert::true($text !== '');
			$res = '';

			while (!$this->isCurrent($text)) {
				$res .= $this->consume(1);

				if ($this->isEnd()) {
					break;
				}
			}

			if ($res !== '') {
				return $res;
			}

			throw new \Inteve\Translator\ParseException('No text to consume.');
		}


		/**
		 * @param  string $pattern
		 * @return string
		 */
		public function consumeByMatch($pattern)
		{
			Assert::true($pattern !== '');

			if ($m = Strings::match($this->text, "\x01(?:$pattern)\x01u", 0, $this->offset)) {
				return $this->consume(strlen($m[0]));
			}

			throw new \Inteve\Translator\ParseException('No text to consume.');
		}


		/**
		 * @template T
		 * @param  callable($this):T $cb
		 * @return T|NULL
		 */
		public function tryParse(callable $cb)
		{
			if ($this->inTryParse) {
				throw new \Inteve\Translator\ParseException('There is active tryParse.');
			}

			$this->inTryParse = TRUE;
			$offset = $this->offset;

			try {
				$res = $cb($this);
				$this->inTryParse = FALSE;
				return $res;

			} catch (\Inteve\Translator\ParseException $e) {
				$this->inTryParse = FALSE;
				$this->offset = $offset;
				// nothing
			}

			return NULL;
		}


		/**
		 * @param  int $length
		 * @return string
		 */
		public function consume($length)
		{
			if ($length <= 0) {
				throw new \Inteve\Translator\ParseException('Costume length must be greater than 0.');
			}

			if (($this->offset + $length) > $this->length) {
				throw new \Inteve\Translator\ParseException('Consume to out of range is not allowed.');
			}

			$result = substr($this->text, $this->offset, $length);
			$this->offset += $length;
			return $result;
		}
	}
