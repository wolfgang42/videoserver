define(function (require) {
	var ko = require('lib/knockout');
	
	var VideoViewModel = require('videoeditor/VideoViewModel');
	
	function UploadedVideo(file, name, id) {
		var self = this;
		
		this.status = ko.observable('active'); // 'cancelled', 'success'
		
		this.video = new VideoViewModel();
		
		this.file = file;
		this.name = name;
		this.id   = id;
		
		UploadedVideo.all[this.id] = this;
		
		this.progress = ko.observable(0);
		this.progTimedOut = ko.observable(false);
		var progressTimeout;
		this.progress.subscribe(function() {
			window.clearTimeout(progressTimeout);
			if (self.progress() != 1) {
				progressTimeout = window.setTimeout(function() {
					self.progTimedOut(true);
				}, 6000);
			}
		});
		this.retry = function() {
			self.progTimedOut(false);
			this.file.retry();
		}

		this.cancel = function() {
			if (self.file != null) self.file.cancel();
			self.status('cancelled');
		}

		this.complete = ko.observable(false);
	}
	UploadedVideo.all = {};
	UploadedVideo.createFromUpload = function(file) {
		var uv = new UploadedVideo(file, file.fileName, file.uniqueIdentifier);
		return uv;
	}
	UploadedVideo.createFromDetails = function(name, id) {
		var uv = new UploadedVideo(null, name, id);
		uv.progress(1);
		uv.complete(true);
		return uv;
	}
	
	return UploadedVideo;
});
