<?php
include('libraries/Apiresponse.php');
include('libraries/Freshdesk.php');
include('libraries/Salesforce.php');
include('libraries/SimpleObject.php');

class FD2SF {
    private $logsPath;

	public function __construct() {
		$this->logsPath = $_SERVER['DOCUMENT_ROOT'].'/sf2fd/logs/';
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
		$data .= print_r($_POST, true)."\n";
		$this->writeLog($data);
	}
	
    public function writeLog($var) {
	    if (is_array($var) || is_object($var)) {
	        $var = print_r($var, true);
	    }
	    file_put_contents($this->logsPath.'requests_'.date('dmY').'.log', $var."\n", FILE_APPEND);
	}
	
    public function sendCase() {
        $url = 'https://teleport-developer-edition.eu11.force.com/services/apexrest/cases?';
        
        $ticketPost = $this->getOption('freshdesk_webhook', $_POST);
        if (is_array($ticketPost)) {
            $ticket = new SimpleObject();
            $ticket->Case_Subject = strip_tags($this->getOption('ticket_subject', $ticketPost, ''));
            $ticket->Contact_Name = strip_tags($this->getOption('ticket_requester_name', $ticketPost, ''));
            $ticket->Case_ContactEmail = strip_tags($this->getOption('ticket_requester_email', $ticketPost, ''));
            $ticket->Case_Origin = strip_tags($this->getOption('ticket_source', $ticketPost, ''));
            $ticket->Case_Description = strip_tags($this->getOption('ticket_description', $ticketPost, ''));
            $ticket->Case_Priority = strip_tags($this->getOption('priority_name', $ticketPost, ''));
            $ticket->CaseComment_CommentBody = '';
            
            // Extend description
            $requesterName = $this->getOption('requester_name', $ticketPost, '');
            $ticketType = $this->getOption('ticket_type', $ticketPost, '');
            if (!empty($requesterName)) {
                $ticket->CaseComment_CommentBody .= 'Requester name: '.$requesterName."\n";
            }
            if (!empty($ticketType)) {
                $ticket->CaseComment_CommentBody .= 'Ticket type: '.$ticketType."\n";
            }
            
            $data = [
                'class' => 'Case',
            	'Account_Name' => 'Microsoft',
            	'Case_AccountId' => '0010Y00000BAtaIQAT',
            	'Case_ContactEmail' => $ticket->Case_ContactEmail,
            	'Case_ContactId' => '0030Y000008gSHQQA2',
            	'Case_Description' => $ticket->Case_Description,
            	'Case_Origin' => $ticket->Case_Origin,
            	'Case_OwnerId' => '0050Y000000ijRXQAY',
            	'Case_Priority' => $ticket->Case_Priority,
            	'Case_Status' => 'Working',
            	'Case_Subject' => $ticket->Case_Subject,
            	'CaseComment_CommentBody' => $ticket->CaseComment_CommentBody,
            	'CaseComment_CreatedBy_Name' => $ticket->Contact_Name,
            	'CaseComment_CreatedBy_UserName' => $ticket->Case_ContactEmail,
            	'CaseComment_CreatedById' => '0050Y000000ijRXQAY',
            	'Contact_Name' => $ticket->Contact_Name,
            ];
            
            $url .= http_build_query($data);
            
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:45.0) Gecko/20100101 Firefox/20.0');
            curl_setopt( $curl, CURLOPT_HEADER, $headers);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            $websiteResponse = curl_exec($ch);
            curl_close($ch);
            return $websiteResponse;
        }
    }
	
	public function process() {
	    $response = $this->sendCase();
	    $this->writeLog('response from salesforce: '.$response);
	}
}

$misha = new FD2SF();
$misha->logRequest();
$misha->process();