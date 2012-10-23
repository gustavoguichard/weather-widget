(function($) {
	
	var $sec = $('#weathersec');
	var $cityinput = $('input[name=cityname]');
		
	$('#cityform').submit(function(e){
		e.preventDefault();
		var city = $cityinput.val();
		var url = $(this).attr('action');
		$sec.prepend(getAjaxImg());
		$.post(url, {cityname: city}, function(data){
			var content = $(data).find("#weatherdata").html();
			$sec.html(content);
			setLocationFound();
		});
	})
	$('a#mylocation').live('click', function(e){
		e.preventDefault();
		$sec.html(getAjaxImg());
		yqlgeo.get('visitor',function(o){
			if(o.error){
				alert('Impossible to get your location :(');
			} else {
				$sec.load("weather.php?woeid=" + o.place.woeid, function(data){
					setLocationFound();
				});
			}
		});
	});
	
	function setLocationFound(){
		var $location = $('#locationfound');
		$cityinput.val($location.text());
		if(Modernizr.localstorage) {
		  localStorage.setItem('weather', $sec.html());
		}
		$location.remove();
	}
	
	function getAjaxImg(){
		return "<img id='ajaxloader' src='ajax.gif' style='width:15px;height:15px' />";
	}
	
	if(Modernizr.localstorage) {
		$sec.html(localStorage.weather);
		setLocationFound();
		$('#cityform').submit();
	}
	
	if(Modernizr.geolocation) {
		$sec.after('<a href="#" class="button" id="mylocation">My location</a>');
	}

})(jQuery);