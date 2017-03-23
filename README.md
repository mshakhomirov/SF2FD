# SF2SD
SalesForce - Freshdesk discussion with John.


-To push custom fields from Salesforce to Freshdesk.
Project files:

- external/salesforce/ — APEX classes and triggers code to place it to Salesforce - Develop.
- libraries/Apiresponse.php — class-"helper", helps to process JSON response from the server. 
- libraries/Freshdesk.php — Class for Freshdesk API: to create ticket, create company (account), create contact, etc.
- libraries/Salesforce.php — Class to process the data pulled from Salesforce.
- libraries/SimpleObject.php — Siple object. Works as a replacement for standard Class in PHP StdClass. If attributes don't exit, returns NULL (not an error).
- fd2sf.php — takes a ticket from Freshdesk and sends it to Salesforce. 
- sf2fd.php — main script. Pulls data from Salesforce and sends it to Freshdesk. All the classes from above are connected here.
