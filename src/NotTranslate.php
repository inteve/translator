<?php

	declare(strict_types=1);

	namespace Inteve\Translator;


	class NotTranslate
	{
		/** @var string */
		private $text;


		/**
		 * @param string $text
		 */
		public function __construct($text)
		{
			$this->text = $text;
		}


		/**
		 * @return string
		 */
		public function getText()
		{
			return $this->text;
		}
	}
