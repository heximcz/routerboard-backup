<?php

namespace App\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\ArrayInput;
use Src\RouterBoard\RouterBoardMod;
use Src\RouterBoard\SecureTools;
use Src\Logger\OutputLogger;

class CliRouterBoardModify extends Command {

	private $config;

	public function __construct(array $config) {
		parent::__construct ();
		$this->config = $config;
	}
	
	protected function configure() {
		$this
		->setName ( 'rb:mod' )
		->setDescription ( 'Mikrotik RouterBoard add/delete/update IP addresses.' )
		->addArgument ( 'action', InputArgument::OPTIONAL, 'addnew | delete | update', 'addnew' )
		->addOption ( 'addr', 'i', InputOption::VALUE_IS_ARRAY | InputOption::VALUE_REQUIRED, 'IPv4 address of router.' )
		->addUsage(
				'addnew -i 192.168.1.1 ' .
				'<comment>-> add one router to backup list.</comment>'
				)
		->addUsage(
				'addnew -i 192.168.1.1 -i 192.168.1.2 -i 192.168.1.3 -i 192.168.1.4 ' .
				'<comment>-> multiple add new IP to backup list.</comment>'
				)
		->addUsage(
				'update -i 192.168.1.1 -i 192.168.1.2 ' .
				'<comment>-> change IP from old to new, the order does not matter (only two IP allowed)</comment>'
				)
		->addUsage(
				'delete -i 192.168.1.1 ' .
				'<comment>-> delete one router from backup list.</comment>'
				)
		->addUsage(
				'delete -i 192.168.1.1 -i 192.168.1.2 -i 192.168.1.3 -i 192.168.1.4 ' .
				'<comment>-> multiple delete IP from backup list.</comment>'
				)
		;
	}
	
	protected function execute( InputInterface $input, OutputInterface $output ) {
		// Check IP address input option.
		if ( !$input->getOption ( 'addr' ) ) {
			$this->defaultHelp($output);
    		return;
		}
		$logger = new OutputLogger ( $output );
		$rbmod  = new RouterBoardMod( $this->config, $logger );
		$action = $input->getArgument ( 'action' );
		switch ($action) {
			case "addnew":
				$logger->log ( "Action: Add a new router/s to backup list." );
				$rsa = new SecureTools( $this->config, $logger );
				$rsa->checkRSA();
				$rbmod->addNewIP( $input->getOption ( 'addr' ) );
				break;
			case "delete":
				$logger->log ( "Action: Delete a router/s from backup list." );
				$rbmod->deleteIP( $input->getOption ( 'addr' ) );
				break;
			case "update":
				$logger->log ( "Action: Update a router ip address in backup list." );
				$rbmod->updateIP( $input->getOption ( 'addr' ) );
				break;
			default:
				$this->defaultHelp($output);
				break;
		}
	}

	/**
	 * Print help to default otput
	 * @param $output
	 */
	private function defaultHelp($output) {
		$command = $this->getApplication()->get('help');
		$command->run(new ArrayInput(['command_name' => $this->getName()]), $output);
	}

}
