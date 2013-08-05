(function($){
$.cookie.json = true;
String.prototype.format = function() {
    var args = arguments;
    return this.replace(/{(\d+)}/g, function(match, number) {
        return typeof args[number] != 'undefined'
            ? args[number]
            : match
        ;
    });
};

function get_geolocation(callback,accuracy,timeout,age){
    if (navigator.geolocation){
        navigator.geolocation.getCurrentPosition(
            function(position){
                callback.call(null, position.coords);
            },function(error){
                callback.call(null,{error: error});
            },{
                enableHighAccuracy:accuracy || true,
                timeout:timeout || 30000,
                maximumAge:age || 10000
            }
        );
    }else{
        callback.call(null,{error: 'not_supported'});
    }
}

$(document).on("pageinit", "#search", function() {
    var page = $(this);
    var results = $(page.find('.search-results'));
    var didyoumean = $(page.find('.didyoumean'));

    var standalone_ad = $(page.find('.standalone-ad'));
    if(typeof window.navigator.standalone != 'undefined' &&
       !window.navigator.standalone
    ){
        standalone_ad.show();
    }

    var default_list = function(){
        didyoumean.hide();
        get_geolocation(function(geo){
            var request = {favorite: $.totalStorage('favorite') || []};
            var has_geo = false;

            if(typeof geo.error == 'undefined'){
                request = $.extend(request,geo);
                has_geo = true;
            }

            window.yandexMetrika.params({has_geo:has_geo});

            results.load('/?'+ $.param(request),function(){
                results.listview('refresh');
            })
        });
    };

    var search = $(page.find('.search'))
        .on('textinput',function()
        {
            var query = $(this).val();
            $.get('/search_stations',{q:query},function(data)
            {
                if(data.fixed_query){
                    didyoumean.show()
                        .find('.fixed').html(data.fixed_query);
                }else{
                    didyoumean.hide();
                }
                results.html(data.results).listview('refresh');

                standalone_ad.hide();
                window.yandexMetrika.reachGoal('search',{search_query:query});
            });
        })
        .on('clear',default_list)
    ;
    default_list();
});
$(document).on('pagechange',function(e,v){
    if(typeof v!= 'undefined'){
        v.toPage.trigger('pagechanged');
    }
});
$(document).on("pagechanged", "#forecast", function() {
    var page = $(this);
    var station_id = page.data('station-id');
    var station_name = $(page.find('.station_name')).html();

    var refresh = $(page.find('.refresh'));
    var timeToRefresh = 10;
    if(typeof(window.refreshTimer) != 'undefined'){
        window.refreshTimer.stop();
    }
    window.refreshTimer = $.timer(function(){
        timeToRefresh--;
        if(timeToRefresh == 0){
            refresh.click();
        }else{
            $('.refresh_in').html(timeToRefresh-1);
        }
    },1000,true);

    refresh.click(function(){
        refreshTimer.stop();
        $.mobile.changePage(location.href, {
            allowSamePageTransition:true,
            reloadPage: true,
            changeHash: false
        });
    });

    if(!station_id) //not valid for a vehicle-forecast
    {
        return;
    }

    //there must be a better way ...
    var favorite = $(page.find('.favorite'));
    favorite.data('favorite_handlers',{
        is: function(id){
            return $.inArray(station_id, $.totalStorage('favorite')) != -1;
        },
        set: function(){
            $.totalStorage('favorite',
                $.merge($.totalStorage('favorite') || [],[station_id])
            );
            window.yandexMetrika.reachGoal('favorite',{favorite:station_name});
        },
        unset: function(){
            var favorite_ids = $.totalStorage('favorite');
            delete favorite_ids[favorite_ids.indexOf(station_id)];
            $.totalStorage('favorite',favorite_ids);
        },
        refresh: function(){
            $this = $(this);
            var h = $this.data('favorite_handlers');
            if(h.is()){
                $this
                    .buttonMarkup({ icon: "minus" })
                    .html('Из избранного');
            }else{
                $this
                    .buttonMarkup({ icon: "plus" })
                    .html('В избранное');
            }
            $this.button('refresh');
        }
    })
    .click(function(){
        $this = $(this);
        var h = $this.data('favorite_handlers');
        if(h.is()){
            h.unset();
        }else{
            h.set();
        }
        h.refresh.call($this);
    }).data('favorite_handlers').refresh.call(favorite);

    window.yandexMetrika.reachGoal('forecast',{forecast_query:station_name,
        forecast_query_json: '{"id":'+station_id+', "name":"'+station_name+'"}'
    });

});

$(document).on("pageinit", "#about", function() {
    $($(this).find(".geo-debug"))
    .on('expand',function()
    {
        var that = this;
        get_geolocation(function(geo)
            {
                if(typeof geo.error != 'undefined'){return;}

                var geo_debug = $(that).find('p');
                var text = '<p>Широта: ' + geo.latitude + '</p>'
                         + '<p>Долгота: ' + geo.longitude + '</p>';
                    text += geo.accuracy ? '<p>Точность: ±' + geo.accuracy + ' метров</p>' : '';
                    text += geo.altitude ? '<p>Высота: ' + geo.altitude + '</p>' : '';
                    text += geo.altitudeAccuracy ? '<p>Точность высоты: ±' + geo.altitudeAccuracy + ' метров</p>' : '';
                    text += geo.speed ? '<p>Скорость: ' + geo.speed + '</p>' : '';
                    text += geo.heading ? '<p>Направление: ' + geo.heading + '</p>' : '';
                    text += geo.timestamp ? '<p>Последнее определение: ' + new Date(position.timestamp).toString() + '</p>' : '';
                    geo_debug.html(text);
            },
            true, 30000, 10000
        );
    });
});

})(jQuery);


