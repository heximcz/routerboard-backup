<?php

namespace App\Console;

use Src\RouterBoard\RouterBoardDecode;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\ArrayInput;
use Src\Logger\OutputLogger;

class CliRouterBoardDecode extends Command
{

    private $config;

    public function __construct(array $config)
    {
        parent::__construct();
        $this->config = $config;
    }

    protected function configure()
    {
        $this
            ->setName('rb:decode')
            ->setDescription('Decode a base64 file from gitlab.')
            ->addArgument('action', InputArgument::OPTIONAL, 'base64', 'base64')
            ->addOption('file', 'f', InputOption::VALUE_REQUIRED, 'Base64 file path')
            ->addUsage(
                '-f ./rbackup.backup' .
                '<comment>-> decode base64 rbackup.backup file from gitlab.</comment>'
            )
            ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $logger = new OutputLogger ($output);
        $decode = new RouterBoardDecode($this->config, $logger);

        $action = $input->getArgument('action');
        switch ($action) {
            case "base64":
                if ($input->getOption('file')) {
                    $logger->log("Action: Decoding file.");
                    try {
                        $decode->decodeBase64File($input->getOption('file'));
                    } catch (\Exception $e) {
                        $logger->log($e->getMessage(), $logger->setError());
                    }
                }
                break;
            default:
                $this->defaultHelp($output);
                break;
        }
    }


    /**
     * @param $output
     * @throws \Exception
     */
    private function defaultHelp($output)
    {
        $command = $this->getApplication()->get('help');
        $command->run(new ArrayInput(['command_name' => $this->getName()]), $output);
    }

}
