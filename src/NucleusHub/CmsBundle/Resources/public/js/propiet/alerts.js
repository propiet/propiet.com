var alertQueries = function(){
    if (typeof localStorage !== "undefined") {
        if(localStorage.getItem('prtquery')){
            var queries = localStorage.getItem('prtquery'),
            $alert = $('#alertQueries > span'),
            queriesToSave = queries.split(';'),
            totalAlerts = queriesToSave.length;
            $alert.text(totalAlerts);
        }
    } else {
        //not implemented
    }
};
$(document).ready(function(){
    alertQueries();
});