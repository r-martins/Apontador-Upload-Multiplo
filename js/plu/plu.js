$(function() {
	function log() {
		var str = "";

		plupload.each(arguments, function(arg) {
			var row = "";

			if (typeof(arg) != "string") {
				plupload.each(arg, function(value, key) {
					// Convert items in File objects to human readable form
					if (arg instanceof plupload.File) {
						// Convert status to human readable
						switch (value) {
							case plupload.QUEUED:
								value = 'FILA';
								break;

							case plupload.UPLOADING:
								value = 'ENVIANDO';
								break;

							case plupload.FAILED:
								value = 'FALHOU';
								break;

							case plupload.DONE:
								value = 'ENVIADO';
								break;
						}
					}

					if (typeof(value) != "function") {
						row += (row ? ', ': '') + key + '=' + value;
					}
				});

				str += row + " ";
			} else { 
				str += arg + " ";
			}
		});

		$('#log').val($('#log').val() + str + "\r\n");
	}


	$("#uploader").pluploadQueue({
		// General settings
		runtimes: 'html5,gears,browserplus,silverlight,flash,html4',
//		url: 'upload.php?lbsid=' + lbsid ,
		url: '' ,
		max_file_size: '10mb',
		chunk_size: '10mb',
		unique_names: true,

		// Resize images on clientside if we can
		resize: {width: 800, height: 600, quality: 90},

		// Specify what files to browse for
		filters: [
			{title: "Image files", extensions: "jpg,gif,png"}/*,
			{title: "Zip files", extensions: "zip"}*/
		],

		// Flash/Silverlight paths
		flash_swf_url: 'plupload.flash.swf',
		silverlight_xap_url: 'plupload.silverlight.xap',

		// PreInit events, bound before any internal events
		preinit: {
			Init: function(up, info) {
				log('[Init]', 'Info:', info, 'Features:', up.features);
			},

			UploadFile: function(up, file) {
				log('[UploadFile]', file);

				// You can override settings before the file is uploaded
				// up.settings.url = 'upload.php?id=' + file.id;
				// up.settings.multipart_params = {param1: 'value1', param2: 'value2'};
			}
		},

		// Post init events, bound after the internal events
		init: {
			Refresh: function(up) {
				// Called when upload shim is moved
				log('[Refresh]');
			},

			StateChanged: function(up) {
				// Called when the state of the queue is changed
				log('[StateChanged]', up.state == plupload.STARTED ? "INICIADO": "PARADO");
			},

			QueueChanged: function(up) {
				// Called when the files in queue are changed by adding/removing files
				log('[QueueChanged]');
			},

			UploadProgress: function(up, file) {
				// Called while a file is being uploaded
				log('[UploadProgress]', 'File:', file, "Total:", up.total);
			},

			FilesAdded: function(up, files) {
				// Callced when files are added to queue
				log('[FilesAdded]');

				plupload.each(files, function(file) {
					log('  File:', file);
				});
			},

			FilesRemoved: function(up, files) {
				// Called when files where removed from queue
				log('[FilesRemoved]');

				plupload.each(files, function(file) {
					log('  File:', file);
				});
			},

			FileUploaded: function(up, file, info) {
				// Called when a file has finished uploading
				log('[FileUploaded] File:', file, "Info:", info);
			},

			ChunkUploaded: function(up, file, info) {
				// Called when a file chunk has finished uploading
				log('[ChunkUploaded] File:', file, "Info:", info);
			},

			Error: function(up, args) {
				// Called when a error has occured

				// Handle file specific error and general error
				if (args.file) {
					log('[error]', args, "File:", args.file);
				} else {
					log('[error]', args);
				}
			}
		}
	});

	$('#log').val('');
	$('#clear').click(function(e) {
		e.preventDefault();
		$("#uploader").pluploadQueue().splice();
	});
});