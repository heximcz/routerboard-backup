<?php

namespace Src\RouterBoard;

use Gitlab\Client;
use Gitlab\Api\Projects;
use Gitlab\Api\Groups;
use Gitlab\Model\Project;

class GitLabAPI extends AbstractGitLabAPI {
	
	private $client;
	private $idproject;
	private $idgroup;
	
	public function __construct($config, $logger) {
		parent::__construct($config, $logger);
		$this->client = new Client( $this->config['gitlab']['url'] );
		$this->client->authenticate( $this->config['gitlab']['token'], $this->config['gitlab']['auth-method'] );
		$this->idproject = false;
		$this->idgroup = false;
	}

	/**
	 * Project ID in GitLab
	 * @return integer
	 */
	public function getProjectID() {
		return $this->idproject;
	}

	/**
	 * Group ID in GitLab
	 * @return integer
	 */
	public function getGroupID() {
		return $this->idgroup;
	}

	/**
	 * get GitLab Client object
 	 * @return object \Gitlab\Client 
	 */
	protected function getGitLabClient() {
		return $this->client;
	}

	/**
	 * Check if project with name ['gitlab']['project-name'] does exist in repository.
	 * @return boolean
	 */
	public function checkProjectName() {
		$project = new Projects( $this->client );
		if ( $this->idgroup ) {
			return $this->arraySearchValues ( 
					$this->config['gitlab']['group-name']."/".$this->config['gitlab']['project-name'], 
					$project->accessible(), 
					'path_with_namespace', 
					'id', 
					$this->idproject
					);
		}
		return $this->arraySearchValues ( 
				$this->config['gitlab']['username']."/".$this->config['gitlab']['project-name'], 
				$project->accessible(), 
				'path_with_namespace', 
				'id', 
				$this->idproject
				);
	}

	/**
	 * Check if group exist
	 */
	public function checkGroupName() {
		$groups = new Groups( $this->client );
		return $this->arraySearchValues ( 
				$this->config['gitlab']['group-name'], 
				$groups->all(), 
				'path',
				'id', 
				$this->idgroup
				);
	}

	/**
	 * Create new project from ['gitlab']['project-name']
	 * @return boolean
	 */
	public function createProject() {
		$project = new Project();
		if ( $this->idgroup ) {
			$project->create($this->client, $this->config['gitlab']['project-name'], array(
					'description' => 'Mikrotik RouterOS backup files.',
					'namespace_id' => $this->getGroupID(),
					'issues_enabled' => false
			));
			return $this->checkProjectName();
		}
		$project->create($this->client, $this->config['gitlab']['project-name'], array(
				'description' => 'Mikrotik RouterOS backup files.',
				'issues_enabled' => false
		));
		return $this->checkProjectName();
	}

	/**
	 * Create new group from ['gitlab']['group-name']
	 * @return boolean
	 */
	public function createGroup() {
		$group = new Groups( $this->client );
		$group->create(
				$this->config['gitlab']['group-name'],
				$this->config['gitlab']['group-name'],
				'Mikrotik RouterOS backup group.'
				);
		return $this->checkGroupName();
	}

	/**
	 * Send file to repository
	 * @param string $filePath
	 * @param string $content
	 * @param string $branch
	 * @param string $message
	 * @throws Gitlab\Exception\RuntimeException;
	 */
	public function sendFile($filePath, $content, $branch, $message) {
		$project = new Project( $this->getProjectID(), $this->client );
		$project->updateFile( $filePath, $content, $branch, $message );
	}

	/**
	 * Find specific value in array and save value to &$destination var
	 * @param string $name - find what
	 * @param array $array - data
	 * @param string $value - find in key
	 * @param string $get - get another value from same level
	 * @param reference to the existing variable $destination
	 * @return boolean
	 */
	private function arraySearchValues($name, $array, $value, $get, &$destination) {
		if ( $this->config['gitlab']['debug'] == 1 ) {
			$this->logger->log("Array ". $name, $this->logger->setDebug());
			print_r($array);
		}
		foreach ($array as $key) {
			if ($key[$value] === $name) {
				$destination = $key[$get];
				return true;
			}
		}
		return false;
	}

}
