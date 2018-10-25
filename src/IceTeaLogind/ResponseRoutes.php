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

			$this->set(function () use ($txt) {
				return [
					(bool)preg_match(
						"/^(\!|\/|\~|\.)?ping$/Usi",
						$txt
					),
					[]
				];
			}, "Ping@ping");

			$this->set(function () use ($txt) {
				return [
					(bool)preg_match(
						"/^(\!|\/|\~|\.)?me($|\s.+)/Usi",
						$txt
					),
					[]
				];
			}, "Me@me");

			$this->set(function () use ($txt) {
				return [
					(bool)preg_match(
						"/^(\!|\/|\~|\.)?date$/Usi",
						$txt
					),
					[]
				];
			}, "Date@show");

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
		}
		
			require BASEPATH."/../teaAI/bootstrap/init.php";
			$st = new TeaAI\TeaAI("chat");
			$st->setInput($txt);
			$st = $st->run();
			if (is_string($st)) {
			$this->ev->messages->sendMessage(
					[
				"peer" => $this->ev->u,
				"message" => $st,
				"reply_to_msg_id" => $this->ev->u["message"]["id"]				
					]
				);
			}
	}
}