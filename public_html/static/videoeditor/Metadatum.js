define(function (require) {
	var ko = require('lib/knockout');
	
	function Metadatum(key, value) {
		this.key   = key;
		this.value = ko.observable(value);
	}
	
	return Metadatum;
});