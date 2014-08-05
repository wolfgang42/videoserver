define(function(require) {
	require('lib/bootstrap'); // For dropdown menus, bootstrap JS must be loaded
	require('videoeditor/kobind_typeahead');
	
	var ko = require('lib/knockout');
	var bootbox = require('lib/bootbox');
	var completions = require('completions');
	
	var Metadatum = require('videoeditor/Metadatum');
	var Series = require('videoeditor/Series');
	var SeriesCompletions = require('videoeditor/SeriesCompletions');
	var MetadataCompletions = require('videoeditor/MetadataCompletions');
	
	function VideoViewModel() {	
		var self = this; // 'this' can change depending on scope; 'self' won't
		
		this.title = ko.observable("");
		this.series = ko.observable(new Series(' [None]', 0));
		this.metadata = ko.observableArray([]);
		
		this.setSeries = function(series) {
			self.series(series);
		}
		
		this.seriesList = null;
		this.filterSeriesList = null;
		SeriesCompletions.attachFilter(this);
		
		MetadataCompletions.attach(this);
		
		this.deleteMetadatum = function(item) {
			self.metadata.remove(item);
		}
		this.addMetadatum = function(name) {
			self.metadata.push(new Metadatum(name, ""));
		}
	}
	
	return VideoViewModel;
});
