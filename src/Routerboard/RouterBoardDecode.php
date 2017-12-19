<?php

namespace Src\RouterBoard;

use Symfony\Component\Filesystem\Filesystem;

class RouterBoardDecode extends AbstractRouterBoard
{


    /**
     * @param $file
     * @throws \Exception
     */
    public function decodeBase64File($file)
    {

        $fs = new Filesystem();

        if ($fs->exists($file)) {
            $actualDate = date("Ymdhis");
            if (!$content = base64_decode(file_get_contents($file), true) )
                throw new \Exception('File is not a base64 file!');
            // create backup file of original
            $fs->copy($file, $file.'.orgBase64-'.$actualDate);
            // save decoded file
            $fs->dumpFile($file, $content);
            $this->logger->log('File has been decoded.');
        }
        else
            throw new \Exception('File does not exist.');

    }

}

