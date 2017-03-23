<?php
error_reporting(-1);
ini_set('error_reporting', E_ALL);

include('libraries/Apiresponse.php');
include('libraries/Freshdesk.php');
include('libraries/SimpleObject.php');
include('libraries/Salesforce.php');

/**
 *
 */
class SF2FD {
	private $logsPath;
	private $response;
	
	public function __construct() {
		$this->logsPath = $_SERVER['DOCUMENT_ROOT'].'/sf2fd/logs/';
		$this->response = new ApiResponse();
		$this->freshdesk = new Freshdesk();
		$this->salesforce = new Salesforce();
		$this->writeLog('Start at'.date('d.m.Y H:i:s'));
	}
	
	public function __destruct() {
	    $this->writeLog('Finish');
	    $this->writeLog('');
	}
	
	public function getOption($key, $array, $defaultValue = false) {
		if (strlen($key) <= 0 || !is_array($array) || !isset($array[$key])) {
			return $defaultValue;
		}
		return $array[$key];
	}
	
	public function logRequest() {
	    $this->writeLog('logRequest()');
		$data = date('d.m.Y H:i:s', time())."\n";
		$data .= print_r($_POST, true)."\n";
		$this->writeLog($data);
	}
	
	public function writeLog($var) {
	    if (is_array($var) || is_object($var)) {
	        $var = print_r($var, true);
	    }
	    file_put_contents($this->logsPath.'requests_'.date('dmY').'.log', $var."\n", FILE_APPEND);
	}
	
	public function process() {
	    $this->writeLog('process()');
	    
		$class = $this->getOption('class', $_POST, '');
		switch ($class) {
			case 'Case':
				$this->writeLog('Class: '.$class);
				$case = $this->salesforce->getCase();
				$this->writeLog($case);
				if ($this->freshdesk->sameTicketExists($case)) {
				    $this->writeLog('Ticket already exists!');
				} else {
				    $this->writeLog('Need to create new ticket!');
				    $this->freshdesk->setCase($case);
				    $this->freshdesk->CreateCase();
				}
				break;
			case 'Company':
				$this->writeLog('Class: '.$class);
				$company = $this->salesforce->getCompany();
				$this->writeLog($company);
				if ($this->freshdesk->sameCompanyExists($company)) {
				    $this->writeLog('Company already exists!');
				} else {
				    $this->writeLog('Need to create new company');
				    $this->freshdesk->setCompany($company);
				    $this->freshdesk->CreateCompany();
				}
				break;
			case 'Contact':
				$this->writeLog('Class: '.$class);
				$contact = $this->salesforce->getContact();
				$this->writeLog($contact);
				if ($this->freshdesk->sameContactExists($contact)) {
				    $this->writeLog('Same contact exists!');
				} else {
				    $this->writeLog('Need to create new contact');
				    $this->freshdesk->setContact($contact);
				    $this->freshdesk->CreateContact();
				}
				break;
			default:
				$this->writeLog('Undefined class: '.$class);
				break;
		}
	}
	
	public function printResponse() {
	    $this->writeLog('printResponse()');
		return $this->response->getJSON();
	}
}

$misha = new SF2FD();
$misha->logRequest();
$misha->process();
