String.prototype.format = function() {
    var args = arguments;
    return this.replace(/{(\d+)}/g, function(match, number) {
        return typeof args[number] != 'undefined'
            ? args[number]
            : match
        ;
    });
};

function updateStation(data)
{
    var list = $("#bus_stops");
    list.empty();
    for (var i = 0, station; station = data[i]; ++i)
    {
        list.append(
            $('<li></li>').append(
                $('<a>').attr('href','/forecast?'+
                    $.param({id:station.id, type: station.type})
                )
                .data('station_name',station.name)
                .text(station.name + ' (→'+station.heading+')')
                .click(function(e){
                    $('#forecast').find('h1').text($(this).data().station_name)
                })
            )
        );
    }
    list.listview('refresh');
}

function showForecast(url,options)
{
    $.get( url, function( vehicles ) {
        if ( vehicles ) {
            // Get the page we are going to dump our content into.
            var $page = $( "#forecast" ),
                $content = $page.children( ":jqmData(role=content)" ),

                // The markup we are going to inject into the content
                // area of the page.
                markup = "<ul data-role='listview' data-inset='true'>"
            ;

            // Generate a list item for each item in the category
            // and add it to our markup.
            for ( var i = 0, vehicle; vehicle = vehicles[i]; i++ ) {
                markup += '<li><img src="img/{0}.png" alt="{1}" class="ui-li-icon">{1} <span class="ui-li-count">{2}</span></li>'
                    .format(
                        vehicle.route_type,
                        '{0} → {1}'.format(vehicle.route_num, vehicle.where_go),
                        Math.round(vehicle.arr_time / 60,2) + ' мин.'
                    );
            }
            markup += "</ul>";

            // Inject the category items markup into the content element.
            $content.html( markup );

            // Pages are lazily enhanced. We call page() on the page
            // element to make sure it is always enhanced before we
            // attempt to enhance the listview markup we just injected.
            // Subsequent calls to page() are ignored since a page/widget
            // can only be enhanced once.
            $page.page();

            // Enhance the listview we just injected.
            $content.find( ":jqmData(role=listview)" ).listview();

            // We don't want the data-url of the page we just modified
            // to be the url that shows up in the browser's location field,
            // so set the dataUrl option to the URL for the category
            // we just loaded.
            options.dataUrl = url;
            // Now call changePage() and tell it to switch to
            // the page we just modified.

            $.mobile.changePage( $page, options );
            $page.attr('data-url',url);
            $.mobile.loading('hide');
        }
    },'json');
}


$('#search-station').live('input',function(event,ui){
    if(typeof this.searchTimeout != 'undefined'){
        clearTimeout(this.searchTimeout);
        delete this.searchTimeout;
    }
    var that = this;
    this.searchTimeout = setTimeout(function(){
        console.log(that);
        var text = $(that).val();
        if(text != ''){
            $.get('/search_stations',{
                q:text
            },updateStation,'json');
        }
    },$(this).data().timeout);
});

$('#forecast .refresh').bind('click',function(event,ui){
    $.mobile.loading( 'show' );
    $.mobile.changePage($('#forecast').attr('data-url'));
});

if (navigator.geolocation)
{
    navigator.geolocation.getCurrentPosition(
        function(position)
        {
            var c = position.coords;
            if(c.accuracy < 1500){
                $.get('/nearest_stations',{
                    lat:c.latitude,
                    lon:c.longitude,
                    accuracy: c.accuracy
                },updateStation,'json');
            }
        },
        function(error)
        {
            console.log(error);
        },
        {enableHighAccuracy:true,timeout:30000,maximumAge:10000}
    );

    $( ".geo-debug").bind('expand',function(event, ui)
    {
        var geo_debug = $(this).find('p');
        navigator.geolocation.getCurrentPosition(function(position)
            {
                var c = position.coords;
                var text = '<p>Широта: ' + c.latitude + '</p>'
                         + '<p>Долгота: ' + c.longitude + '</p>';
                text += c.accuracy ? '<p>Точность: ±' + c.accuracy + ' метров</p>' : '';
                text += c.altitude ? '<p>Высота: ' + c.altitude + '</p>' : '';
                text += c.altitudeAccuracy ? '<p>Точность высоты: ±' + c.altitudeAccuracy + ' метров</p>' : '';
                text += c.speed ? '<p>Скорость: ' + c.speed + '</p>' : '';
                text += c.heading ? '<p>Направление: ' + c.heading + '</p>' : '';
                text += c.timestamp ? '<p>Последнее определение: ' + new Date(position.timestamp).toString() + '</p>' : '';
                geo_debug.html(text);
            },null,{enableHighAccuracy:true,timeout:30000,maximumAge:10000}
        );
    });
}

// Listen for any attempts to call changePage().
$(document).bind( "pagebeforechange", function( e, data ) {
	// We only want to handle changePage() calls where the caller is
	// asking us to load a page by URL.
	if ( typeof data.toPage === "string" ) {
		// We are being asked to load a page by URL, but we only
		// want to handle URLs that request the data for a specific
		// category.
		var u = $.mobile.path.parseUrl( data.toPage );
		if ( u.pathname.search("forecast") !== -1 ) {
			// We're being asked to display the items for a specific category.
			// Call our internal method that builds the content for the category
			// on the fly based on our in-memory category data structure.
			showForecast( u.pathname + u.search, data.options );

			// Make sure to tell changePage() we've handled this call so it doesn't
			// have to do anything.
			e.preventDefault();
		}
	}
});