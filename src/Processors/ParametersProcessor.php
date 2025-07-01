<?php

	declare(strict_types=1);

	namespace Inteve\Translator\Processors;

	use Inteve\Translator\Ast;
	use Inteve\Translator\Utils;
	use Nette\Utils\Strings;


	class ParametersProcessor implements \Inteve\Translator\MessageProcessor
	{
		/** @var Utils\ParametersParser|NULL */
		private $parametersParser;


		public function processMessage($messageText)
		{
			if (!Strings::contains($messageText, '{$')) {
				return new Ast\MessageText([$messageText]);
			}

			if ($this->parametersParser === NULL) {
				$this->parametersParser = new Utils\ParametersParser;
			}

			return Ast\MessageText::normalize($this->parametersParser->parse($messageText));
		}
	}
