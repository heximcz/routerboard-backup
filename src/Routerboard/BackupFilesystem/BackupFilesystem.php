<?php

namespace Src\RouterBoard;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

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
 			$bfs = new Filesystem();
 			for($i = 0; $i < $cnt - $rotate; $i++) {
 				$bfs->remove( $files[$i] );
 			}
 		}
	}
	
	/**
	 * @see \Src\RouterBoard\BackupFilesystem\IBackupFilesystem::saveBackupRB()
	 */
	public function saveBackupFile($addr, $content, $folder, $filename, $extension, $identity = NULL) {
		if ( !$content ) {
			$this->logger->log( 'Router: ' . $addr . ' Size of the file: ' . $filename . '.' . $extension . ' is zero!', $this->logger->setError() );
			return false;
		}
		$bfs = new Filesystem();
		$backupdir = $folder . DIRECTORY_SEPARATOR;
		if ( $identity )
			$backupdir .= $identity . '_' . $addr . DIRECTORY_SEPARATOR;
		else
			$backupdir .= $addr . DIRECTORY_SEPARATOR;
		if ( !$bfs->exists( $backupdir ) )
			$bfs->mkdir( $backupdir, 0700 );
		$bfs->dumpFile( $backupdir . $filename . '.' . $extension, $content, 0600);
		$this->rotateBackupFiles($backupdir, $extension);
		return true;
	}
	
}
