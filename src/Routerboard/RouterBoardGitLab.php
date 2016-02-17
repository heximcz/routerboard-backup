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
		$this->checkExistProject( $this->config['gitlab']['project-name'] );
		
	}
	
	/**
	 * @see \Src\RouterBoard\IRouterBoardBackup::backupOneRouterBoard()
	 */
	public function backupOneRouterBoard(array $addr) {
		// TODO: Auto-generated method stub
	}
	

	/**
	 * Check if project with $name does exist in repository, if not try create it
	 * 
	 * @param string $name
	 * @throws Exception
	 */
	protected function checkExistProject($name) {
		$gitlab = new GitLabAPI( $this->config, $this->logger );
		if ( !$gitlab->checkProjectName() ) {
			$this->logger->log( "Project '" . $name . "' does not exist in repo. Creating new ...", $this->logger->setNotice() );
			if ( $gitlab->createProject() ) {
				$this->logger->log( "Project '" . $this->config['gitlab']['project-name'] . "' has been created successfully.", $this->logger->setNotice() );
				return;
			}
			throw new Exception("Can not create new project in GitLab!");
		}
	}
	
}

