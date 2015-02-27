<?php
ini_set('display_errors', 1);
error_reporting(0);
/**
 * Нужно создать свое приложение ВК и указать здесь параметры
 */
$config['secret_key'] = ''//app_secret;
$config['client_id'] = ; // номер приложения
$config['user_id'] = //id пользователя;
$config['scope'] = 'messages,wall';
$config['access_token'] = ''//access_Token;

$v = new Vk($config);
