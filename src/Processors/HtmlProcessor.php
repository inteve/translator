<?php

	namespace Inteve\Translator\Processors;

	use Inteve\Translator\Utils;


	class HtmlProcessor implements \Inteve\Translator\MessageProcessor
	{
		/** @var Utils\ParametersParser|NULL */
		private $parametersParser;


		public function processMessage($messageText)
		{
			if ($messageText === '') {
				return [];
			}

			if ($this->parametersParser === NULL) {
				$this->parametersParser = new Utils\ParametersParser;
			}

			return $this->parametersParser->parse($messageText);
		}
	}
