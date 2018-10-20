<?php

namespace IceTeaLogind\Foundation;

use IceTeaLogind\EventHandler;

/**
 * @author Ammar Faizi <ammarfaizi2@gmail.com> https://www.facebook.com/ammarfaizi2
 * @license MIT
 * @version 0.0.1
 * @package \IceTeaLogind\Foundation
 */
abstract class ResponseAbstraction
{
	/**
	 * @var \IceTeaLogind\EventHandler $ev
	 */
	protected $ev;

	/**
	 * @param \IceTeaLogind\EventHandler $ev
	 *
	 * Constructor.
	 */
	public function __construct(EventHandler $ev)
	{
		$this->ev = $ev;
	}
}
