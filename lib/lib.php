<?php

/**
 * Получить погоду
 *
 * @param $city Город
 * @return bool|string
 */
function getWeather($city)
{
	$cityStart = $city;
	$wcity = mb_substr($city, 0, -1);
	$json = file_get_contents("http://api.openweathermap.org/data/2.5/weather?q={$wcity}&lang=ru");
	$w = json_decode($json);
	$description = $w->weather[0]->description;
	if(!$description)
	{
		return false;
	}
	$wind = $w->wind->speed;
	$temp = round($w->main->temp - 273);
	return "Погода в {$cityStart}: {$temp} °C. Скорость ветра: {$wind} м/с, {$description}";
}

/**
 * Получить случайный факт из файла
 *
 * @return string
 */
function getRandomFact()
{
	$facts = file(__DIR__.'/../files/facts.txt');
	return $facts[array_rand($facts)];
}


/**
 * Send messages
 *
 * @param $message array of mesages
 * @param $messageText message text
 * @param $v object instance vk.api class
 * @param $attachments null nj
 */
function sendMessage($message, $messageText, $v, $attachments=null)
{

		if($attachments)
		{
			$attachments = implode(',', $attachments);
		}
		if (isset($message['chat_id'])) {
			$m = $v->api('messages.send', [
				'chat_id' => $message['chat_id'],
				'message' => $messageText,
			]);
		} else {
			$m = $v->api('messages.send', [
				'user_id' => $message['user_id'],
				'message' => $messageText,
			]);
		}
		dump($m);
}