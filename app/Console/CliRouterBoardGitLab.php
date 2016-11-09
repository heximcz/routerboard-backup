<?php

namespace App\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\OutputInterface;
use Src\Logger\OutputLogger;
use Src\RouterBoard\RouterBoardGitLab;
use Src\RouterBoard\InputParser;

class CliRouterBoardGitLab extends Command
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
            ->setName('rb:gitlab')
            ->setDescription('Mikrotik RouterBoard backup configurations to GitLab project.')
            ->addArgument('action', InputArgument::OPTIONAL, 'backup', 'backup')
            ->addOption('addr', 'i', InputOption::VALUE_IS_ARRAY | InputOption::VALUE_REQUIRED, 'IPv4 address of router.')
            ->addUsage(
                '<comment>-> by default backup all routers from backup list to GitLab.</comment>'
            )
            ->addUsage(
                '-i 192.168.1.1 ' .
                '<comment>-> backup one router to GitLab.</comment>'
            )
            ->addUsage(
                '-i 192.168.1.1 -i 192.168.1.2 ' .
                '<comment>-> backup more routers to GitLab.</comment>'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $logger = new OutputLogger ($output);
        $gitlab = new RouterBoardGitLab($this->config, $logger);
        $action = $input->getArgument('action');
        switch ($action) {
            case "backup":
                if (!$input->getOption('addr')) {
                    $logger->log("Action: Backup all routers from backup list to GitLab.");
                    $gitlab->backupAllRouterBoards();
                    break;
                }
                $logger->log("Action: Backup one or more routers from input to GitLab.");
                $gitlab->backupOneRouterBoard(new InputParser($this->config, $logger, $input->getOption('addr')));
                break;
            default:
                $command = $this->getApplication()->get('help');
                $command->run(new ArrayInput(['command_name' => $this->getName()]), $output);
                break;
        }
    }
}
