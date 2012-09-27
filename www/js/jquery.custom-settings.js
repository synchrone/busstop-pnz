$(document).bind("mobileinit", function(){
    $.mobile.defaultDialogTransition =
    $.mobile.defaultPageTransition = 'none';

    var origBack = $.mobile.back;
    $.mobile.back = function() {
        if(typeof $.mobile.urlHistory.getPrev() == 'undefined'){
            $.mobile.changePage('/');
        }else{
            origBack.call(this);
        }
    };
});