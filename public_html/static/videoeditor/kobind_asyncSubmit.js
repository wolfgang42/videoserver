define(function (require) {
	var ko = require('lib/knockout');
	var bootbox = require('lib/bootbox');
	
	var uid = 0;
	
	ko.bindingHandlers.asyncSubmit = {
		init: function(form, successAccessor) {
			var iframe = document.createElement('iframe');
				iframe.name = 'uid-'+(++uid);
				iframe.src = "about:blank";
				iframe.style.display="none";
			function showError(text) {
				var message = iframe.contentDocument.body.innerHTML;
				if (message == '') {
					message = "The server did not return a message.";
				}
				bootbox.dialog({
					title: text,
					message: message,
					buttons: {ok: {label: "OK"}},
				});
			}
			iframe.onabort = function() {
				showError("Loading was aborted before the action was completed.");
			};
			iframe.onerror = function() {
				showError("An error occurred while performing the action.");
			};
			var firstLoad = true;
			iframe.onload = function() {
				if (firstLoad) {
					// Otherwise it fires onLoad() when we first load about:blank
					firstLoad = false;
					return;
				}
				try {
					result=JSON.parse(iframe.contentDocument.body.innerHTML);
				} catch (e) {
					showError("The server returned an unexpected result while performing the action.");
				}
				if (result) {
					if (result.status == 'ok') {
						successAccessor()(result); // Call the accessor to get the callback, then call the callback.
					} else {
						showError('The server returned an error while performing the action.');
					}
				}
			}
			document.body.appendChild(iframe);
			form.target = iframe.name;
		},
	};
});