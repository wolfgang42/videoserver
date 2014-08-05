requirejs.config({
	shim: {
		'lib/knockout': {
			exports: 'ko',
		},
		'lib/bootstrap': {
			deps: ['lib/jquery'],
		},
		'lib/jquery': {
			exports: '$',
		},
		'lib/typeahead': {
			deps: ['lib/bootstrap'],
		},
		'lib/bootbox': {
			deps: ['lib/bootstrap', 'lib/jquery'],
			exports: 'bootbox',
		},
		'lib/resumable': {
			exports: 'Resumable',
		},
	},
	paths: {
		'lib/jquery': 'lib/mediaelement/jquery',
		'lib/bootstrap': 'lib/bootstrap/js/bootstrap.min',
		'lib/bootbox': 'lib/bootbox.min',
		'lib/typeahead': 'lib/bootstrap3-typeahead.min',
		'completions': '/completions',
	},
	baseUrl: "/static/"
});
requirejs.onError = function (err) {
    console.log(err);
    alert("An error occurred while loading the editor. Press OK to retry.");
	document.location.reload();
};