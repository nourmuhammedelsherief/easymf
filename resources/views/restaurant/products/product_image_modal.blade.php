<!-- Modal -->
<div class="modal fade" id="cropperImage" tabindex="-1" aria-labelledby="cropperImageLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
	  <div class="modal-content">
		<div class="modal-header">
		  <h5 class="modal-title" id="cropperImageLabel">{{ trans('dashboard.product_image') }}</h5>
		  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		  </button>
		</div>
		<div class="modal-body">
		  <div class="product-image" dir="rtl" style="max-width: 100%;">
			<img style="max-height:500px;"  dir="rtl"  src="" id="edit-image">
		  </div>
		</div>
		<div class="modal-footer">
			
			</button>
			<button type="button"  class="btn btn-secondary zoom-in" ><i class="fa fa-search-plus" aria-hidden="true"></i>
			</button>
			<button type="button"  class="btn btn-secondary zoom-out" ><i class="fa fa-search-minus" aria-hidden="true"></i>
			</button>
			<button type="button"  class="btn btn-secondary restore" ><i class="fa fa-retweet" aria-hidden="true"></i>
			</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal">{{ trans('dashboard.cancel') }}</button>
            <button type="button" class="btn btn-primary" id="crop">{{ trans('dashboard.save') }}</button>
          </div>
	  </div>
	</div>
  </div>

  @push('styles')
  	<link rel="stylesheet" href="{{asset('admin/css/cropper.css')}}">
	  <style>
		.custom-label{
			display: inline;
		}
		#productUploadImage .product-image{
			max-width: 100%;;
			
		}
		.progress {
			display: none;
			margin-bottom: 1rem;
		}
		.cropper-container ,.cropper-wrap-box, .cropper-canvas, .cropper-drag-box, .cropper-crop-box, .cropper-modal , .cropper-container *{
			direction: ltr !important;
		}
	  </style>
  @endpush

  @push('scripts')
	  <script src="{{asset('admin/js/cropper.js')}}"></script>
	  <script>
		window.addEventListener('DOMContentLoaded', function () {
		  var avatar = document.getElementById('avatar');
		  var image = document.getElementById('edit-image');
		  var input = document.getElementById('image-uploader');
		  var $progress = $('.progress');
		  var $progressBar = $('.progress-bar');
		  var $alert = $('.alert');
		  var $modal = $('#cropperImage');
		  var cropper;
		  var oldImage = null;
		  var action = "{{!empty($itemId) ? 'edit' : 'create'}}";
		  var itemId = {{isset($itemId) ? $itemId : 0}};
		  var imageRate = {{isset($editorRate) ? ($editorRate[0] / $editorRate[1]) : 1}};
		  

		  $('[data-toggle="tooltip"]').tooltip();
	
		  input.addEventListener('change', function (e) {
			var files = e.target.files;
			
			var done = function (url) {
			  input.value = '';
			  image.src = url;
			  $alert.hide();
			  $modal.modal('show');
			};
			var reader;
			var file;
			var url;
	
			if (files && files.length > 0) {
			  file = files[0];
	
			  if (URL) {
				done(URL.createObjectURL(file));
			  } else if (FileReader) {
				reader = new FileReader();
				reader.onload = function (e) {
				  done(reader.result);
				};
				reader.readAsDataURL(file);
			  }
			}
		  });
	
		  $modal.on('shown.bs.modal', function () {
			cropper = new Cropper(image, {
				
				viewMode: 2,
				dragMode: 'move',
				aspectRatio: imageRate,
				// autoCropArea: 0.95,
				restore: false,
				guides: true,
				center: true,
				highlight: true,
				cropBoxMovable: true,
				cropBoxResizable: true,
				// toggleDragModeOnDblclick: false,
			});
			$modal.find('.zoom-in').on('click' ,function(){
				console.log('zoom-in');
				cropper.zoom(0.1);
			});
			$modal.find('.zoom-out').on('click' ,function(){
				console.log('zoom-out');
				cropper.zoom(-0.1);
			});
			$modal.find('.restore').on('click' ,function(){
				cropper.reset();
			});
		  }).on('hidden.bs.modal', function () {
			cropper.destroy();
			cropper = null;
		  });
	
		  document.getElementById('crop').addEventListener('click', function () {
			var initialAvatarURL;
			var canvas;
	
			$modal.modal('hide');
	
			if (cropper) {
			  canvas = cropper.getCroppedCanvas({
				// width: 200,
				// height: 200,
			  });
			  initialAvatarURL = avatar.src;
			  avatar.src = canvas.toDataURL();
			  $progress.show();
			  $alert.removeClass('alert-success alert-warning');
			  canvas.toBlob(function (blob) {
				var formData = new FormData();
	
				formData.append('photo', blob, 'image.jpg');
				formData.append('_token' , '{{csrf_token()}}');
				console.log(action + ' - ' + itemId);
				if(action == 'edit') formData.append('item_id' , itemId);
				formData.append('action' , action);
				if(oldImage != null) formData.append('old_image' , oldImage);
				console.log('old image : ' + oldImage);
				$.ajax('{{$imageUploaderUrl}}', {
				  method: 'POST',
				  data: formData,
				  processData: false,
				  contentType: false,
					// headers : {Accept : 'application/json'}	,
				  xhr: function () {
					var xhr = new XMLHttpRequest();
	
					xhr.upload.onprogress = function (e) {
					  var percent = '0';
					  var percentage = '0%';
	
					  if (e.lengthComputable) {
						// console.log('loaded : ' + e.loaded +' | total : '+ e.total);
						percent = Math.round((e.loaded / e.total) * 100);
						percentage = percent + '%';
						$progressBar.width(percentage).attr('aria-valuenow', percent).text(percentage);
					  }
					};
					
					return xhr;
				  },
	
				  success: function (json) {
					console.log(json);
					oldImage = json.photo;
					if(action == 'create'){
						var input = $('form input[name=image_name]');
						
						if(!input.length){
							$alert.show().addClass('alert-warning').text('{{trans('dashboard.errors.upload_fail')}}');
						}
						else{
							
							input.prop('value'  , json.photo);
							$alert.show().addClass('alert-success').text('{{trans('dashboard.messages.upload_success')}}');
						}
					}else{
						$alert.show().addClass('alert-success').text('{{trans('dashboard.messages.upload_success')}}');
						
					}
					
				  },
	
				  error: function (xhr) {
					console.log(xhr);
					avatar.src = initialAvatarURL;
					$alert.show().addClass('alert-warning').text('{{trans('dashboard.errors.upload_fail')}}');
				  },
	
				  complete: function () {
					$progress.hide();
				  },
				});
			  });
			}
		  });
		});
	  </script>
  @endpush
  