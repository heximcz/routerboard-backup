<?php 
namespace Src\Adapters;

interface IAdapter{
	/**
	 * Get all IP address and identity from db
	 * @return mixed (array, false)
	 */
	public function getIP();
	
	/**
	 * Get one IP address and identity from db
	 * @return mixed (array, false)
	 */
	public function getOneIP($addr);
	
	/**
	 * Save new IP to db
	 * @param string $addr
	 * @param integer $port
	 * @param string $identity
	 * @return boolean
	 */
	public function addIP($addr,$port,$identity);
	
	/**
	 * Update IP in db
	 * @param string $oldIP
	 * @param string $newIP
	 * @param integer $newPort
	 * @param string $identity
	 * @return boolean
	 */
	public function updateIP($oldAddr,$newAddr,$newPort,$identity);
	
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
