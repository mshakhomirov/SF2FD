@RestResource(urlMapping='/cases')

global class Cases {

    public static Boolean sameCaseExists(String caseSubject) {
        Boolean exists = false;
        
        List<Case> cases;
        cases = [select id from Case where Subject =: caseSubject];
        
        exists = cases.size() > 0;
        
        return exists;
    }

    public static String processCase() {
        String output = '';
        
        String caseSubject = '';
        String caseEmail = '';
        String caseDescription = '';
        String caseCommentBody = '';
        
        List<Account> accounts;
        List<Contact> contacts;
        List<User> users;
        Id accId;
        Id contactId;
        Id userId;
        
        caseSubject = RestContext.request.params.get('Case_Subject');
        caseEmail = RestContext.request.params.get('Case_ContactEmail');
        caseDescription = RestContext.request.params.get('Case_Description');
        caseCommentBody = RestContext.request.params.get('CaseComment_CommentBody');
        
        if (sameCaseExists(caseSubject)) {
            output += ' Same case ("'+caseSubject+'") exists already! ';
            return output;
        }
        
        output += ('caseSubject: ' + caseSubject + '; ');
        output += ('caseEmail: ' + caseEmail + '; ');
        output += ('caseDescription: ' + caseDescription + '; ');
        output += ('caseCommentBody: ' + caseCommentBody + '; ');
        
        accId = Id.valueOf('0010Y00000CpsnEQAR');
        userId = Id.valueOf('0050Y000000RrdeQAC');
        
        // Accounts
        accounts = [select id from Account where id =: accId];
        output += ' accounts count: ' + String.valueOf(accounts.size()) + '; ';
        for (Account account : accounts) {
            output += 'account id: ' + String.valueOf(account.Id) + '; ';
        }
        
        // Contacts
        createContactIfDoesNotExists(caseEmail, accId, userId);
        contacts = [select Id, Email, AccountId, OwnerId from Contact where email =: caseEmail];
        output += ' contacts count: ' + String.valueOf(contacts.size()) + '; ';
        for (Contact contact : contacts) {
        contactId = contact.Id;
            output += 'contact id: ' + String.valueOf(contact .Id) + '; ';
            output += 'contact email: ' + String.valueOf(contact.email) + '; ';
        }

        // Users
        /*users = [select id, email, AccountId, ContactId, FirstName, LastName from User where ContactId =: contactId];
        output += ' users count: ' + String.valueOf(users.size()) + '; ';
        for (User user : users) {
            output += 'user Id: ' + String.valueOf(user.Id) + '; ';
            output += 'user AccountId: ' + String.valueOf(user.AccountId) + '; ';
            output += 'user ContactId: ' + String.valueOf(user.ContactId) + '; ';
            output += 'user FirstName: ' + String.valueOf(user.FirstName) + '; ';
            output += 'user LastName: ' + String.valueOf(user.LastName) + '; ';
        }*/
        
        if (contacts.size() == 1) {
            output += ' GOOD ';
            for (Contact contact : contacts) {
                Case caseObj = new Case(
                    ContactId = contact.Id,
                    AccountId = contact.AccountId,
                    OwnerId = contact.OwnerId,
                    Subject = caseSubject,
                    Status = 'Working',
                    Origin = 'Phone',
                    Description = caseDescription
                   );
                
                insert caseObj;
                output += 'id of new case: ' + caseObj.Id + '; ';
                
                // Insert comment
                CaseComment comment = new CaseComment();
                comment.CommentBody = caseCommentBody;
                comment.IsPublished = TRUE;
                comment.ParentId = caseObj.id;
                //comment.CreatorName = 'John';
                insert comment;
 
 
                //caseObj = [select id, casenumber from Case where id = : caseObj.id];
                //System.debug('case number' + caseObj.casenumber);
            }
        } else {
            output += ' BAD ';
        }
        
        return output;
    }
    
    public static void createContactIfDoesNotExists(String ContactEmail, Id AccountId, Id OwnderId) {
        List<Contact> contacts = [select id, email from Contact where email =: ContactEmail];
        if (contacts.size() == 0) {
            /*User u = new User(
                Email = ContactEmail
            );
            insert u;*/
            
            Contact c = new Contact(
                LastName = 'LastName',
                AccountId = AccountId,
                Email = ContactEmail,
                OwnerId = OwnderId
                //Fax=acc.Fax,
                //MailingStreet=acc.BillingStreet,
                ///MailingCity=acc.BillingCity,
                //MailingState=acc.BillingState,
                //MailingPostalCode=acc.BillingPostalCode,
                //MailingCountry=acc.BillingCountry,
                //Phone=acc.Phone
            );
            insert c;
        }
    }

    @HttpGet
    global static String doGet() {
        String output = '';
        
        String entityClass = RestContext.request.params.get('class');
        if (entityClass == 'Case') {
            output += processCase();
        } else if (entityClass == 'Contact') {
            output += 'Undefined entityClass: ' + entityClass;
        }
 
        return output;           
    }
}