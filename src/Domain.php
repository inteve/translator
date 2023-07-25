<?php

	namespace Inteve\Translator;

	use CzProject\Assert\Assert;
	use Nette\Utils\Validators;


	class Domain
	{
		/** @var non-empty-string */
		private $name;


		/**
		 * @param non-empty-string $name
		 */
		public function __construct($name)
		{
			Assert::true(self::isValid($name), 'Invalid domain name.');

			$this->name = $name;
		}


		/**
		 * @return non-empty-string
		 */
		public function toString()
		{
			return $this->name;
		}


		/**
		 * @param  string $domain
		 * @return bool
		 */
		public static function isValid($domain)
		{
			if ($domain === '') {
				return FALSE;
			}

			return Validators::is($domain, 'pattern:[a-zA-Z0-9]+');
		}
	}
