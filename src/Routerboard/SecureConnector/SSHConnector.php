<?php

namespace Src\RouterBoard;

use phpseclib\Net\SSH2;
use phpseclib\Net\SCP;
use phpseclib\Crypt\RSA;
use Src\RouterBoard\BackupFilesystem;
use Symfony\Component\Filesystem\Exception\IOException;

class SSHConnector extends AbstractRouterBoard {

	/**
	 * Create backup user over ssh
	 * @param ip $addr
	 * @return mixed (false or identity of the router)
	 */
	public function createBackupAccount($addr, $port) {
		$bcpuser = $this->config['routerboard']['backupuser'];
		$keyname = 'id_rsa-backup-user.pub';
		if ( $ssh = $this->sshConnect($addr, $port, false) ) {
			$scp = new SCP($ssh);
			if ( !$scp->put($keyname, 
					$this->config['system']['ssh-dir'] . DIRECTORY_SEPARATOR . 'id_rsa.pub', SCP::SOURCE_LOCAL_FILE) ) {
				$this->logger->log( "The SSH-RSA file copy to the :'" . $addr . "' router fails!", $this->logger->setError() );
				return false;
			}
			$ssh->exec( 'user add name=' . $bcpuser . ' group=full' );
			sleep(1);
			$ssh->exec( 'user ssh-keys import user=' . $bcpuser . ' public-key-file=' . $keyname );
			sleep(1);
			if ( $ssh->exec( 'user comment ' . $bcpuser . ' comment="Backup User"' )) {
				$this->logger->log( "Creating of the backup account '" . $bcpuser . "' fails!", $this->logger->setError() );
				return false;
			}
			sleep(1);
			$identity = $ssh->exec( 'system identity print' );
			$identity = trim( str_replace('name:', '', trim($identity)) );
			$this->logger->log( "The backup account '" . $bcpuser . "' at '" . $identity . "'@'" . $addr . "' has been created successfully!");
			$this->sshDisconnect($ssh);
			return $identity;
		}
		return false;
	}
	
	/**
	 * Create new backup files
	 * Auto remove old backup files (only files created by this script wil be removed)
	 * Download backup files from routerboard via SCP
	 * Save files to the desination
	 * 
	 * @param string $addr IP address
	 * @param string $filename
	 * @param string $folder
	 * @param string $identity of the routerboard
	 * @return boolean
	 */
	public function getBackupFile($addr, $port, $filename, $folder, $identity) {
		$user = $this->config['routerboard']['backupuser'];
		$msg = 'Connect to the: ' . $user . "@" . $addr . ":" .  $port . ' has been ';
		if ( $ssh = $this->sshConnect($addr, $port, true) ) {
			$this->logger->log( $msg . 'successfully.' );
			// remove old backup files
			$ssh->exec( 'file remove [/file find where name~"' . $user . '-"]' );
			// create new backups
			$ssh->exec( 'system backup save name=' . $filename );
			$ssh->exec( 'export compact file=' . $filename );
			// download and save new backup files
			$scp = new SCP($ssh);
			$bfs = new BackupFilesystem( $this->config, $this->logger );
			if ( $bfs->saveBackupFile( $addr, $scp->get( $filename . '.backup' ), $folder, $filename, 'backup', $identity ) &&
				 $bfs->saveBackupFile( $addr, $scp->get( $filename . '.rsc' ), $folder, $filename, 'rsc', $identity ) ) 
				{
				$this->sshDisconnect($ssh);
				return true;
				}
			$this->sshDisconnect($ssh);
			return false;
		}
		$this->logger->log( $msg . 'fails!', $this->logger->setError() );
		return false;
	}
	
	/**
	 * SSH connect via user&passwd or user&rsakey
	 *
	 * @param string ip $addr
	 * @param bool $type - connect via user&rsakey(true), user&password(false)
	 * @return mixed object | false
	 */
	protected function sshConnect($addr, $port, $type) {
		set_error_handler( array( $this, "myErrorHandler" ), E_ALL );
		$ssh = new SSH2( $addr, $port );
		// user&password
		$ssh->setWindowSize(1024,768);
		if ( !$type && $ssh->login( $this->config['routerboard']['rblogin'], $this->config['routerboard']['rbpasswd'] ))
			return $ssh;
		// user&rsakey
		if ( $type ) {
			$key = new RSA();
			$key->loadKey( file_get_contents( $this->config['system']['ssh-dir'] . DIRECTORY_SEPARATOR . 'id_rsa' ) );
			if ( $ssh->login( $this->config['routerboard']['backupuser'], $key ))
				return $ssh;
			}
		return false;
	}

	protected function sshDisconnect($ssh) {
		$ssh->disconnect();
		restore_error_handler();
	}
	
	/**
	 * My error handler, I do not like this: PHP Notice:  Cannot connect...
	 * Must be a public!
	 */
	public function myErrorHandler($severity, $message) {
		if ( !(error_reporting() & $severity) )
			return;
		switch ( $severity ) {
			case E_NOTICE:
			case E_USER_NOTICE:
			case E_WARNING:
			case E_USER_WARNING:
				$this->logger->log( $message , $this->logger->setError() );
				break;
			case E_ERROR:
			case E_USER_ERROR:
			default:
				throw new IOException( $message );
				break;
		}
	}

}
