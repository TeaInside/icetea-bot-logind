<?php

namespace IceTeaLogind;

/**
 * @author Ammar Faizi <ammarfaizi2@gmail.com> https://www.facebook.com/ammarfaizi2
 * @license MIT
 * @version 0.0.1
 * @package \IceTeaLogind
 */
trait ResponseRoutes
{
	/**
	 * @return void
	 */
	private function buildRoutes(): void
	{
		if ($this->ev->d["text"] !== "") {
			
			$txt = $this->ev->d["text"];

			/**
			 * Ping bot
			 */
			$this->set(function () use ($txt) {
				return [
					(bool)preg_match(
						"/^(\!|\/|\~|\.)?ping$/Usi",
						$txt
					),
					[]
				];
			}, "Ping@ping");

			/**
			 * There is no data stored for this user.
			 */
			$this->set(function () use ($txt) {
				return [
					(bool)preg_match(
						"/^(\!|\/|\~|\.)?me($|\s.+)/Usi",
						$txt
					),
					[]
				];
			}, "Me@me");

			/**
			 * Show date.
			 */
			$this->set(function () use ($txt) {
				return [
					(bool)preg_match(
						"/^(\!|\/|\~|\.)?date$/Usi",
						$txt
					),
					[]
				];
			}, "Date@show");

			/**
			 * Shell exec. (sudo only)
			 */
			$this->set(function () use ($txt) {
				$m = [];
				
				if (preg_match(
					"/^(?:\!|\/|\~|\.)?(?:sh[\s\n]*)(.*)$/Usi",
					$txt,
					$m
				)) {
					return [
						true,
						[$m[1]]
					];
				}
				return [false, []];
			}, "Shell@sh");



			/**
			 * Tea AI override.
			 */
			$this->set(function () use ($txt) {
				return [true, []];
			}, function () use ($txt) {
				if (isset($this->ev->u["message"]["from_id"])) {
					$u = $this->ev->u["message"]["from_id"];
				} else {
					$u = $this->ev->u;
				}

				$uVector = $this->ev->users->getUsers(
					[
						"id" => [$u]
					]
				);

				$name = $uVector[0]["first_name"];
				if (isset($uVector[0]["last_name"])) {
					$name .= " ".$uVector[0]["last_name"];
				}

				$name = escapeshellarg($name);
				$st = trim(shell_exec(
					"echo ".escapeshellarg($txt)." | php ".BASEPATH."/../teaAI/bin/TeaAI.php chat --stdout-output --stdin-input --name={$name}"
				));

				if ($st !== "") {
					$this->ev->messages->sendMessage(
						[
							"peer" => $this->ev->u,
							"message" => $st,
							"reply_to_msg_id" => $this->ev->u["message"]["id"]
						]
					);
				}
			});
		}
	}
}