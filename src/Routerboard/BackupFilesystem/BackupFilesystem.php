<?php

namespace Src\RouterBoard;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Filesystem\Exception\IOException;

class BackupFilesystem extends AbstractBackupFilesystem {
	
	/**
	 * @see \Src\RouterBoard\BackupFilesystem\IBackupFilesystem::fileRotate()
	 */
	public function rotateBackupFiles($directory, $extension, $rotate = 5) {
		$finder = new Finder();
		$finder
			->depth('0')
			->name('*.' . $extension)
			->sortByModifiedTime()
			->files()
			->in($directory);
		$files = array();
 		foreach ($finder as $file) {
 			$files[] = $file->getRealpath();
 		}
 		if ( ($cnt = count($files)) > $rotate ) {
 			$fs = new Filesystem();
 			for($i = 0; $i < $cnt - $rotate; $i++) {
 				$fs->remove( $files[$i] );
 			}
 		}
	}
	
	/**
	 * @see \Src\RouterBoard\BackupFilesystem\IBackupFilesystem::saveBackupRB()
	 */
	public function saveBackupFile($addr, $content, $filename, $extension, $identity = false) {
		if ( !$content ) {
			$this->logger->log( 'Router: ' . $addr . ' Size of the file: ' . $filename . '.' . $extension . ' is zero!', $this->logger->setError() );
			return false;
		}
		else {
			$fs = new Filesystem();
			$backupdir = $this->config['system']['backupdir'] . DIRECTORY_SEPARATOR;
			if ( $identity )
				$backupdir .= $identity . '_' . $addr . DIRECTORY_SEPARATOR;
			else
				$backupdir .= $addr . DIRECTORY_SEPARATOR;
			if ( !$fs->exists( $backupdir ) )
				$fs->mkdir( $backupdir, 0700 );
			$fs->dumpFile( $backupdir . $filename . '.' . $extension, $content, 0600);
			$this->rotateBackupFiles($backupdir, $extension);
			return true;
		}
	}
	
}
