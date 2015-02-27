<?php
require __DIR__.'/vendor/autoload.php';
require __DIR__.'/lib/lib.php';
require __DIR__.'/lib/vk.api.php';
require __DIR__.'/config/config.php';

$response = $v->api('messages.get', [
	'count' => 10
]);
$messages = $response['items'];
foreach($messages as $message)
{
	if (!$message['read_state']) {

		if (preg_match('/бот/ui', $message['body'])) {

			if (preg_match('/(погода|погоду).(.*)/ui', $message['body'], $city)) {
				$city = explode(' ', $city[0]);
				$weater = getWeather($city[2]);
				if ($weater) {
					$messageText = empty($city[2]) ? "Я не понял запрос. Напиши так: покажи погоду в киеве" : getWeather($city[2]);
				} else {
					$messageText = 'Не могу найти погоду в ' . $city[2];
				}
				sendMessage($message, $messageText, $v);
			}

			if (preg_match('/факт/ui', $message['body'])) {
				$messageText = getRandomFact();
				sendMessage($message, $messageText, $v);

			}



			if (preg_match('/команды|как с тобой разговаривать|помощь/ui', $message['body'])) {
				$messageText = "
						Я тебе не бесдушная скотина, поэтому называй мое имя, когда пишешь мне.

						1. Показать команды: бот, покажи команды\n
						2. Показать погоду: Покажи погоду в city_name. Пример: бот, покажи погоду в киеве\n
						3. Интересный факт: бот, покажи интересный факт";
				sendMessage($message, $messageText, $v);

			}

			if(preg_match('/фото/', $message['body']))
			{
				$attachments = $v->upload_photo(false, ['images/deer.jpg']);
				$messageText = 'вот фото';
				sendMessage($message, $messageText, $v, $attachments);
			}

		}

		/**
		 * Общение с ботом
		 */
		$ignore_symbs = [')', '))', '(', '((', ')))', ',', '!', '?'];
		$talkPhraces = file(__DIR__ . '/files/talk.txt');

		foreach ($talkPhraces as $phrases)
		{
			$phrases = explode(":", $phrases);
			foreach ($phrases as $phrase)
			{
				if (preg_match('/'.$phrase.'/ui', $message['body'])) {
					$messageText = $phrases[array_rand($phrases)];
					if(!preg_match('/'.$messageText.'/ui', $message['body']))
					{
						sendMessage($message, $messageText, $v);
					}
					else
					{
						$messageText = $phrases[array_rand($phrases)];
					}

				}
			}
		}
	}



}
