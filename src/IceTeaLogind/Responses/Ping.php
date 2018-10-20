<?php

namespace IceTeaLogind\Responses;

use IceTeaLogind\Foundation\ResponseAbstraction;

/**
 * @author Ammar Faizi <ammarfaizi2@gmail.com> https://www.facebook.com/ammarfaizi2
 * @license MIT
 * @version 0.0.1
 * @package \IceTeaLogind\Contracts
 */
class Ping extends ResponseAbstraction
{
	/**
	 * @return bool
	 */
	public function ping(): bool
	{
		$this->ev->messages->sendMessage(
			[
				"peer" => $this->ev->u,
				"message" => "Pong!",
				"reply_to_msg_id" => $this->ev->u["message"]["id"]
			]
		);
        return true;
	}	
}
