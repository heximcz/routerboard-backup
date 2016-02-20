<?php

namespace Src\RouterBoard;

use Gitlab\Client;
use Gitlab\Api\Projects;
use Gitlab\Model\Project;

class GitLabAPI extends AbstractGitLabAPI {
	
	private $client;
	private $idproject;
	
	public function __construct($config, $logger) {
		parent::__construct($config, $logger);
		$this->client = new Client( $this->config['gitlab']['url'] );
		$this->client->authenticate( $this->config['gitlab']['token'], $this->config['gitlab']['auth-method'] );
	}
	
	/**
	 * Check if project with name ['gitlab']['project-name'] does exist in repository.
	 * @return boolean
	 */
	public function checkProjectName() {
		$project = new Projects( $this->client );
		return $this->projectAccessibleSearch( $this->config['gitlab']['project-name'], $project->accessible(), 'name');
	}
	
	/**
	 * Project ID in GitLab
	 * @return integer
	 */
	public function getProjectID() {
		return $this->idproject;
	}
	
	/**
	 * Create new project with ['gitlab']['project-name'] name.
	 * @return boolean
	 */
	public function createProject() {
		$project = new Project();
		$project->create($this->client, $this->config['gitlab']['project-name'], array(
				'description' => 'Mikrotik RouterOS backup files.',
				'issues_enabled' => false
		));
		return $this->checkProjectName();
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
	
	private function projectAccessibleSearch($name, $array, $value) {
		foreach ($array as $key) {
			if ($key[$value] === $name) {
				$this->idproject = $key['id'];
				return true;
			}
		}
		return false;
	}

}
