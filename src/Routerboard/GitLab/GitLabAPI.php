<?php

namespace Src\RouterBoard;

use Gitlab\Client;
use Gitlab\Model\Project;
use Gitlab\Api\Projects;

class GitLabAPI extends AbstractGitLabAPI {
	
	protected $gitlab;
	
	public function __construct($config, $logger) {
		parent::__construct($config, $logger);
		$this->gitlab = new Client( $this->config['gitlab']['url'] );
		$this->gitlab->authenticate( $this->config['gitlab']['token'], $this->config['gitlab']['auth-method'] );
	}
	
	public function checkProjectName() {
		$project = new Projects( $this->gitlab );
		return $this->arraySearch( $this->config['gitlab']['project-name'], $project->accessible(), 'name');
	}
	
	public function createProject() {
		$project = new Project();
		$project->create($this->gitlab, $this->config['gitlab']['project-name'], array(
				'description' => 'Mikrotik RouterOS backup files.',
				'issues_enabled' => false
		));
		return $this->checkProjectName();
	}
	
	private function arraySearch($name, $array, $value) {
		foreach ($array as $key) {
			if ($key[$value] === $name) {
				return true;
			}
		}
		return false;
	}
	


	
}