<?php

namespace sobot\telegram;

use GuzzleHttp;
use sobot\core\arrayManager as array_manager;
use sobot\core\ipManager as ip_manager;
class bot
{
    private $token;
    public $update;

    public function __construct($token)
    {
        $this->token = $token;
        $this->array_manager = new array_manager();
        $this->ip_manager = new ip_manager();
    }
    public function __call($name, $arguments)
    {
        $this->telegram_curl($name, $arguments[0], $arguments[1] ?? []);
    }
    public function __set($name, $value)
    {
        $this->$name = $value;
    }
    protected function telegram_curl($method, $main = "", $query)
    {
        if (gettype($main) == 'array') {
            $query = $main;
        } else {
            $main = $main ?? $this->default[$method][$this->getDefault($method)];
            $query[$this->getDefault($method)] = $main;
        }
        $this->default[$method] = isset($this->default[$method]) ? $this->array_manager->getSingleArray($this->getClassDefault($method), $this->default[$method]) : $this->getClassDefault($method);
        $res = isset($this->default[$method]) ? $this->array_manager->getSingleArray($this->default[$method], $query) : $query;
        if ($this->request("https://api.telegram.org/bot{$this->token}/$method", $res)) {
            return $this;
        }
    }
    public function request($url, array $query)
    {
        $client = new GuzzleHttp\Client();
        $res = $client->request('GET', $url, [
            'query' => $query,
        ]);
        if ($res->getStatusCode() == 200) {
            return true;
        }
        return $this->getStatusCode();
    }
    public function getUpdate()
    {
        $update = json_decode(file_get_contents("php://input")) ?? $return = true;
        if($return){
            return false;
        }
        $this->update = $update;
        if (isset($update->message)) {
            $this->message_id = $update->message->message_id;
            $this->text = $update->message->text;
            $this->date = $update->message->date;
            if (isset($update->message->from)) {
                $this->is_bot = $update->message->from->is_bot;
                $this->firstname = $update->message->from->first_name;
                $this->lastname = $update->message->from->last_name;
                $this->username = $update->message->from->username;
                $this->language_code = $update->message->from->language_code;
                $this->from_id = $update->message->from->id;
            }
            if (isset($update->message->chat)) {
                $this->chat_id = $update->message->chat->id;
                $this->type = $update->message->chat->type;
            }
            if (isset($update->message->reply_to_message)) {
                $this->reply_to_message['message_id'] = $update->message->reply_to_message->message_id;
                if (isset($update->message->reply_to_message->from)) {
                    $this->reply_to_message['from_id'] = $update->message->reply_to_message->from->id;
                    $this->reply_to_message['is_bot'] = $update->message->reply_to_message->from->is_bot;
                    $this->reply_to_message['first_name'] = $update->message->reply_to_message->from->first_name;
                    $this->reply_to_message['username'] = $update->message->reply_to_message->from->username;
                }
                if (isset($update->message->reply_to_message->chat)) {
                    $this->reply_to_message['chat_id'] = $update->message->reply_to_message->chat->id;
                    $this->reply_to_message['chat_first'] = $update->message->reply_to_message->chat->first_name;
                    $this->reply_to_message['chat_last'] = $update->message->reply_to_message->chat->last_name;
                    $this->reply_to_message['chat_username'] = $update->message->reply_to_message->chat->username;
                }
                $this->reply_to_message['text'] = $update->message->reply_to_message->text;
                $this->reply_to_message['date'] = $update->message->reply_to_message->date;
            }
            if (isset($update->message->reply_to_message->contact)) {
                $this->phone_number = $update->message->reply_to_message->contact->phone_number;
                $this->contact_name = $update->message->reply_to_message->contact->first_name;
                $this->contact_last = $update->message->reply_to_message->contact->last_name;
                $this->contact_id = $update->message->reply_to_message->contact;
            }
        }
        if (isset($update->callback_query)) {
            $this->data = $update->callback_query->data;
            if (isset($update->callback_query->from)) {
                $this->from_id = $update->callback_query->from->id;
                $this->is_bot = $update->callback_query->from->is_bot;
                $this->first_name = $update->callback_query->from->first_name;
                $this->last_name = $this->is_bot = $update->callback_query->from->last_name;
                $this->username = $update->callback_query->from->username;
                $this->language_code = $update->callback_query->from->language_code;
            }
            if(isset($update->callback_query->message)){
                $this->message_id = $update->callback_query->message->message_id;
                $this->chat_id = $update->callback_query->message->chat->id;
            }
        }
        return $this;
    }
    public function getDefault($name)
    {
        return [
            'setWebhook' => 'url',
            'sendMessage' => 'text',
            'editMessageText' => 'text',
            'sendPhoto' => 'photo',
            'forwardMessage' => 'from_chat_id',
            'copyMessage' => 'from_chat_id',
            'sendAudio' => 'audio',
            'sendDocument' => 'document',
            'sendVideo' => 'video',
            'sendAnimation' => 'animation',
            'sendVoice' => 'voice',
            'sendVideoNote' => 'video_note',
            'sendMediaGroup' => 'media',
            'sendContact' => 'phone_number',
            'sendPoll' => 'question',
            'sendDice' => 'emoji',
            'sendChatAction' => 'action',
            'getFile' => 'file_id',
            'kickChatMember' => 'user_id',
            'unbanChatMember' => 'user_id',
            'restrictChatMember' => 'user_id',
            'promoteChatMember' => 'user_id',
            'setChatAdministratorCustomTitle' => 'user_id',
            'setChatPermissions' => 'permissions',
            'exportChatInviteLink' => 'chat_id',
            'createChatInviteLink' => 'chat_id',
            'editChatInviteLink' => 'chat_id',
            'revokeChatInviteLink' => 'chat_id',
            'setChatPhoto' => 'chat_id',
            'deleteChatPhoto' => 'chat_id',
            'setChatTitle' => 'chat_id',
            'setChatDescription' => 'chat_id',
            'pinChatMessage' => 'chat_id',
            'unpinChatMessage' => 'chat_id',
            'unpinAllChatMessages' => 'chat_id',
            'leaveChat' => 'chat_id',
            'getChat' => 'chat_id',
            'getChatAdministrators' => 'chat_id',
            'getChatMembersCount' => 'chat_id',
            'getChatMember' => 'user_id',
            'setChatStickerSet' => 'chat_id',
            'deleteChatStickerSet' => 'chat_id',
            'answerCallbackQuery' => 'callback_query_id',
            'setMyCommands' => 'commands',
            'editMessageCaption' => 'caption',
            'editMessageMedia' => 'media',
        ][$name];
    }
    public function getClassDefault($name)
    {
        return [
            '*' => [

            ],
            'sendMessage' => [
                'chat_id' => $this->from_id,
            ]
        ][$name];
    }
    public function inline_keyboard($keyboard)
    {
        return json_encode(['inline_keyboard' => $keyboard]);
    }
    public function keyboard($keyboard)
    {
        return json_encode(['keyboard' => $keyboard , 'resize_keyboard' => true]);
    }
    public function isUpdate(){
        $ranges = [
             $this->ip_manager->rangeToIp('149.154.164.0/22'),
             $this->ip_manager->rangeToIp('149.154.160.0/20'),
             $this->ip_manager->rangeToIp('91.108.4.0/22'),
             $this->ip_manager->rangeToIp('91.108.56.0/22'),
        ];
        $user_range = ip2long($_SERVER['REMOTE_ADDR']);
        foreach($ranges as $range){
            $status = [
                'lower' => ip2long($range['lower']),
                'higher' => ip2long($range['higher']),
            ];
            if($status['lower'] < $user_range && $status['higher'] > $user_range){
                return true;
            }
        }
        return false;
    }
}
