<?php

require_once "phing/Task.php";
include(dirname(__FILE__).'/../../common/user_config.php');
include(dirname(__FILE__).'/../../common/gforgeconnector.php');

class GForgeDeployTask extends Task {
	private $connector = null;
	private $username = '';
	private $password = '';
	private $site = '';
	private $frs_release_id = 0;

    /**
     * The filename passed in the buildfile.
     */
    private $filename = null;

    /**
     * The setter for the attribute "message"
     */
    public function setFilename($str) {
        $this->filename = $str;
    }
    
    public function setUsername($str) {
    	$this->username = $str;	
    }
    
    public function setPassword($str) {
    	$this->password = $str;
    }
    
    public function setSite($str) {
    	$this->site = $str;
    }
    
    public function setFRSReleaseId($str) {
    	$this->frs_release_id = $str;	
    }

    /**
     * The init method: Do init steps.
     */
    public function init() {
		// should shift stuff here...
    }

    /**
     * The main entry point method.
     */
    public function main() {
    	$this->connector = new GForgeConnector($this->site);
		if(!$this->connector) {
			print("Failed to connect to server");
			return false;
		}
		if(!$this->connector->login($this->username, $this->password)) {
			print("Login failed");
			return false;	
		}
		print("Deploying ". $this->filename."\n");
    	return $this->connector->addFilesystem('frsrelease', $this->frs_release_id, basename($this->filename), '', base64_encode(file_get_contents($this->filename)));
    }
}
