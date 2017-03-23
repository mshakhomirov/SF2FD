trigger SendAccount on Account (after insert) {
    System.debug('Account Trigger Send2FD!');
    
    for(Account a : Trigger.New) {
        System.debug('Account ' + a);
        SF2FD.SendAccount(a.Id);
    } 
}