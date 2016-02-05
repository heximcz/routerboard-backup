<?php

namespace Src\RouterBoard;

interface IRouterBoardMod {
	
	/**
	 * Add new IP address to backup list
	 * @param ip address $ip
	 */
	public function addNewIP(array $ip);
	
	/**
	 * Delete IP address from backup list
	 * @param ip address $ip
	 */
	public function deleteIP(array $ip);
	
	/**
	 * Change existing IP address in backup list
	 * @param old and new ip address in array $ip
	 */
	public function updateIP(array $ip);
	
}