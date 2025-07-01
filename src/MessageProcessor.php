<?php

	declare(strict_types=1);

	namespace Inteve\Translator;


	interface MessageProcessor
	{
		/**
		 * @param  non-empty-string $messageText
		 * @return Ast\MessageText
		 */
		function processMessage($messageText);
	}
