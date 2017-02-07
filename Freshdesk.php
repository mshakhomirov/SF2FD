<?php

error_reporting(-1);

ini_set('error_reporting', E_ALL);



class Freshdesk {

	private $case;

	private $contact;

	private $logsPath;

	private $domain;

	

	public function __construct() {

		$this->api_key = '5xer7BauHOjFUEg07GH';

		$this->password = '123456789tpu';

		$this->domain = 'teleportsystems';

		

		$this->logsPath = $_SERVER['DOCUMENT_ROOT'].'/sf2fd/logs/';



		$this->ticketsUri = 'https://'.$this->domain.'.freshdesk.com/helpdesk/tickets.json';

		$this->contactsUri = 'https://'.$this->domain.'.freshdesk.com/contacts.json';

	}

	

	public function writeLog($var) {

	    if (is_array($var) || is_object($var)) {

	        $var = print_r($var, true);

	    }

	    file_put_contents($this->logsPath.'requests.log', $var."\n", FILE_APPEND);

	}

	

	public function setCase($case) {

		$this->case = $case;

	}

	

	public function setContact($contact) {

		$this->contact = $contact;

	}

	

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

				'email' => 'mike@homeretail.com',

				'priority' => 1,

				'status' => 2

			],

			'cc_emails' => ['mike@homeretail.com']

		];



		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($this->ticket_data));

		

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$server_output = curl_exec($ch);

		$info = curl_getinfo($ch);

		$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);

		$headers = substr($server_output, 0, $header_size);

		$response = substr($server_output, $header_size);

		file_put_contents($this->logsPath.'requests.log', 'response: '.$response, FILE_APPEND);

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

	

	public function CreateContact() {

		$ch = curl_init($this->contactsUri);

		$header[] = 'Content-type: application/json';

		curl_setopt($ch, CURLOPT_POST, true);

		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

		curl_setopt($ch, CURLOPT_HEADER, true);

		curl_setopt($ch, CURLOPT_USERPWD, $this->api_key.':'.$this->password);



		

		

		$this->userData = [

			'user' => [

				'name' => $this->contact->Contact_Name,

				'email' => $this->contact->Contact_Email,

				'description' => $this->contact->Contact_Description,

				'phone' => $this->contact->Contact_Phone,

				'mobile' => $this->contact->Contact_MobilePhone,

				'external_id' => $this->contact->Contact_Id,

				'custom_field' => [

				    'Account_Name' => $this->contact->Account_Name,

				    'Contact_AccountId' => $this->contact->Contact_AccountId,

				    'Contact_Fax' => $this->contact->Contact_Fax,

				    'Contact_OwnerId' => $this->contact->Contact_OwnerId,

				]

			]

		];



		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($this->userData));

		

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$server_output = curl_exec($ch);

		$info = curl_getinfo($ch);

		$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);

		$headers = substr($server_output, 0, $header_size);

		$response = substr($server_output, $header_size);

		

		file_put_contents($this->logsPath.'requests.log', 'response: '.$response, FILE_APPEND);

		

		if ($info['http_code'] == 201) {

            echo 'Ticket created successfully, the response is given below \n';

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



    public function sameContactExists($contact) {

        $sameExists = false;

        echo 'sameContactExists()<br><br>';

        $ch = curl_init('https://'.$this->domain.'.freshdesk.com/contacts.json?query='.urlencode('email is '.$contact->Contact_Email));

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

		

		file_put_contents($this->logsPath.'requests.log', 'response: '.$response, FILE_APPEND);

		

		if ($info['http_code'] == 200) {

		  echo 'User list okay ept <br><br>';

		  echo 'Response Headers are<br>';

		  echo $headers.'<br><br>';

		  echo 'Response Body<br>';

		  //echo $response.'<br><br>';

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

     * Существует ли тикет с таким же заголовком, созданный в течение последнего часа (например)

     */

    public function sameTicketExists($case) {

        $sameExists = false;

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

		

		file_put_contents($this->logsPath.'requests.log', 'response: '.$response, FILE_APPEND);

		

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

		              $expirationTime = 1 * 60 * 60;

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