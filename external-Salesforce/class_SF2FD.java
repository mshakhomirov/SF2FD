public class SF2FD {

    public static String middlewareUri;

    public SF2FD() {
        
    }

    @future (callout=true)
    public static void SendAccount(Id accountId) {
        System.debug('SF2FD.SendAccout(accountId)');
        
        System.PageReference pageReference = new System.PageReference('');
        Map<String,String> requestData = new Map<String,String>{};

        List<Account> accounts;
        String requestQuery;

        // Created Company
        accounts = [
            SELECT 
                Id, OwnerId, Description, Fax, Name, Phone, Site, Website
            FROM 
                Account
            WHERE 
                Account.Id = :accountId];
        System.debug('Accounts: ' + accounts);
        

        for (Account accountRecord : accounts) {
            // Main information
            requestData.put('class', 'Company');
            requestData.put('Account.Id', accountRecord.Id);
            //requestData.put('Account.CaseNumber', accountRecord.CaseNumber);
            //requestData.put('Account.ContactId', accountRecord.ContactId);
            //requestData.put('Account.ContactEmail', accountRecord.ContactEmail);
            //requestData.put('Account.ContactPhone', accountRecord.ContactPhone);
            //requestData.put('Account.Id', accountRecord.Id);
            requestData.put('Account.OwnerId', accountRecord.OwnerId);
            requestData.put('Account.Description', accountRecord.Description);
            requestData.put('Account.Fax', accountRecord.Fax);
            requestData.put('Account.Name', accountRecord.Name);
            requestData.put('Account.Phone', accountRecord.Phone);
            requestData.put('Account.Site', accountRecord.Site); // Not a website
            requestData.put('Account.Website', accountRecord.Website);
        
            pageReference.getParameters().putAll(requestData);

            requestQuery = pageReference.getUrl();
            if (requestQuery.length() > 0) {
                requestQuery = requestQuery.substring(1, requestQuery.length());
            }
            requestQuery = requestQuery.escapeUnicode();
            System.debug('Query : ' + requestQuery);
        }
    
        HttpRequest req = new HttpRequest();
        req.setEndpoint('http://teleportsystems.co.uk/sf2fd/sf2fd.php');
        req.setMethod('POST');
        req.setBody(requestQuery);
        Http http = new Http();
        HTTPResponse response = http.send(req);
        
        
        try {
            System.debug('Response body from middleware:');
            System.debug(response.getBody());
        } catch(System.CalloutException e) {
            //System.debug('Callout error: '+ e);
            //System.debug(response.toString());
            System.debug('BBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBB');
        }
    }

    @future (callout=true)
    public static void SendContact(Id contactId) {
        System.debug('SF2FD.SendContact(contactId)');
        
        System.PageReference pageReference = new System.PageReference('');
        Map<String,String> requestData = new Map<String,String>{};
        List<Account> accounts;
        List<Contact> owners;
        String requestQuery;
        
        // Created contact
        List<Contact> contacts = [
            SELECT 
                AccountId, Description, Email, Fax, FirstName, HomePhone, Id, LastName, MobilePhone, Name, OwnerId, Phone
            FROM Contact 
            WHERE Contact.Id = :contactId];
        System.debug('Contacts: ' + contacts);
        
        for (Contact contactRecord : contacts) {
            // Main info
            requestData.put('class', 'Contact');
            requestData.put('Contact.AccountId', contactRecord.AccountId);
            requestData.put('Contact.Description', contactRecord.Description);
            requestData.put('Contact.Email', contactRecord.Email);
            requestData.put('Contact.Fax', contactRecord.Fax);
            requestData.put('Contact.FirstName', contactRecord.FirstName);
            requestData.put('Contact.HomePhone', contactRecord.HomePhone);
            requestData.put('Contact.LastName', contactRecord.LastName);
            requestData.put('Contact.MobilePhone', contactRecord.MobilePhone);
            requestData.put('Contact.Name', contactRecord.Name);
            requestData.put('Contact.OwnerId', contactRecord.OwnerId);
            requestData.put('Contact.Phone', contactRecord.Phone);

            // Look for account (company)
            accounts = [SELECT Id, Name FROM Account WHERE Account.Id = :contactRecord.AccountId];
            System.debug('Accounts: ' + accounts);
            for (Account account : accounts) {
                requestData.put('Account.Name', account.Name);
            }
            
            // Look for owner
            owners = [SELECT Id, Name FROM Contact WHERE Contact.Id = :contactRecord.OwnerId];
            System.debug('Owners: ' + owners);
            for (Contact owner : owners) {
                requestData.put('Account.Name', owner.Name);
            }

            pageReference.getParameters().putAll(requestData);

            requestQuery = pageReference.getUrl();
            if (requestQuery.length() > 0) {
                requestQuery = requestQuery.substring(1, requestQuery.length());
            }
            requestQuery = requestQuery.escapeUnicode();
            System.debug('Query : ' + requestQuery);
        }
    
        HttpRequest req = new HttpRequest();
        req.setEndpoint('http://teleportsystems.co.uk/sf2fd/sf2fd.php');
        req.setMethod('POST');

        req.setBody(requestQuery);
    
        
    
        Http http = new Http();
        HTTPResponse response = http.send(req);
        
        
        try {
            System.debug(response.getBody());
            System.debug('AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA');
        } catch(System.CalloutException e) {
            //System.debug('Callout error: '+ e);
            //System.debug(response.toString());
            System.debug('BBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBB');
        }
    }

    @future (callout=true)
    public static void SendCase(Id caseId) {
        System.debug('SF2FD.SendCase(caseId)');
        
        System.PageReference pageReference = new System.PageReference('');
        Map<String,String> requestData = new Map<String,String>{};
        List<Contact> contacts;
        List<Account> accounts;
        List<Contact> owners;
        List<CaseComment> comments;
        String requestQuery;

        // creted case 
        List<Case> cases = [
            SELECT 
                AccountId, CaseNumber, ContactId, ContactEmail, ContactPhone, Id, OwnerId, 
                Description, Origin, Priority, Status, Subject, Type,
                
                Business_case__c, 
                Classification__c, Classification_of_fault__c, Client_Region__c, 
                Date_needed_by__c, Delimiter__c, Distribution_type__c, 
                Email_Message__c, Email_Recipients__c, Email_Subject__c, End_date__c, EngineeringReqNumber__c, Example__c, Extension__c, 
                File_Name__c, Format__c, 
                Hardware__c, Header_row__c, Host__c, Impact__c, 
                Justification__c, 
                Location__c, 
                Name_of_the_report_in_SSRS__c, 
                Password__c,
                PotentialLiability__c,
                Previous_request__c,
                Product__c,
                Raised_on_Behalf__c,
                Reccurence__c,
                Region__c,
                Re_open_case__c,
                Report_s_Requested__c,
                Reporting_currency__c,
                Report_reference__c,
                Report_Type__c,
                Request_type__c,
                Rules__c,
                Scale__c,
                Schedule__c,
                Schedule_details__c,
                SLAViolation__c,
                Start_date__c,
                Tags__c,
                Template_ID__c,
                Time__c,
                Timezone__c,
                Type_design__c,
                Type_of_the_report__c,
                Urgency__c,
                Username__c,
                Versions__c
                
                
            FROM Case 
            WHERE Case.Id = :caseId];
        System.debug('Cases: ' + cases);
        

        for (Case caseRecord : cases) {
            // Main info
            requestData.put('class', 'Case');
            requestData.put('Case.AccountId', caseRecord.AccountId);
            requestData.put('Case.CaseNumber', caseRecord.CaseNumber);
            requestData.put('Case.ContactId', caseRecord.ContactId);
            requestData.put('Case.ContactEmail', caseRecord.ContactEmail);
            requestData.put('Case.ContactPhone', caseRecord.ContactPhone);
            requestData.put('Case.Id', caseRecord.Id);
            requestData.put('Case.OwnerId', caseRecord.OwnerId);
            requestData.put('Case.Description', caseRecord.Description);
            requestData.put('Case.Origin', caseRecord.Origin);
            requestData.put('Case.Priority', caseRecord.Priority);
            requestData.put('Case.Status', caseRecord.Status);
            requestData.put('Case.Subject', caseRecord.Subject);
            requestData.put('Case.Type', caseRecord.Type);
            // Custom fields
            requestData.put('Case.Business_case__c', caseRecord.Business_case__c);
            requestData.put('Case.Classification__c', caseRecord.Classification__c);
            requestData.put('Case.Classification_of_fault__c', caseRecord.Classification_of_fault__c);
            requestData.put('Case.Client_Region__c', caseRecord.Client_Region__c);
            requestData.put('Case.Date_needed_by__c', String.ValueofGmt(caseRecord.Date_needed_by__c));
            requestData.put('Case.Delimiter__c', caseRecord.Delimiter__c);
            requestData.put('Case.Distribution_type__c', caseRecord.Distribution_type__c);
            requestData.put('Case.Email_Message__c', caseRecord.Email_Message__c);
            requestData.put('Case.Email_Recipients__c', caseRecord.Email_Recipients__c);
            requestData.put('Case.Email_Subject__c', caseRecord.Email_Subject__c);
            requestData.put('Case.End_date__c', String.ValueofGmt(caseRecord.End_date__c));
            requestData.put('Case.EngineeringReqNumber__c', caseRecord.EngineeringReqNumber__c);
            requestData.put('Case.Example__c', caseRecord.Example__c);
            requestData.put('Case.Extension__c', caseRecord.Extension__c);
            requestData.put('Case.File_Name__c', caseRecord.File_Name__c);
            requestData.put('Case.Format__c', caseRecord.Format__c);
            requestData.put('Case.Hardware__c', caseRecord.Hardware__c);
            requestData.put('Case.Header_row__c', caseRecord.Header_row__c);
            requestData.put('Case.Host__c', caseRecord.Host__c);
            requestData.put('Case.Impact__c', caseRecord.Impact__c);
            requestData.put('Case.Justification__c', caseRecord.Justification__c);
            requestData.put('Case.Location__c', caseRecord.Location__c);
            requestData.put('Case.Name_of_the_report_in_SSRS__c', caseRecord.Name_of_the_report_in_SSRS__c);
            requestData.put('Case.Password__c', caseRecord.Password__c);
            requestData.put('Case.PotentialLiability__c', caseRecord.PotentialLiability__c);
            requestData.put('Case.Previous_request__c', caseRecord.Previous_request__c);
            requestData.put('Case.Product__c', caseRecord.Product__c);
            requestData.put('Case.Raised_on_Behalf__c', caseRecord.Raised_on_Behalf__c);
            requestData.put('Case.Reccurence__c', caseRecord.Reccurence__c);
            requestData.put('Case.Region__c', caseRecord.Region__c);
            requestData.put('Case.Re_open_case__c', caseRecord.Re_open_case__c);
            requestData.put('Case.Report_s_Requested__c', caseRecord.Report_s_Requested__c);
            requestData.put('Case.Reporting_currency__c', caseRecord.Reporting_currency__c);
            requestData.put('Case.Report_reference__c', caseRecord.Report_reference__c);
            requestData.put('Case.Report_Type__c', caseRecord.Report_Type__c);
            requestData.put('Case.Request_type__c', caseRecord.Request_type__c);
            requestData.put('Case.Rules__c', caseRecord.Rules__c);
            requestData.put('Case.Scale__c', caseRecord.Scale__c);
            requestData.put('Case.Schedule__c', caseRecord.Schedule__c);
            requestData.put('Case.Schedule_details__c', caseRecord.Schedule_details__c);
            requestData.put('Case.SLAViolation__c', caseRecord.SLAViolation__c);
            requestData.put('Case.Start_date__c', String.ValueofGmt(caseRecord.Start_date__c));
            requestData.put('Case.Tags__c', caseRecord.Tags__c);
            requestData.put('Case.Template_ID__c', caseRecord.Template_ID__c);
            requestData.put('Case.Time__c', caseRecord.Time__c);
            requestData.put('Case.Timezone__c', caseRecord.Timezone__c);
            requestData.put('Case.Type_design__c', caseRecord.Type_design__c);
            requestData.put('Case.Type_of_the_report__c', caseRecord.Type_of_the_report__c);
            requestData.put('Case.Urgency__c', caseRecord.Urgency__c);
            requestData.put('Case.Username__c', caseRecord.Username__c);
            requestData.put('Case.Versions__c', caseRecord.Versions__c);
        
            // look for contact
            contacts = [SELECT Id, Name FROM Contact WHERE Contact.Id = :caseRecord.ContactId];
            System.debug('Contacts: ' + contacts);
            for (Contact contact : contacts) {
                requestData.put('Contact.Name', contact.Name);
            }
            
            // look for account
            accounts = [SELECT Id, Name FROM Account WHERE Account.Id = :caseRecord.AccountId];
            System.debug('Accounts: ' + accounts);
            for (Account account : accounts) {
                requestData.put('Account.Name', account.Name);
            }
            
            // look for owner 
            owners = [SELECT Id, Name FROM Contact WHERE Contact.Id = :caseRecord.OwnerId];
            System.debug('Owners: ' + owners);
            for (Contact owner : owners) {
                requestData.put('Account.Name', owner.Name);
            }
            
            // Comments
            // In this case only one comment goes here
            comments = [SELECT Id, CreatedById, CreatedBy.UserName, CreatedBy.Name, CommentBody FROM CaseComment WHERE CaseComment.ParentId = :caseRecord.Id];
            System.debug('CaseComments: ' + comments);
            for (CaseComment comment : comments) {
                requestData.put('CaseComment.CreatedById', comment.CreatedById);
                requestData.put('CaseComment.CreatedBy.UserName', comment.CreatedBy.UserName);
                requestData.put('CaseComment.CreatedBy.Name', comment.CreatedBy.Name);
                requestData.put('CaseComment.CommentBody', comment.CommentBody);
            }

            pageReference.getParameters().putAll(requestData);

            requestQuery = pageReference.getUrl();
            if (requestQuery.length() > 0) {
                requestQuery = requestQuery.substring(1, requestQuery.length());
            }
            requestQuery = requestQuery.escapeUnicode();
            System.debug('Query : ' + requestQuery);
        }
    
        HttpRequest req = new HttpRequest();
        req.setEndpoint('http://teleportsystems.co.uk/sf2fd/sf2fd.php');
        req.setMethod('POST');

        req.setBody(requestQuery);

        Http http = new Http();
        HTTPResponse response = http.send(req);
        
        
        try {
            System.debug(response.getBody());
            System.debug('AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA');
        } catch(System.CalloutException e) {
            //System.debug('Callout error: '+ e);
            //System.debug(response.toString());
            System.debug('BBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBB');
        }
    }
}