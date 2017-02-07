<?php
error_reporting(-1);
ini_set('error_reporting', E_ALL);


include('Apiresponse.php');
include('Freshdesk.php');
include('SimpleObject.php');

class SF2FD {
	private $logsPath;
	private $response;

	public function __construct() {
		$this->logsPath = $_SERVER['DOCUMENT_ROOT'].'/sf2fd/logs/';
		$this->response = new ApiResponse();
		$this->freshdesk = new Freshdesk();
		$this->writeLog('Start at'.date('d.m.Y H:i:s'));
	}

	public function __destruct() {
	    $this->writeLog('');
	}

	public function getOption($key, $array, $defaultValue = false) {
		if (strlen($key) <= 0 || !is_array($array) || !isset($array[$key])) {
			return $defaultValue;
		}
		return $array[$key];
	}

	public function logRequest() {
		$data = date('d.m.Y H:i:s', time())."\n";
		//$data .= print_r($_GET, true)."\n";
		$data .= print_r($_POST, true)."\n";
		//$data .= print_r($_SERVER, true)."\n";
		//$data .= "\n";
		$this->writeLog($data);
		//file_put_contents($this->casesPath.'cases.log', $data, FILE_APPEND);
	}

	public function writeLog($var) {
	    if (is_array($var) || is_object($var)) {
	        $var = print_r($var, true);
	    }
	    file_put_contents($this->logsPath.'requests.log', $var."\n", FILE_APPEND);
	}

	private function getCase() {
		$case = new SimpleObject();
		$case->Account_Name = $this->getOption('Account_Name', $_POST, '');
		$case->Case_AccountId = $this->getOption('Case_AccountId', $_POST, '');
		$case->Case_CaseNumber = $this->getOption('Case_CaseNumber', $_POST, '');
		$case->Case_ContactEmail = $this->getOption('Case_ContactEmail', $_POST, '');
		$case->Case_ContactId = $this->getOption('Case_ContactId', $_POST, '');
		$case->Case_Description = $this->getOption('Case_Description', $_POST, '');
		$case->Case_Id = $this->getOption('Case_Id', $_POST, '');
		$case->Case_Origin = $this->getOption('Case_Origin', $_POST, '');
		$case->Case_OwnerId = $this->getOption('Case_OwnerId', $_POST, '');
		$case->Case_Priority = $this->getOption('Case_Priority', $_POST, '');
		$case->Case_Status = $this->getOption('Case_Status', $_POST, '');
		$case->Case_Subject = $this->getOption('Case_Subject', $_POST, '');
		$case->Case_Origin = $this->getOption('Case_Origin', $_POST, '');
		$case->CaseComment_CommentBody = $this->getOption('CaseComment_CommentBody', $_POST, '');
		$case->CaseComment_CreatedBy_UserName = $this->getOption('CaseComment_CreatedBy_UserName', $_POST, '');
		$case->CaseComment_CreatedById = $this->getOption('CaseComment_CreatedById', $_POST, '');
		$case->Contact_Name = $this->getOption('Contact_Name', $_POST, '');
		return $case;
	}

	private function getContact() {
		$contact = new SimpleObject();
		$contact->Account_Name = $this->getOption('Account_Name', $_POST, '');
		$contact->Contact_AccountId = $this->getOption('Contact_AccountId', $_POST, '');
		$contact->Contact_Description = $this->getOption('Contact_Description', $_POST, '');
		$contact->Contact_Email = $this->getOption('Contact_Email', $_POST, '');
		$contact->Contact_Fax = $this->getOption('Contact_Fax', $_POST, '');
		$contact->Contact_FirstName = $this->getOption('Contact_FirstName', $_POST, '');
		$contact->Contact_HomePhone = $this->getOption('Contact_HomePhone', $_POST, '');
		$contact->Contact_LastName = $this->getOption('Contact_LastName', $_POST, '');
		$contact->Contact_MobilePhone = $this->getOption('Contact_MobilePhone', $_POST, '');
		$contact->Contact_Name = $this->getOption('Contact_Name', $_POST, '');
		$contact->Contact_OwnerId = $this->getOption('Contact_OwnerId', $_POST, '');
		$contact->Contact_Phone = $this->getOption('Contact_Phone', $_POST, '');
		return $contact;
	}

	public function process() {
		$this->logRequest();

		$class = $this->getOption('class', $_POST, '');

		switch ($class) {
			case 'Case':
				$this->writeLog('Class: '.$class);
				$case = $this->getCase();
				$this->writeLog($case);
				if ($this->freshdesk->sameTicketExists($case)) {
				    $this->writeLog('same ticket exists!');
				} else {
				    $this->writeLog('New ticket!');
				    $this->freshdesk->setCase($case);
				    $this->freshdesk->CreateCase();
				}
				break;
			case 'Contact':
				$this->writeLog('Class: '.$class);
				$contact = $this->getContact();
				$this->writeLog($contact);
				if ($this->freshdesk->sameContactExists($contact)) {
				    echo 'contact already exists';
				    $this->writeLog('Same contact exists!');
				} else {
				    echo 'Need to create new contact';
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
		return $this->response->getJSON();
	}


}

$misha = new SF2FD();
//$misha->logRequest();
$misha->process();
//echo '<pre>'.$misha->printResponse().'</pre>';