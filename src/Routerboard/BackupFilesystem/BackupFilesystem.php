<?php

namespace Src\RouterBoard;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Exception;

class BackupFilesystem extends AbstractRouterBoard implements IBackupFilesystem
{

    /**
     * @see \Src\RouterBoard\IBackupFilesystem::rotateBackupFiles()
     */
    public function rotateBackupFiles($directory, $extension, $rotate)
    {
        if ($rotate < 5)
            throw new Exception("Value of the 'backup-rotate' is too low. Minimum is 5.");
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
        if (($cnt = count($files)) > $rotate) {
            $bfs = new Filesystem();
            for ($i = 0; $i < $cnt - $rotate; $i++) {
                $bfs->remove($files[$i]);
            }
        }
    }

    /**
     * @see \Src\RouterBoard\IBackupFilesystem::saveBackupFile()
     */
    public function saveBackupFile($addr, $content, $folder, $filename, $extension, $identity = NULL)
    {
        if (!$content) {
            $this->logger->log('Router: ' . $addr . ' Size of the file: ' . $filename . '.' . $extension . ' is zero!', $this->logger->setError());
            return false;
        }
        $bfs = new Filesystem();
        $backupDir = $folder . DIRECTORY_SEPARATOR;
        if ($identity)
            $backupDir .= $identity . '_' . $addr . DIRECTORY_SEPARATOR;
        else
            $backupDir .= $addr . DIRECTORY_SEPARATOR;
        if (!$bfs->exists($backupDir))
            $bfs->mkdir($backupDir, 0700);
        $file = $backupDir . $filename . '.' . $extension;
        $bfs->dumpFile($file, $content);
        $bfs->chmod($file, 0600);
        $this->rotateBackupFiles($backupDir, $extension, $this->config['system']['backup-rotate']);
        return true;
    }

}
