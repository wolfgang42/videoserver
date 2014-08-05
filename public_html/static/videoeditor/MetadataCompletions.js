define(function (require) {
	var ko = require('lib/knockout');
	var bootbox = require('lib/bootbox');
	var completions = require('completions');
	
	var Metadatum = require('videoeditor/Metadatum');
		
	var metadataKeys = ko.observableArray(Object.keys(completions['metadata']));
	metadataKeys.sort();
	
	function newMetadatum(callback) {
		bootbox.prompt("Name", function(name) {
			if (name == null) {
				// User cancelled; do nothing
				return;
			}
			if (name == "") {
				bootbox.alert("<b>You must specify a name.</b>");
				return;
			}
			if (metadataKeys.indexOf(name) != -1) {
				bootbox.alert("<b>That name already exists.</b>");
				return;
			}
			metadataKeys.push(name);
			callback(name);
		});
	}
	
	function attach(object) {
		object.availableMetadatas = new ko.observableArray([]);
		function updateAvailable(newMetadata) {
			console.log(newMetadata);
			var newAvailableMetadatas = metadataKeys().slice(0);
			for (var i = 0; i < newMetadata.length; i++) {
				newAvailableMetadatas.splice(newAvailableMetadatas.indexOf(newMetadata[i].key), 1);
			}
			object.availableMetadatas(newAvailableMetadatas); // Update
		}
		object.metadata.subscribe(updateAvailable);
		object.newMetadatum = function() {
			newMetadatum(function(name) {
				object.addMetadatum(name);
			});
		};
		updateAvailable([]);
	}
	
	return {
		assoc: completions.metadata,
		attach: attach,
	};
});