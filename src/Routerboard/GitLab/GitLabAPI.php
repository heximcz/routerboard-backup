<?php

namespace Src\RouterBoard;

use Gitlab\Client;
use Gitlab\Api\Projects;
use Gitlab\Api\Groups;
use Gitlab\Model\Project;
use Gitlab\Exception\RuntimeException;
use Exception;
use Src\Logger\OutputLogger;

class GitLabAPI extends AbstractRouterBoard
{

    /** @var Client $client */
    private $client;
    private $idProject;
    private $idGroup;

    /**
     * GitLabAPI constructor.
     * @param array $config
     * @param OutputLogger $logger
     */
    public function __construct(array $config, OutputLogger $logger)
    {
        parent::__construct($config, $logger);
        $this->client = Client::create($this->config['gitlab']['url'])
            ->authenticate($this->config['gitlab']['token'], $this->config['gitlab']['auth-method']);
        $this->idProject = false;
        $this->idGroup = false;
    }

    /**
     * Project ID in GitLab
     * @return integer
     */
    public function getProjectID()
    {
        return $this->idProject;
    }

    /**
     * Group ID in GitLab
     * @return integer
     */
    public function getGroupID()
    {
        return $this->idGroup;
    }

    /**
     * get GitLab Client object
     * @return object \Gitlab\Client
     */
    protected function getGitLabClient()
    {
        return $this->client;
    }


    /**
     * Check if project with name ['gitlab']['project-name'] does exist in repository.
     * @return bool
     * @throws Exception
     */
    public function checkProjectName()
    {
        $this->checkUserName($this->config['gitlab']['username']);
        $project = new Projects($this->client);
        $arrProjects = $project->all([
            'search' => $this->config['gitlab']['project-name']
        ]);
        //old $this->config['gitlab']['project-name'], 1, 1000, 'path');
        if ($this->idGroup) {
            return $this->arraySearchValues(
                $this->config['gitlab']['group-name'] . "/" . $this->config['gitlab']['project-name'],
                $arrProjects,
                'path_with_namespace',
                'id',
                $this->idProject
            );
        }
        return $this->arraySearchValues(
            $this->config['gitlab']['username'] . "/" . $this->config['gitlab']['project-name'],
            $arrProjects,
            'path_with_namespace',
            'id',
            $this->idProject
        );
    }

    /**
     * Check if group exist
     */
    public function checkGroupName()
    {
        $groups = new Groups($this->client);
        echo "Group\n";
        print_r( $this->arraySearchValues(
            $this->config['gitlab']['group-name'],
            $groups->all(),
            'path',
            'id',
            $this->idGroup
        ));
        exit;
    }

    /**
     * Create new project from ['gitlab']['project-name']
     * @return boolean
     * @throws Exception
     */
    public function createProject()
    {
        $project = new Project();
        if ($this->idGroup) {
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
    public function createGroup()
    {
        $group = new Groups($this->client);
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
     */
    public function sendFile($filePath, $content, $branch, $message)
    {
        $project = new Project($this->getProjectID(), $this->client);
        // try if file exist
        try {
            $project->getFile('master', $filePath);
            $project->updateFile($filePath, $content, $branch, $message);
        } catch (RuntimeException $runtimeException) {
            // $runtimeException->getMessage() = '404 File Not Found'
            $project->createFile($filePath, $content, $branch, $message);
        }
    }

    /**
     * Find specific value in array and save value to &$destination var
     * @param string $name - find what
     * @param array $array - data
     * @param string $value - find in key
     * @param string $get - get another value from same level
     * @param string $destination reference to the existing variable $destination
     * @return boolean
     */
    private function arraySearchValues($name, $array, $value, $get, &$destination)
    {
        if ($this->config['gitlab']['debug'] == 1) {
            $this->logger->log("Array " . $name, $this->logger->setDebug());
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

    /**
     * Check if username is email
     * @param string $userName
     * @return bool true if not, else
     * @throws Exception
     */
    private function checkUserName($userName)
    {
        if (!filter_var($userName, FILTER_VALIDATE_EMAIL) === false) {
            throw new Exception("Email in gitlab/username is not allowed! Set your real username.");
        }
        return true;
    }

}
