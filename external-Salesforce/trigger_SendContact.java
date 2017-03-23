trigger SendContact on Contact (after insert) {
    System.debug('Contact Trigger Send2FD!');
    
    for(Contact c : Trigger.New) {
        System.debug('Contact ' + c);
        SF2FD.SendContact(c.Id);
    } 
}