<?php

namespace IceTeaLogind\Responses;

use IceTeaLogind\Foundation\ResponseAbstraction;

/**
 * @author Ammar Faizi <ammarfaizi2@gmail.com> https://www.facebook.com/ammarfaizi2
 * @license MIT
 * @version 0.0.1
 * @package \IceTeaLogind\Contracts
 */
class Me extends ResponseAbstraction
{
	/**
	 * @return bool
	 */
	public function me(): bool
	{
		$this->ev->messages->sendMessage(
			[
				"peer" => $this->ev->u,
				"message" => "There is no data stored for this user.",
				"reply_to_msg_id" => $this->ev->u["message"]["id"]				
			]
		);
        return true;
	}	
}
