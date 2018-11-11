<?php

if (!function_exists("is_sudoer")) {
	/**
	 * @param mixed $userId
	 * @return bool
	 */
	function is_sudoer($userId): bool
	{
		return in_array($userId, SUDOERS);
	}
}
