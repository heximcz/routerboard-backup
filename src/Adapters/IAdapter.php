<?php 
namespace Src\Adapters;

interface IAdapter{
	/**
	 * Get all IP address and identity from db
	 * @return array|bool
	 */
	public function getIP();
	
	/**
	 * Get one IP address and identity from db
	 * @return array|bool
	 */
	public function getOneIP($addr);
	
	/**
	 * Save new IP to db
	 * @param array of IP address
	 * @return boolean
	 */
	public function addIP($addr,$identity);
	
	/**
	 * Update IP in db
	 * @param string $oldIP
	 * @param string $newIP
	 * @param string RouterBoard Identity
	 * @return boolean
	 */
	public function updateIP($oldAddr,$newAddr,$identity);
	
	/**
	 * Delete IP from db
	 * @param string $addr
	 * @return boolean
	 */
	public function deleteIP($addr);
	
	/**
	 * Check if IP address is in db
	 * @param string $addr
	 * @return boolean
	 */
	public function checkExistIP($addr);
	
	/**
	 * Update last backup time
	 * @param string $addr
	 * @return boolean
	 */
	public function updateBackupTime($addr);
	
}
