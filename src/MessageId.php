<?php

	declare(strict_types=1);

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
		 * @return Domain|NULL
		 */
		public function getDomain($prefix = NULL)
		{
			if ($prefix === NULL) {
				$domainName = Strings::before($this->id, '.');
				return is_string($domainName) && $domainName !== '' ? new Domain($domainName) : NULL;
			}

			if (!Strings::startsWith($this->id, $prefix . '.')) {
				throw new \Inteve\Translator\InvalidArgumentException("MessageID '{$this->id}' has not prefix '$prefix'.");
			}

			$id = Strings::substring($this->id, Strings::length($prefix) + 1);
			$domainName = Strings::before($id, '.');
			return is_string($domainName) && $domainName !== '' ? new Domain($domainName) : NULL;
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
