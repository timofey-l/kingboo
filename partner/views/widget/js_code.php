(function () {
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

	var libsToLoad = [
		{
			test: function(){
				return (typeof jQuery == 'undefined');
			},
			url: 'http://code.jquery.com/jquery-1.11.3.min.js'
		},
		{
			test: function(){
				return (typeof jQuery.fn.datepicker == 'undefined');
			},
			url: 'http://code.jquery.com/ui/1.11.3/jquery-ui.min.js'
		},
		{
			test: function() { return true; },
			url: 'http://partner.king-boo.com/js/widget/widget.js'
		}
	];


	function loadWLibs(libs, callback, i) {
		if (typeof i == 'undefined') {
			var i = 0;
		}
		if (typeof libs[i] == 'undefined') {
			callback();
			return;
		}
		if (libs[i]['test']()) {
			loadScript(libs[i]['url'], function(){
				loadWLibs(libs,callback, i+1);
			});
		} else {
			loadWLibs(libs,callback, i+1);
		}
	}


		document.write('<link rel="stylesheet" href="https://code.jquery.com/ui/1.11.3/themes/smoothness/jquery-ui.css"><link rel="stylesheet" href="http://partner.king-boo.com/widget/css/<?= $code ?>"><div id="widget_<?= $code ?>" style="display: none;"></div>');
		preLoadWidgetLibs(function(){
			var params = <?= $widget_params ?>;
			var el = document.getElementById('widget_<?= $code ?>');
			loadWLibs(libsToLoad, function(){
				initWidget(el, params);
			});
		});
	})();

