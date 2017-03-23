<?php

class Salesforce {
	private $logsPath;
	
	public function __construct() {
		$this->logsPath = $_SERVER['DOCUMENT_ROOT'].'/sf2fd/logs/';
	}
	
	public function getOption($key, $array, $defaultValue = false) {
		if (strlen($key) <= 0 || !is_array($array) || !isset($array[$key])) {
			return $defaultValue;
		}
		return $array[$key];
	}

	public function writeLog($var) {
	    if (is_array($var) || is_object($var)) {
	        $var = print_r($var, true);
	    }
	    file_put_contents($this->logsPath.'requests_'.date('dmY').'.log', $var."\n", FILE_APPEND);
	}

	/**
	 * Processes request from salesforce
	 * @return object $case
	 */
	public function getCase() {
	    $this->writeLog('getCase()');
		
		$case = new SimpleObject();
		
		// Main fields
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
		$case->Case_Type = $this->getOption('Case_Type', $_POST, '');
		//$case->Case_Type = '22000242646';
		$case->CaseComment_CommentBody = $this->getOption('CaseComment_CommentBody', $_POST, '');
		$case->CaseComment_CreatedBy_UserName = $this->getOption('CaseComment_CreatedBy_UserName', $_POST, '');
		$case->CaseComment_CreatedById = $this->getOption('CaseComment_CreatedById', $_POST, '');
		$case->Contact_Name = $this->getOption('Contact_Name', $_POST, '');
		
		// Custom fields
		$case->Case_Business_case__c = $this->getOption('Case_Business_case__c', $_POST, '');
		$case->Case_Classification__c = $this->getOption('Case_Classification__c', $_POST, '');
		$case->Case_Classification_of_fault__c = $this->getOption('Case_Classification_of_fault__c', $_POST, '');
		$case->Case_Client_Region__c = $this->getOption('Case_Client_Region__c', $_POST, '');
		$case->Case_Date_needed_by__c = $this->getOption('Case_Date_needed_by__c', $_POST, '');
		$case->Case_Delimiter__c = $this->getOption('Case_Delimiter__c', $_POST, '');
		$case->Case_Distribution_type__c  = $this->getOption('Case_Distribution_type__c', $_POST, '');
        $case->Case_Email_Message__c  = $this->getOption('Case_Email_Message__c', $_POST, '');
		$case->Case_Email_Recipients__c  = $this->getOption('Case_Email_Recipients__c', $_POST, '');
		$case->Case_Email_Subject__c  = $this->getOption('Case_Email_Subject__c', $_POST, '');
		$case->Case_End_date__c  = $this->getOption('Case_End_date__c', $_POST, '');
		$case->Case_EngineeringReqNumber__c  = $this->getOption('Case_EngineeringReqNumber__c', $_POST, '');
		$case->Case_Example__c  = $this->getOption('Case_Example__c', $_POST, '');
		$case->Case_Extension__c  = $this->getOption('Case_Extension__c', $_POST, '');
        $case->Case_File_Name__c  = $this->getOption('Case_File_Name__c', $_POST, '');
		$case->Case_Format__c  = $this->getOption('Case_Format__c', $_POST, '');
        $case->Case_Hardware__c  = $this->getOption('Case_Hardware__c', $_POST, '');
		$case->Case_Header_row__c  = $this->getOption('Case_Header_row__c', $_POST, '');
		$case->Case_Host__c  = $this->getOption('Case_Host__c', $_POST, '');
		$case->Case_Impact__c  = $this->getOption('Case_Impact__c', $_POST, '');
        $case->Case_Justification__c  = $this->getOption('Case_Justification__c', $_POST, '');
        $case->Case_Location__c  = $this->getOption('Case_Location__c', $_POST, '');
        $case->Case_Name_of_the_report_in_SSRS__c  = $this->getOption('Case_Name_of_the_report_in_SSRS__c', $_POST, '');
        $case->Case_Password__c = $this->getOption('Case_Password__c', $_POST, '');
        $case->Case_PotentialLiability__c = $this->getOption('Case_PotentialLiability__c', $_POST, '');
        $case->Case_Previous_request__c = $this->getOption('Case_Previous_request__c', $_POST, '');
        $case->Case_Product__c = $this->getOption('Case_Product__c', $_POST, '');
        $case->Case_Raised_on_Behalf__c = $this->getOption('Case_Raised_on_Behalf__c', $_POST, '');
        $case->Case_Reccurence__c = $this->getOption('Case_Reccurence__c', $_POST, '');
        $case->Case_Region__c = $this->getOption('Case_Region__c', $_POST, '');
        $case->Case_Re_open_case__c = $this->getOption('Case_Re_open_case__c', $_POST, '');
        $case->Case_Report_s_Requested__c = $this->getOption('Case_Report_s_Requested__c', $_POST, '');
        $case->Case_Reporting_currency__c = $this->getOption('Case_Reporting_currency__c', $_POST, '');
        $case->Case_Report_reference__c = $this->getOption('Case_Report_reference__c', $_POST, '');
        $case->Case_Report_Type__c = $this->getOption('Case_Report_Type__c', $_POST, '');
        $case->Case_Request_type__c = $this->getOption('Case_Request_type__c', $_POST, '');
        $case->Case_Rules__c = $this->getOption('Case_Rules__c', $_POST, '');
        $case->Case_Scale__c = $this->getOption('Case_Scale__c', $_POST, '');
        $case->Case_Schedule__c = $this->getOption('Case_Schedule__c', $_POST, '');
        $case->Case_Schedule_details__c = $this->getOption('Case_Schedule_details__c', $_POST, '');
        $case->Case_SLAViolation__c = $this->getOption('Case_SLAViolation__c', $_POST, '');
        $case->Case_Start_date__c = $this->getOption('Case_Start_date__c', $_POST, '');
        $case->Case_Tags__c = $this->getOption('Case_Tags__c', $_POST, '');
        $case->Case_Template_ID__c = $this->getOption('Case_Template_ID__c', $_POST, '');
        $case->Case_Time__c = $this->getOption('Case_Time__c', $_POST, '');
        $case->Case_Timezone__c = $this->getOption('Case_Timezone__c', $_POST, '');
        $case->Case_Type_design__c = $this->getOption('Case_Type_design__c', $_POST, '');
        $case->Case_Type_of_the_report__c = $this->getOption('Case_Type_of_the_report__c', $_POST, '');
        $case->Case_Urgency__c = $this->getOption('Case_Urgency__c', $_POST, '');
        $case->Case_Username__c = $this->getOption('Case_Username__c', $_POST, '');
        $case->Case_Versions__c = $this->getOption('Case_Versions__c', $_POST, '');

		return $case;
	}
	
	/**
	 * Processes request from salesforce
	 * @return object $company
	 */
	public function getCompany() {
	    $this->writeLog('getCompany()');
		
		$company = new SimpleObject();
		
		// Main fields
		$company->Account_Id = $this->getOption('Account_Id', $_POST, '');
		$company->Account_OwnerId = $this->getOption('Account_OwnerId', $_POST, '');
		$company->Account_Description = $this->getOption('Account_Description', $_POST, '');
		$company->Account_Fax = $this->getOption('Account_Fax', $_POST, '');
		$company->Account_Name = $this->getOption('Account_Name', $_POST, '');
		$company->Account_Phone = $this->getOption('Account_Phone', $_POST, '');
		$company->Account_Site = $this->getOption('Account_Site', $_POST, '');
		$company->Account_Website = $this->getOption('Account_Website', $_POST, '');
		
		return $company;
	}
	
	/**
	 * Processes request from salesforce
	 * @return object $contact
	 */
	public function getContact() {
	    $this->writeLog('getContact()');
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
}