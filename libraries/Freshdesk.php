<?php
/**
 * Receives requests from freshdesk and send them to salesforce
 */
class Freshdesk {
	private $case;
	private $company;
	private $contact;
	private $logsPath;
	private $domain;
	
	public function __construct() {
		$this->api_key = '5xer7BauHOjFUEg07GH';
		$this->password = '123456789tpu';
		$this->domain = 'teleportsystems';
		$this->logsPath = $_SERVER['DOCUMENT_ROOT'].'/sf2fd/logs/';
		$this->ticketsUri = 'https://'.$this->domain.'.freshdesk.com/helpdesk/tickets.json';
		$this->companiesUri = 'https://'.$this->domain.'.freshdesk.com/api/v2/companies';
		$this->contactsUri = 'https://'.$this->domain.'.freshdesk.com/api/v2/contacts';
	}
	
	public function writeLog($var) {
	    if (is_array($var) || is_object($var)) {
	        $var = print_r($var, true);
	    }
	    file_put_contents($this->logsPath.'requests_'.date('dmY').'.log', $var."\n", FILE_APPEND);
	}
	
	public function setCase($case) {
		$this->case = $case;
	}
	
	public function setCompany($company) {
		$this->company = $company;
	}
	
	public function setContact($contact) {
		$this->contact = $contact;
	}
	
	public function getCasePriorityByName($priorityName) {
		$priorityName = (string)$priorityName;
		$priorityId = 1;
		
		switch ($priorityName) {
			case 'Low':
				$priorityId = 1;
				break;
			case 'Medium':
				$priorityId = 2;
				break;
			case 'High':
				$priorityId = 3;
				break;
			case 'Urgent':
				$priorityId = 4;
				break;
		}

		return $priorityId;
	}
	
	public function getCaseStatusByName($statusName) {
		$statusName = (string)$statusName;
		$statusId = 2;
		
		switch ($statusName) {
			case 'Open':
				$statusId = 2;
				break;
			case 'Pending':
				$statusId = 3;
				break;
			case 'Resolved':
				$statusId = 4;
				break;
		}

		return $statusId;
	}
	
	public function getCaseSourceByName($sourceName) {
		$sourceName = (string)$sourceName;
		$sourceId = 2;
		
		switch ($sourceName) {
			case 'Phone':
				$sourceId = 3;
				break;
			case 'Email':
				$sourceId = 1;
				break;
			case 'Web':
				$sourceId = 2;
				break;
		}

		return $sourceId;
	}
	
