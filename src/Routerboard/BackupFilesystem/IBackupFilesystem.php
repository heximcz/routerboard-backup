<?php

namespace Src\RouterBoard;

interface IBackupFilesystem
{

    /**
     * Rotate backup files
     *
     * @param string $directory where find a files
     * @param string $extension of a file
     * @param integer $rotate how many backup files keep
     */
    public function rotateBackupFiles($directory, $extension, $rotate);

    /**
     * Save backup file from routerboard
     *
     * @param string $addr ip address of the router
     * @param string $content contents of the backup file
     * @param string $folder
     * @param string $filename
     * @param string $extension of a file
     * @param string $identity of the routerboard, may be false
     * @return boolean
     */
    public function saveBackupFile($addr, $content, $folder, $filename, $extension, $identity = NULL);

}
