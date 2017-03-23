trigger SendCase on Case (after insert, after update) {
    System.debug('SendCase Trigger!');
    for(Case c : Trigger.New) {
        SF2FD.SendCase(c.Id);
    }
}