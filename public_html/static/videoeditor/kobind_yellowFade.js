define(function (require) {
	var ko = require('lib/knockout');
	
	ko.bindingHandlers.yellowFade = {
		update: function(element, valueAccessor, allBindings) {
			console.log(element);
			if (typeof element._yellowFadeDone == 'undefined') {
				element._yellowFadeDone = false;
			}
			if (!element._yellowFadeDone && valueAccessor()()) { // I really don't understand why valueAccessor() returns a function, but this seems to work
				element._yellowFadeDone = true;
				var alpha = 1;
				var br=0xFF, bg=0xFF, bb=0x00; // Beginning color (r, g, b)
				var er=0xAD, eg=0xD8, eb=0xE6; // Ending    color (r, g, b)
				var event = window.setInterval(function() {
					element.style.backgroundColor = 'rgb('+
							Math.round(br*alpha + er*(1-alpha))+","+
							Math.round(bg*alpha + eg*(1-alpha))+","+
							Math.round(bb*alpha + eb*(1-alpha))+")";
					alpha = alpha - .05;
					if (alpha <= 0) {
						element.style.backgroundColor = 'rgb('+([er, eg, eb].join(","))+")"; // set bg color back
						window.clearInterval(event);
					}
				}, 50);
			}
		},
	};
});