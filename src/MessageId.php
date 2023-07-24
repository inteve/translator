<?php

	namespace Inteve\Translator;

	use CzProject\Assert\Assert;
	use Nette\Utils\Strings;


	class MessageId
	{
		/** @var string */
		private $id;


		/**
		 * @param string $id
		 */
		public function __construct($id)
		{
			Assert::true(self::isValid($id));
			$this->id = $id;
		}


		/**
		 * @param  string $prefix
		 * @return bool
		 */
		public function isUnder($prefix)
		{
			Assert::string($prefix);

			if ($prefix === '') {
				return TRUE;
			}

			return Strings::startsWith($this->id, $prefix . '.');
		}


		/**
		 * @param  string|NULL $prefix
		 * @return string|NULL
		 */
		public function getDomain($prefix = NULL)
		{
			if ($prefix === NULL) {
				return Strings::before($this->id, '.');
			}

			if (!Strings::startsWith($this->id, $prefix . '.')) {
				throw new \Inteve\Translator\InvalidArgumentException("MessageID '{$this->id}' has not prefix '$prefix'.");
			}

			$id = Strings::substring($this->id, Strings::length($prefix) + 1);
			return Strings::before($id, '.');
		}


		/**
		 * @return string
		 */
		public function toString()
		{
			return $this->id;
		}


		/**
		 * @param  string $id
		 * @return bool
		 */
		public static function isValid($id)
		{
			Assert::string($id);
			return (bool) Strings::match($id, '~^[a-zA-Z0-9]+(?:\.[a-zA-Z0-9]+)*$~s');
		}
	}