	/**
	 * Creates Ticket in freshdesk
	 */
	public function CreateCase() {
		$ch = curl_init($this->ticketsUri);
		$header[] = 'Content-type: application/json';
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_HEADER, true);
		curl_setopt($ch, CURLOPT_USERPWD, $this->api_key.':'.$this->password);
		$this->ticket_data = [
			'helpdesk_ticket' => [
				'description' => $this->case->Case_Description,
				'subject' => $this->case->Case_Subject,
				'email' => $this->case->Case_ContactEmail,
				'priority' => $this->getCasePriorityByName($this->case->Case_Priority),
				'status' => $this->getCaseStatusByName($this->case->Case_Status),
				'source' => $this->getCaseSourceByName($this->case->Case_Origin),
				'ticket_type' => $this->case->Case_Type,
				'type' => $this->case->Case_Type,
				//'tags' => $this->case->Case_Tags__c,
				
				'custom_field' => [
					'business_case_534181' => $this->case->Case_Business_case__c,
					'classification_534181' => $this->case->Case_Classification__c,
					'classification_of_fault_534181' => $this->case->Case_Classification_of_fault__c,
					'client_region_534181' => $this->case->Case_Client_Region__c,
					'date_needed_by_534181' => $this->case->Case_Date_needed_by__c,
					'delimiter_534181' => $this->case->Case_Delimiter__c,
					'distribution_type_534181' => $this->case->Case_Distribution_type__c,
					'email_message_534181' => $this->case->Case_Email_Message__c,
					'email_recipients_534181' => $this->case->Case_Email_Recipients__c,
					'email_subject_534181' => $this->case->Case_Email_Subject__c,
					'end_date_534181' => $this->case->Case_End_date__c,
					'example_534181' => $this->case->Case_Example__c,
					'extension_534181' => $this->case->Case_Extension__c,
					'file_name_534181' => $this->case->Case_File_Name__c,
					'format_534181' => $this->case->Case_Format__c,
					'hardware_534181' => $this->case->Case_Hardware__c,
					'header_row_534181' => $this->case->Case_Header_row__c,
					'host_534181' => $this->case->Case_Host__c,
					'impact_534181' => $this->case->Case_Impact__c,
					'justification_534181' => $this->case->Case_Justification__c,
					'location_534181' => $this->case->Case_Location__c,
					'name_of_the_report_in_ssrs_534181' => $this->case->Case_Name_of_the_report_in_SSRS__c,
					'password_534181' => $this->case->Case_Password__c,
					'previous_request_534181' => $this->case->Case_Previous_request__c,
					'product_534181' => $this->case->Case_Product__c,
					'raised_on_behalf_534181' => $this->case->Case_Raised_on_Behalf__c,
					'reccurence_534181' => $this->case->Case_Reccurence__c,
					'region_534181' => $this->case->Case_Region__c,
					'reopen_case_534181' => $this->case->Case_Re_open_case__c,
					'reopen_case_reason_534181' => '',
					'report_reference_534181' => $this->case->Case_Report_reference__c,
					'report_type_534181' => $this->case->Case_Report_Type__c,
					'reporting_currency_534181' => $this->case->Case_Reporting_currency__c,
					'reports_requested_534181' => $this->case->Case_Report_s_Requested__c,
					'request_type_534181' => $this->case->Case_Request_type__c,
					'rules_534181' => $this->case->Case_Rules__c,
					'scale_534181' => $this->case->Case_Scale__c,
					'schedule_534181' => $this->case->Case_Schedule__c,
					'schedule_details_534181' => $this->case->Case_Schedule_details__c,
					'start_date_534181' => $this->case->Case_Start_date__c,
					//'tags_534181' => $this->case->Case_Tags__c,
					'template_id_534181' => $this->case->Case_Template_ID__c,
					'time_534181' => $this->case->Case_Time__c,
					'timezone_534181' => $this->case->Case_Timezone__c,
					//'ticket_type_534181' => $this->case->Case_Type,
					'type_534181' => $this->case->Case_Type,
					'type_of_the_report_534181' => $this->case->Case_Type_of_the_report__c,
					'urgency_534181' => $this->case->Case_Urgency__c,
					'username_534181' => $this->case->Case_Username__c,
					'versions_534181' => $this->case->Case_Versions__c,
				]
			],
			'cc_emails' => [$this->case->Case_ContactEmail]
		];
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($this->ticket_data));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$server_output = curl_exec($ch);
		$info = curl_getinfo($ch);
		$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
		$headers = substr($server_output, 0, $header_size);
		$response = substr($server_output, $header_size);
		$this->writeLog('response: '.$response);
		if ($info['http_code'] == 201) {
            echo 'Ticket created successfully, the response is given below \n';
            echo 'Response Headers are \n';
            echo $headers.'\n';
            echo 'Response Body \n';
            echo '$response \n';
		} else {
            if ($info['http_code'] == 404) {
                echo 'Error, Please check the end point \n';
            } else {
                echo 'Error, HTTP Status Code : ' . $info['http_code'] . '\n';
                echo 'Headers are '.$headers;
                echo 'Response are '.$response;
            }
		}
		curl_close($ch);
	}

	/**
	 * Creates Company in Freshdesk
	 */
	public function CreateCompany() {
	    $this->writeLog('CreateCompany()');
	    
		$ch = curl_init($this->companiesUri);
		$header[] = 'Content-type: application/json';
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_HEADER, true);
		curl_setopt($ch, CURLOPT_USERPWD, $this->api_key.':'.$this->password);
		$this->userData = [
			'name' => $this->company->Account_Name,
			'description' => $this->company->Account_Description,
			'custom_fields' => [
			    'phone' => $this->company->Account_Phone,
			    'fax' => $this->company->Account_Fax,
			    'site' => $this->company->Account_Site,
			    'website' => $this->company->Account_Website,
			    //'Contact_AccountId' => $this->company->Contact_AccountId,
			    //'Contact_Fax' => $this->company->Contact_Fax,
			    //'Contact_OwnerId' => $this->company->Contact_OwnerId,
			]
		];
		
		$this->writeLog('Data: '.json_encode($this->userData));
		
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($this->userData));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$server_output = curl_exec($ch);
		$info = curl_getinfo($ch);
		$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
		$headers = substr($server_output, 0, $header_size);
		$response = substr($server_output, $header_size);
		$this->writeLog('$this->userData: '.print_r($this->userData, true));
		$this->writeLog('response: '.$response);
		if ($info['http_code'] == 201) {
            echo 'Company created successfully, the response is given below \n';
            echo 'Response Headers are \n';
            echo $headers.'\n';
            echo 'Response Body \n';
            echo '$response \n';
        } else {
            if($info['http_code'] == 404) {
                echo 'Error, Please check the end point \n';
            } else {
                echo 'Error, HTTP Status Code : ' . $info['http_code'] . '\n';
                echo 'Headers are '.$headers;
                echo 'Response are '.$response;
            }
		}
		curl_close($ch);
	}
	
	/**
	 * Creates Contact in Freshdesk
	 */
	public function CreateContact() {
		$ch = curl_init($this->contactsUri);
		$header[] = 'Content-type: application/json';
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_HEADER, true);
		curl_setopt($ch, CURLOPT_USERPWD, $this->api_key.':'.$this->password);
		$this->userData = [
			'name' => $this->contact->Contact_Name,
			'email' => $this->contact->Contact_Email,
			'description' => $this->contact->Contact_Description,
			'phone' => $this->contact->Contact_Phone,
			'mobile' => $this->contact->Contact_MobilePhone,
			/*'custom_fields' => [
			    //'abc' => '123',
			    //'Account_Name' => $this->contact->Account_Name,
			    //'Contact_Id' => $this->contact->Contact_Id,
			    //'Contact_AccountId' => $this->contact->Contact_AccountId,
			    //'Contact_Fax' => $this->contact->Contact_Fax,
			    //'Contact_OwnerId' => $this->contact->Contact_OwnerId,
			]*/
		];
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($this->userData));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$server_output = curl_exec($ch);
		$info = curl_getinfo($ch);
		$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
		$headers = substr($server_output, 0, $header_size);
		$response = substr($server_output, $header_size);
		$this->writeLog('$this->userData: '.print_r($this->userData, true));
		$this->writeLog('response: '.$response);
		if ($info['http_code'] == 201) {
            echo 'Contact created successfully, the response is given below \n';
            echo 'Response Headers are \n';
            echo $headers.'\n';
            echo 'Response Body \n';
            echo '$response \n';
        } else {
            if($info['http_code'] == 404) {
                echo 'Error, Please check the end point \n';
            } else {
                echo 'Error, HTTP Status Code : ' . $info['http_code'] . '\n';
                echo 'Headers are '.$headers;
                echo 'Response are '.$response;
            }
		}
		curl_close($ch);
	}
	
	/**
	 * Checks if company with same name already exists in Freshdesk
	 * @param object $newCompany
	 * @return boolean
	 */
    public function sameCompanyExists($newCompany) {
        $this->writeLog('sameCompanyExists()');
        
        $sameExists = false;
        $ch = curl_init($this->companiesUri);
		$header[] = 'Content-type: application/json';
		//curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_HEADER, true);
		curl_setopt($ch, CURLOPT_USERPWD, $this->api_key.':'.$this->password);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$server_output = curl_exec($ch);
		$info = curl_getinfo($ch);
		$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
		$headers = substr($server_output, 0, $header_size);
		$response = substr($server_output, $header_size);
		$this->writeLog('response: '.$response);
		if ($info['http_code'] == 200) {
		  echo 'Company list okay ept <br><br>';
		  echo 'Response Headers are<br>';
		  echo $headers.'<br><br>';
		  echo 'Response Body<br>';
		  echo $response.'<br><br>';
		  $companies = json_decode($response);
		  if (is_array($companies)) {
		      echo '<pre>'.print_r($companies, true).'</pre><br>';
		      foreach ($companies as $company) {
		          //if (isset($user->user) && is_object($user->user)) {
		              //$user = $user->user;
    		          //echo '<pre>'.print_r($user, true).'</pre><br>';
    		          if (isset($company->name) && $company->name == $newCompany->Account_Name) {
    		              $sameExists = true;
    		              break;
    		          }
		          //}
		      }
		  } else {
		      $this->writeLog('Companies are not an array');
		  }
		} else {
            if ($info['http_code'] == 404) {
                echo 'Error, Please check the end point <br><br>';
            } else {
                echo 'Error, HTTP Status Code : ' . $info['http_code'] . '<br><br>';
                echo 'Headers are '.$headers.'<br><br>';
                echo 'Response are '.$response.'<br><br>';
            }
		}
		curl_close($ch);
        return $sameExists;
    }
	
	/**
	 * Checks if Contact with same email already exists in Freshdesk
	 * @param object $newCompany
	 * @return boolean
	 */
    public function sameContactExists($contact) {
        $sameExists = false;
        echo 'sameContactExists()<br><br>';
        $ch = curl_init('https://'.$this->domain.'.freshdesk.com/api/v2/contacts');
		$header[] = 'Content-type: application/json';
		//curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_HEADER, true);
		curl_setopt($ch, CURLOPT_USERPWD, $this->api_key.':'.$this->password);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$server_output = curl_exec($ch);
		$info = curl_getinfo($ch);
		$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
		$headers = substr($server_output, 0, $header_size);
		$response = substr($server_output, $header_size);
		$this->writeLog('response: '.$response);
		if ($info['http_code'] == 200) {
		  echo 'User list okay ept <br><br>';
		  echo 'Response Headers are<br>';
		  echo $headers.'<br><br>';
		  echo 'Response Body<br>';
		  echo $response.'<br><br>';
		  $users = json_decode($response);
		  if (is_array($users)) {
		      echo '<pre>'.print_r($users, true).'</pre><br>';
		      foreach ($users as $user) {
		          if (isset($user->user) && is_object($user->user)) {
		              $user = $user->user;
    		          //echo '<pre>'.print_r($user, true).'</pre><br>';
    		          if (isset($user->email) && $user->email == $contact->Contact_Email) {
    		              $sameExists = true;
    		              break;
    		          }
		          }
		      }
		  } else {
		      echo 'users not array<br>';
		  }
		} else {
            if ($info['http_code'] == 404) {
                echo 'Error, Please check the end point <br><br>';
            } else {
                echo 'Error, HTTP Status Code : ' . $info['http_code'] . '<br><br>';
                echo 'Headers are '.$headers.'<br><br>';
                echo 'Response are '.$response.'<br><br>';
            }
		}
		curl_close($ch);
        return $sameExists;
    }
    
	/**
	 * Checks if Ticket with same subject already exists in Freshdesk
	 * @param object $newCompany
	 * @return boolean
	 */
    public function sameTicketExists($case) {
        $sameExists = false;
		$hours = 1;
		
        echo 'sameTicketExists()<br><br>';
        $ch = curl_init('https://'.$this->domain.'.freshdesk.com/helpdesk/tickets/filter/all_tickets?format=json&wf_order=created_at&wf_order_type=desc');
		$header[] = 'Content-type: application/json';
		//curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_HEADER, true);
		curl_setopt($ch, CURLOPT_USERPWD, $this->api_key.':'.$this->password);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$server_output = curl_exec($ch);
		$info = curl_getinfo($ch);
		$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
		$headers = substr($server_output, 0, $header_size);
		$response = substr($server_output, $header_size);
		$this->writeLog('response: '.$response);
		if ($info['http_code'] == 200) {
		  echo 'Ticket list okay ept <br><br>';
		  echo 'Response Headers are \n';
		  echo $headers.'<br><br>';
		  echo 'Response Body<br>';
		  //echo $response.'<br><br>';
		  $tickets = json_decode($response);
		  if (is_array($tickets)) {
		      echo '<pre>'.print_r($tickets, true).'</pre><br>';
		      foreach ($tickets as $ticket) {
		          if ($ticket->subject == $case->Case_Subject) {
		              //$sameExists = true;
		              $createdat = strtotime($ticket->created_at);
		              $expirationTime = $hours * 60 * 60;
		              if (time() < $createdat + $expirationTime) {
		                  $sameExists = true;
		              }
		              break;
		          }
		      }
		  }
		} else {
		  if ($info['http_code'] == 404) {
			echo 'Error, Please check the end point <br><br>';
		  } else {
			echo 'Error, HTTP Status Code : ' . $info['http_code'] . '<br><br>';
			echo 'Headers are '.$headers.'<br><br>';
			echo 'Response are '.$response.'<br><br>';
		  }
		}
		curl_close($ch);
        return $sameExists;
    }
}