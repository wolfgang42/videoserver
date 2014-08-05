define(function (require) {
	require('lib/typeahead');
	
	var ko = require('lib/knockout');
	var $ = require('lib/jquery');
	var MetadataCompletions = require('videoeditor/MetadataCompletions');
	
	ko.bindingHandlers.typeahead = {
		init: function(element, valueAccessor) {
			$(element).typeahead({
				source: MetadataCompletions.assoc[valueAccessor()],
				sorter: function(items) {items.sort(); return items;},
				minLength: 0,
				items: 10,
				showHintOnFocus: true,
			});
			element.focus();
		},
	};
});