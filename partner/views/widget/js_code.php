	function loadScript(src, cb) {
		var s,
			r,
			t;
		r = false;
		s = document.createElement('script');
		s.type = 'text/javascript';
		s.src = src;
		s.onload = s.onreadystatechange = function() {
			if ( !r && (!this.readyState || this.readyState == 'complete') )
			{
				r = true;
				cb();
			}
		};
		t = document.getElementsByTagName('script')[0];
		t.parentNode.insertBefore(s, t);
	}

	function preLoadWidgetLibs(callback) {
		var afterjQueryLoad = function() {
			if (typeof jQuery.fn.datepicker == 'undefined') {
				loadScript('https://code.jquery.com/ui/1.11.3/jquery-ui.min.js', function(){
					callback();
				});
			}
		};
		if (typeof jQuery == 'undefined') {
			loadScript('http://code.jquery.com/jquery-1.11.3.min.js', afterjQueryLoad);
		} else {
			afterjQueryLoad();
		}
	}

	(function () {

		document.write('<link rel="stylesheet" href="https://code.jquery.com/ui/1.11.3/themes/smoothness/jquery-ui.css"><link rel="stylesheet" href="http://partner.booking.local/widget/css/<?= $code ?>"><div id="widget_<?= $code ?>" style="display: none;"></div>');
		preLoadWidgetLibs(function(){
			var params = <?= $params ?>;
			var el = document.getElementById('widget_<?= $code ?>');
			loadScript('http://partner.booking.local/js/widget/widget.js', function() {
				initWidget(el, params);
			});
		});
	})();

