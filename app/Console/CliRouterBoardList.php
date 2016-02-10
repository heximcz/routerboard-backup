<?php

namespace App\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\ArrayInput;
use Src\Logger\OutputLogger;
use Src\RouterBoard\RouterBoardList;

class CliRouterBoardList extends Command {

	private $config;

	public function __construct(array $config) {
		parent::__construct ();
		$this->config = $config;
	}
	
	protected function configure() {
		$this
		->setName ( 'rb:list' )
		->setDescription ( 'Mikrotik RouterBoard print backup list.' )
		->addArgument ( 'action', InputArgument::OPTIONAL, 'list', 'list' )
		->addUsage(
				'<comment>-> by default print all routers from backup list to stdout.</comment>'
				)
		;
	}
	
	protected function execute( InputInterface $input, OutputInterface $output ) {
		$logger = new OutputLogger ( $output );
		$rprint  = new RouterBoardList( $this->config, $logger );
		$action = $input->getArgument ( 'action' );
		switch ($action) {
			case "list":
					$logger->log ( "Action: Print all routers from backup list." );
					$rprint->printAllRouterBoards();
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
