<?php

	declare(strict_types=1);

	namespace Inteve\Translator;


	class Translate
	{
		/** @var MessageId */
		private $id;

		/** @var array<string, mixed> */
		private $parameters;


		/**
		 * @param string|MessageId $id
		 * @param array<string, mixed> $parameters
		 */
		public function __construct($id, array $parameters = [])
		{
			if (is_string($id)) {
				$id = new MessageId($id);
			}

			$this->id = $id;
			$this->parameters = $parameters;
		}


		/**
		 * @return MessageId
		 */
		public function getId()
		{
			return $this->id;
		}


		/**
		 * @return array<string, mixed>
		 */
		public function getParameters()
		{
			return $this->parameters;
		}
	}
