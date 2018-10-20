<?php

namespace IceTeaLogind;

use Closure;
use IceTeaLogind\Exceptions\ResponseException;

/**
 * @author Ammar Faizi <ammarfaizi2@gmail.com> https://www.facebook.com/ammarfaizi2
 * @license MIT
 * @version 0.0.1
 * @package \IceTeaLogind
 */
class Response
{	
	use ResponseRoutes;

	/**
	 * @var array
	 */
	private $routes = [];

	/**
	 * @var \IceTeaLogind\EventHandler
	 */
	private $ev;

	/**
	 * @param \IceTeaLogind\EventHandler $ev
	 *
	 * Constructor.
	 */
	public function __construct(EventHandler $ev)
	{
		$this->ev = $ev;
	}

	/**
	 * @throws \IceTeaLogind\Excepitons\ResponseException
	 * @return void
	 */
	private function matching(): void
	{
		foreach ($this->routes as $k => $v) {
			$inv = $v[0]($this->ev);
			if (
				(!is_array($inv)) ||
				(!isset($inv[0], $inv[1]))
			) {
				$route = is_string($route) ? $route : var_export($u, true);
				throw new ResponseException(
					sprintf(
						"Invalid return data for route %s (at offset %d)", 
						$route, 
						$k
					)
				);
			}
			if ($inv[0]) {
				if (is_string($v[1])) {
					$v[1] = explode("@", $v[1], 2);
					if (count($v[1]) === 2) {
						$class = "\\IceTeaLogind\\Responses\\{$v[1][0]}";
						$class = new $class($this->ev);
						if (call_user_func_array([$class, $v[1][1]], $inv[1])) {
							return;
						}
					} else {
						throw new ResponseException(
							sprintf("Invalid route: %s", implode("@", $v[1]))
						);
					}
				} else if ($v[1] instanceof Closure) {
					if (call_user_func_array($v[1], $inv[1])) {
						return;
					}
				}
			}
		}
	}

	/**
	 * @param \Closure $condition
	 * @param mixed    $action
	 * @return void
	 */
	private function set(Closure $condition, $action): void
	{
		$this->routes[] = [$condition, $action];
	}

	/**
	 * @return void
	 */
	public function run(): void
	{
		$this->buildRoutes();
		$this->matching();
	}
}
