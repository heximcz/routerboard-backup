<?php

namespace Src\RouterBoard;

use phpseclib\Crypt\RSA;
use Src\Logger\OutputLogger;
use Symfony\Component\Filesystem\Filesystem;
use Exception;

class SecureTools extends AbstractRouterBoard
{

    private $fsys;

    /**
     * SecureTools constructor.
     * @param array $config
     * @param OutputLogger $logger
     */
    public function __construct(array $config, OutputLogger $logger)
    {
        parent::__construct($config, $logger);
        $this->fsys = new Filesystem();
    }

    /**
     * Check if ssh-rsa keys exist. If not, will be created
     * @throws Exception - if ssh directory no exist / if keys no been generated
     */
    public function checkRSA()
    {
        // does exist ssh directory ?
        if (!$this->fsys->exists($this->config ['system'] ['ssh-dir'])) {
            $this->fsys->mkdir($this->config ['system'] ['ssh-dir'], 0700);
            $this->logger->log("The SSH directory: " . $this->config ['system'] ['ssh-dir'] . " has been created !", $this->logger->setNotice());
        }
        if (!$this->fsys->exists($this->config ['system'] ['ssh-dir'] . DIRECTORY_SEPARATOR . 'id_rsa.pub')) {
            $this->logger->log("The SSH-RSA public key does not exist. Creating new.", $this->logger->setNotice());
            $this->createRSA();
        } else
            $this->logger->log("The SSH-RSA public key does exist. OK.");
    }

    /**
     * @throws Exception
     */
    protected function createRSA()
    {
        $rsa = new RSA();
        $rsa->setPublicKeyFormat(RSA::PUBLIC_FORMAT_OPENSSH);
        $rsa->setComment($this->config['routerboard']['backupuser'] . "@backup");
        $key = $rsa->createKey(2048);
        if (!empty ($key)) {
            // be safe
            $this->backupExistRSA();
            // create id_rsa.pub (public key)
            $this->fsys->dumpFile(
                $this->config['system']['ssh-dir'] . DIRECTORY_SEPARATOR . 'id_rsa.pub',
                $key['publickey']
            );
            // create id_rsa (private key)
            $this->fsys->dumpFile(
                $this->config['system']['ssh-dir'] . DIRECTORY_SEPARATOR . 'id_rsa',
                $key['privatekey']
            );
            // set permissions -rw-------
            $this->fsys->chmod($this->config['system']['ssh-dir'] . DIRECTORY_SEPARATOR . 'id_rsa', 0600);
            $this->fsys->chmod($this->config['system']['ssh-dir'] . DIRECTORY_SEPARATOR . 'id_rsa.pub', 0600);
            // backup existing RSA files for sure.
            $this->backupExistRSA('routerboard-backup');
            $this->logger->log("The SSH-RSA public key has been created. Never delete those files! (id_rsa,id_rsa.pub)", $this->logger->setNotice());
            return;
        }
        throw new Exception(get_class($this) . " can not create the ssh-rsa public key file!");
    }

    /**
     * Create backup of the RSA files
     * @param string $suffix (if empty, suffix is timestamp)
     */
    private function backupExistRSA($suffix = '')
    {
        if (empty($suffix))
            $suffix = date("Ydmhis", time());
        $originFile = $this->config['system']['ssh-dir'] . DIRECTORY_SEPARATOR . 'id_rsa.pub';
        if ($this->fsys->exists($originFile)) {
            $targetFile = $originFile . "." . $suffix . '.bak';
            $this->fsys->copy($originFile, $targetFile);
            $this->fsys->chmod($targetFile, 0600);
        }
        $originFile = $this->config['system']['ssh-dir'] . DIRECTORY_SEPARATOR . 'id_rsa';
        if ($this->fsys->exists($originFile)) {
            $targetFile = $originFile . "." . $suffix . '.bak';
            $this->fsys->copy($originFile, $targetFile);
            $this->fsys->chmod($targetFile, 0600);
        }
    }

}
