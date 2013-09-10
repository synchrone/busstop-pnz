//textinput custom event
$(document) //hi iphone
    .on('keyup','input.search',function()
    {
        var $this = $(this);
        var timeout = $this.data('timeout');
        var minlength = $this.data('minlength') || 3;

        if($this.val().length < minlength ||
           typeof timeout == 'undefined'
        ){return;}

        if(typeof this.searchTimeout != 'undefined'){
            clearTimeout(this.searchTimeout);
            delete this.searchTimeout;
        }

        this.searchTimeout = setTimeout(function(){
            if($this.val() !==''){
                $this.trigger('textinput');
            }else{
                $this.trigger('clear');
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