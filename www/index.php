<?php

define('CALLBACK_API_EVENT_CONFIRMATION', 'confirmation');
define('CALLBACK_API_EVENT_MESSAGE_NEW', 'message_new');

require_once 'config.php';
require_once 'global.php';

require_once 'api/vk_api.php';
require_once 'api/yandex_api.php';

require_once 'bot/bot.php';

if (!isset($_REQUEST)) {
  exit;
}

callback_handleEvent();

function callback_handleEvent() {
  $event = callback_getEvent();

  try {
    switch ($event['type']) {
      case CALLBACK_API_EVENT_CONFIRMATION:
        callback_handleConfirmation();
        break;

      case CALLBACK_API_EVENT_MESSAGE_NEW:
        callback_handleMessageNew($event['object']);
        break;

      default:
        callback_response('Unsupported event');
        break;
    }
  } catch (Exception $e) {
    log_error($e);
  }

  callback_okResponse();
}

function callback_getEvent() {
  return json_decode(file_get_contents('php://input'), true);
}

function callback_handleConfirmation() {
  callback_response(CALLBACK_API_CONFIRMATION_TOKEN);
}

function callback_handleMessageNew($data) {
  $user_id = $data['user_id'];
  bot_sendMessage($user_id);
  callback_okResponse();
}

function callback_okResponse() {
  callback_response('ok');
}

function callback_response($data) {
  echo $data;
  exit();
}


