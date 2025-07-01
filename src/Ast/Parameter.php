<?php

	declare(strict_types=1);

	namespace Inteve\Translator\Ast;

	use Inteve\Translator\Locale;


	class Parameter implements Node
	{
		/** @var string */
		private $name;

		/** @var string[] */
		private $modifiers;


		/**
		 * @param string $name
		 * @param string[] $modifiers
		 */
		public function __construct($name, array $modifiers = [])
		{
			$this->name = $name;
			$this->modifiers = $modifiers;
		}


		public function format(array $parameters, Locale $locale)
		{
			if (isset($parameters[$this->name])) {
				return $locale->formatValue($parameters[$this->name], $this->modifiers);
			}

			return '';
		}
	}
