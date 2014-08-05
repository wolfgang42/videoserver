define(function (require) {
	var ko = require('lib/knockout');
	var bootbox = require('lib/bootbox');
	var completions = require('completions');
	
	var Series = require('videoeditor/Series');
	
	var seriesList = ko.observableArray();
	var seriesKeys = Object.keys(completions.series);
	seriesKeys.sort();
	seriesKeys.forEach(function(name) {
		seriesList.push(new Series(name, completions.series[name]));
	});
	seriesList._old_push = seriesList.push;
	seriesList.push = function(item) {
		seriesList._old_push(item);
		seriesList.sort(function (a, b) {
			if (a.name > b.name) return 1;
			if (a.name < b.name) return -1;
			return 0;
		});
	}
	
	function addNewSeries(video) {
		bootbox.dialog({
			title: "Create Series",
			message: '<div class="splashoverlay" id="newseries-splashoverlay"></div>'+
					'<form id="newseries-dialog" data-bind="asyncSubmit:newSeriesDialogCallback" method="POST" action="/admin/content/edit/save?new=series&return=ok">'+
					document.getElementById('serieseditor-content').innerHTML+
					'</form>',
			buttons: {
				cancel: {
					label: "Cancel",
					className: 'newseries-btn-cancel btn btn-default',
					// No callback needed; just close the dialog.
				},
				save: {
					label: "Save",
					className: 'newseries-btn-save btn btn-primary',
					callback: function() {
						document.getElementById('newseries-dialog').submit();
						return false;
					}
				},
			},
		});
		$('.newseries-btn-save').attr('disabled');
		require(['videoeditor/VideoViewModel'], function (VideoViewModel) {
			var series = new VideoViewModel();
			series.newSeriesDialogCallback = function(details) {
				$('.newseries-btn-cancel').click();
				var seriesName = document.getElementById('newseries-dialog')['*title'].value;
				var newSeries = new Series(seriesName, details.id);
				seriesList.push(newSeries);
				video.series(newSeries);
			}
			ko.applyBindings(series,document.getElementById('newseries-dialog'));
			$(document.getElementById("newseries-splashoverlay")).fadeOut(250);
		});
	}
	
	function attachFilter(object) {
		object.addNewSeries = addNewSeries;
		object.seriesList = ko.observableArray([]);
		var filter='';
		function filterFn() {
			var lowerFilterText = filter.toLowerCase();
			object.seriesList([]);
			seriesList().forEach(function(series) {
				if (series.name.toLowerCase().indexOf(lowerFilterText) != -1) {
					object.seriesList.push(series);
				}
			});
		}
		object.filterSeriesList = function(newFilter) {
			filter=newFilter;
			filterFn();
		}
		seriesList.subscribe(filterFn);
		filterFn(); // Do initial filter
	}
	
	return {
		list: seriesList,
		attachFilter: attachFilter,
	};
});