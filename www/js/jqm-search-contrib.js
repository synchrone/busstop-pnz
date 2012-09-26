//textinput custom event
$(document) //hi iphone
    .on('keyup','input.search',function(){
        var timeout = $(this).data('timeout');
        if(typeof timeout == 'undefined'){return;}

        if(typeof this.searchTimeout != 'undefined'){
            clearTimeout(this.searchTimeout);
            delete this.searchTimeout;
        }
        var that = this;
        this.searchTimeout = setTimeout(function(){
            if($(that).val() !==''){
                $(that).trigger('textinput');
            }else{
                $(that).trigger('clear');
            }
        },timeout);
    })
    .on('change','input.search',function(){
        var $this = $(this);
        if($this.val() == ''){
            $this.trigger('clear');
        }
    })
;