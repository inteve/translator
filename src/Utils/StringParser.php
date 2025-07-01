<?php

	declare(strict_types=1);

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
		 * @param  non-empty-string $text
		 * @return bool
		 */
		public function isCurrent($text)
		{
			if ($this->isEnd()) {
				return FALSE;
			}

			$length = strlen($text);

			if (($this->offset + $length) > $this->length) {
				return FALSE;
			}

			if ($this->offset < $this->length) {
				return substr_compare($this->text, $text, $this->offset, $length) === 0;
			}

			throw new \Inteve\Translator\ParseException('No text, there is end of string.');
		}


		/**
		 * @param  non-empty-string $text
		 * @return string
		 */
		public function consumeText($text)
		{
			Assert::true($text !== '');

			if ($this->isCurrent($text)) {
				return $this->consume(strlen($text));
			}

			throw new \Inteve\Translator\ParseException('No text (' . $text . ') to consume, there is (' . substr($this->text, $this->offset, 10) . '), offset ' . $this->offset . '.');
		}


		/**
		 * @param  non-empty-string $text
		 * @return non-empty-string
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

			throw new \Inteve\Translator\ParseException('No text to consume before (' . $text . '), there is (' . substr($this->text, $this->offset, 10) . '), offset ' . $this->offset . '.');
		}


		/**
		 * @param  non-empty-string $text
		 * @return string|NULL
		 */
		public function tryConsumeToText($text)
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

			return NULL;
		}


		/**
		 * @param  non-empty-string $pattern
		 * @return string
		 */
		public function consumeByMatch($pattern)
		{
			Assert::true($pattern !== '');

			if ($m = Strings::match($this->text, "\x01\\G(?:$pattern)\x01u", 0, $this->offset)) {
				$length = strlen($m[0]);

				if ($length > 0) {
					return $this->consume($length);
				}
			}

			throw new \Inteve\Translator\ParseException('No text match (pattern ' . $pattern . ') to consume, there is (' . substr($this->text, $this->offset, 10) . '), offset ' . $this->offset . '.');
		}


		/**
		 * @param  non-empty-string $pattern
		 * @return string|NULL
		 */
		public function tryConsumeByMatch($pattern)
		{
			Assert::true($pattern !== '');

			if ($m = Strings::match($this->text, "\x01\\G(?:$pattern)\x01u", 0, $this->offset)) {
				$length = strlen($m[0]);

				if ($length > 0) {
					return $this->consume($length);
				}
			}

			return NULL;
		}


		/**
		 * @template T
		 * @param  callable($this):T $cb
		 * @return T|NULL
		 */
		public function tryParse(callable $cb)
		{
			$offset = $this->offset;

			try {
				$res = $cb($this);
				return $res;

			} catch (\Inteve\Translator\ParseException $e) {
				$this->offset = $offset;
				// nothing
			}

			return NULL;
		}


		/**
		 * @param  positive-int $length
		 * @return string
		 */
		public function consume($length)
		{
			if ($length <= 0) {
				throw new \Inteve\Translator\InvalidStateException('Costume length must be greater than 0.');
			}

			if (($this->offset + $length) > $this->length) {
				throw new \Inteve\Translator\InvalidStateException('Consume to out of range is not allowed.');
			}

			$result = substr($this->text, $this->offset, $length);
			$this->offset += $length;
			return $result;
		}
	}
