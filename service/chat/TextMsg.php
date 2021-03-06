<?php

declare(strict_types=1);
/**
 * @author zhang rui<zhangruirui@zhangruirui.com>
 * @see https://github.com/zhangrui-git/im.git/
 */

namespace service\chat;


use core\Message;
use core\Service;
use response\chat\SendTo;

class TextMsg extends Service
{
    public string $msgType = 'chat';
    public string $module = 'text';

    public function getMsgType(): string
    {
        return $this->msgType;
    }

    public function getModule(): string
    {
        return $this->module;
    }

    public function sendTo(Message $msg, string $ssid, string $content)
    {
        $ws = $this->server->ws;
        $ssidTable = $this->server->ssidTable;
        $log = $this->server->log;
        $toFd = $ssidTable->get($ssid, 'fd');
        if ($toFd !== false) {
            $sendTo = new SendTo();
            $sendTo->setForm($msg->ssid)->setContent($content);
            $ws->push($toFd, strval($sendTo));
        } else {
            $log->debug('offline', ['ssid' => $ssid]);
        }

        return null;
    }
}