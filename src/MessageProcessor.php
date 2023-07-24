<?php

	namespace Inteve\Translator;


	interface MessageProcessor
	{
		/**
		 * @param  non-empty-string $messageText
		 * @return Ast\MessageText
		 */
		function processMessage($messageText);
	}
