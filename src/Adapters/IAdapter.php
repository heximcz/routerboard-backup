<?php 
namespace Src\Adapters;

interface IAdapter{
	/**
	 * Get IP address from db
	 * @return array|bool
	 */
	public function getIP();
	
	/**
	 * Save new IP to db
	 * @param array of IP address
	 * @return boolean
	 */
	public function addIP($addr,$identity);
	
	/**
	 * Update IP in db
	 * @param $oldIP
	 * @param $newIP
	 * @param RouterBoard Identity
	 * @return boolean
	 */
	public function updateIP($oldAddr,$newAddr,$identity);
	
	/**
	 * Delete IP from db
	 * @param $addr
	 * @return boolean
	 */
	public function deleteIP($addr);
	
	/**
	 * Check if IP address is in db
	 * @param ip address $addr
	 * @return boolean
	 */
	public function checkExistIP($addr);
}
