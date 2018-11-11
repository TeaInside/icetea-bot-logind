<?php

namespace IceTeaLogind;

use danog\MadelineProto\Logger;
use danog\MadelineProto\RPCErrorException;
use danog\MadelineProto\EventHandler as BaseEventHandler;

/**
 * @author Ammar Faizi <ammarfaizi2@gmail.com> https://www.facebook.com/ammarfaizi2
 * @license MIT
 * @version 0.0.1
 * @package \IceTeaLogind
 */
class EventHandler extends BaseEventHandler
{
    /**
     * @var array
     */
    public $u = [];

    /**
     * @var array
     */
    public $d = [];

    /**
     * Constructor.
     */
    public function __construct($madelineProto)
    {
        parent::__construct($madelineProto);
    }

    /**
     * @param array $u
     * @return void
     */
    public function onAny(array $u): void
    {
        $this->u = $u;
        var_dump("any", $u);
        Logger::log("Received an update of type ".$u["_"]);
    }

    /**
     * @return void
     */
    public function onLoop(): void
    {
        Logger::log("Working...");
    }

    /**
     * @param array $u
     * @return void
     */
    public function onUpdateNewChannelMessage(array $u): void
    {
        var_dump("channel update", $u);
        $this->d["chat_type"] = "channel";
        $this->onUpdateNewMessage($u);
    }

    /**
     * @param array $u
     * @return void
     */
    public function onUpdateNewMessage(array $u): void
    {
        var_dump("new message update", $u);
        $this->u = $u;
        if (empty($u)) {
            return;
        }

        if (isset($u["message"]["from_id"])) {
            $this->d["user_id"] = $u["message"]["from_id"];
        } else if (isset($u["message"]["user_id"])) {
            $this->d["user_id"] = $u["message"]["user_id"];
        } else {
            
        }

        print json_encode($u, 128);

        $this->d["msg_type"] =
            isset($u["message"]["message"]) && 
            is_string($u["message"]["message"]) ? 
            "text" : "other";

        $this->d["text"] =
            $this->d["msg_type"] === "text" ? 
                $u["message"]["message"] : "";

        if (!isset($this->d["chat_type"])) {
            $this->d["chat_type"] = "private";
        }

        if (isset($u["message"]["out"]) && $u["message"]["out"]) {
            return;
        }
        
        $res = json_encode($u, JSON_PRETTY_PRINT);
        if ($res == "") {
            $res = var_export($u, true);
        }

        try {
            if (in_array($u["_"], [
                "updateNewMessage",
                "updateNewChannelMessage"
            ])) {
                $pid = pcntl_fork();
                if ($pid === 0) {
                    $rp = new Response($this);
                    $rp->run();
                    exit(0);
                }
                $status = null;
                pcntl_waitpid($pid, $status, WNOHANG);
            }
        } catch (RPCErrorException $e) {
            $this->messages->sendMessage(
                [
                    "peer" => "ammarfaizi2", 
                    "message" => "An error occured: ".$e->getCode().": ".$e->getMessage().PHP_EOL.$e->getTraceAsString()
                ]
            );
        }
    }
}
