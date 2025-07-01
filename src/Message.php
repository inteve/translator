<?php

	declare(strict_types=1);

	namespace Inteve\Translator;


	class Message
	{
		/** @var MessageId */
		private $id;

		/** @var array<string|MessageElement> */
		private $elements;


		/**
		 * @param array<string|MessageElement> $elements
		 */
		public function __construct(MessageId $id, array $elements)
		{
			$this->id = $id;
			$this->elements = $elements;
		}


		/**
		 * @return MessageId
		 */
		public function getId()
		{
			return $this->id;
		}


		/**
		 * @return array<string|MessageElement>
		 */
		public function getElements()
		{
			return $this->elements;
		}
	}
