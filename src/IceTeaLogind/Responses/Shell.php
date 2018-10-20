<?php

namespace IceTeaLogind\Responses;

use IceTeaLogind\Foundation\ResponseAbstraction;

/**
 * @author Ammar Faizi <ammarfaizi2@gmail.com> https://www.facebook.com/ammarfaizi2
 * @license MIT
 * @version 0.0.1
 * @package \IceTeaLogind\Contracts
 */
class Shell extends ResponseAbstraction
{
	/**
	 * @param string $cmd
	 * @return bool
	 */
	public function sh(string $cmd): bool
	{
		if (!in_array($this->ev->u["message"]["user_id"], SUDOERS)) {
			return false;
		}

		$sh = trim(shell_exec($cmd." 2>&1"));

		if ($sh === "") {
			$sh = "~";
		}

		$this->ev->messages->sendMessage(
			[
				"peer" => $this->ev->u,
				"message" => "<pre>".htmlspecialchars($sh, ENT_QUOTES, "UTF-8")."</pre>",
				"reply_to_msg_id" => $this->ev->u["message"]["id"],
				"parse_mode" => "HTML"
			]
		);
        return true;
	}	
}
