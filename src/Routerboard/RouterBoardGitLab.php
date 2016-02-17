<?php

namespace Src\RouterBoard;

//use Src\Adapters\RouterBoardDBAdapter;
use Src\RouterBoard\GitLabAPI;
use Exception;

class RouterBoardGitLab extends AbstractRouterBoard  implements IRouterBoardBackup {
	
	/**
	 * @see \Src\RouterBoard\IRouterBoardBackup::backupAllRouterBoards()
	 */
	public function backupAllRouterBoards() {
		$gitlab = new GitLabAPI( $this->config, $this->logger );
		if ( !$gitlab->checkProjectName() ) {
			$this->logger->log( "Project '" . $this->config['gitlab']['project-name'] . "' does not exist in repo. Creating new ...", $this->logger->setNotice() );
			$this->createNewProject($gitlab);
		}
		
	}
	
	/**
	 * @see \Src\RouterBoard\IRouterBoardBackup::backupOneRouterBoard()
	 */
	public function backupOneRouterBoard(array $addr) {
		// TODO: Auto-generated method stub
	}
	
	private function createNewProject( GitLabAPI $gitlab) {
		if ( $gitlab->createProject() ) {
			$this->logger->log( "Project '" . $this->config['gitlab']['project-name'] . "' has been created successfully.", $this->logger->setNotice() );
			return true;
		}
		throw new Exception("Can not create new project in GitLab!");
	}
}

