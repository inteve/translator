<?php

	namespace Inteve\Translator;


	interface MessageProcessor
	{
		/**
		 * @param  string $messageText
		 * @return array<string|MessageElement|Parameter>
		 */
		function processMessage($messageText);
	}
