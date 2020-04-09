<?php

use Symfony\Component\Dotenv\Dotenv;
use Zanzara\Config;
use Zanzara\Context;
use Zanzara\Telegram\Type\Message;
use Zanzara\Telegram\Type\Scalar;
use Zanzara\Zanzara;

require "../../vendor/autoload.php";

$dotenv = new Dotenv();
$dotenv->load("../../.env");

$config = new Config();
$config->setUpdateMode(Config::POLLING_MODE);
$bot = new Zanzara($_ENV['BOT_KEY'], $config);
$key = $_ENV['BOT_KEY'];

$bot->onCommand("start", function (Context $ctx) {
    echo "I'm processing the /start command \n";

    $ctx->reply("Ciao condottiero")->then(function (Message $response) use ($ctx) {
        $message_id = $response->getMessageId();
        $chat_id = $ctx->getUpdate()->getEffectiveChat()->getId();
        $ctx->editMessageText("ciao condottiero changed", compact("chat_id", "message_id"));
        $ctx->deleteMessage($chat_id, $message_id)->then(function ($response) {
            var_dump($response);
        });


        $ctx->reply("ciao")->then(function (Message $message) {
            var_dump($message);
        });

        $ctx->exportChatInviteLink($chat_id)->then(function ($response) {
            var_dump($response);
        }, function ($error) {
            var_dump($error);
        });


    });
});

$bot->onChannelPost(function (Context $ctx) {
    $message = $ctx->getChannelPost()->getText();
    $chatId = $ctx->getChannelPost()->getChat()->getId();

    $ctx->exportChatInviteLink($chatId)->then(function ($response) {
        var_dump($response);
    });


});


echo "The bot is listening ... \n";

$bot->run();



