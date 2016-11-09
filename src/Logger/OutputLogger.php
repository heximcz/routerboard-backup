<?php
namespace Src\Logger;

use Symfony\Component\Console\Output\OutputInterface;
use Src\Logger\AbstractMailLogger;

class OutputLogger extends AbstractMailLogger implements ILogger
{

    private $output;

    public function __construct(OutputInterface $output)
    {
        parent::__construct();
        $this->output = $output;
    }

    public function log($message, $level = self::LEVEL_INFO)
    {
        $msg = sprintf('[%1$s] %2$s: %3$s', date("Y-d-m H:i:s"), $level, $message);
        if ($level == self::LEVEL_INFO)
            $this->output->writeln('<info>' . $msg . '</info>');

        if ($level == self::LEVEL_NOTICE)
            $this->output->writeln('<comment>' . $msg . '</comment>');

        if ($level == self::LEVEL_DEBUG)
            $this->output->writeln('<question>' . $msg . '</question>');

        if ($level == self::LEVEL_ERROR) {
            $this->output->writeln('<error>' . $msg . '</error>');
            $this->setMailBody($message, $level);
        }
    }

    public function setError()
    {
        return self::LEVEL_ERROR;
    }

    public function setDebug()
    {
        return self::LEVEL_DEBUG;
    }

    public function setInfo()
    {
        return self::LEVEL_INFO;
    }

    public function setNotice()
    {
        return self::LEVEL_NOTICE;
    }

    protected function setMailBody($message, $level)
    {
        $this->mailBody .= sprintf("%1s [%2s]: %3s\n", $level, date("Y-d-m H:i:s"), $message);
    }

}