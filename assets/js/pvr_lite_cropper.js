$(function () {

        var imageCropperModal = '<div id="imageCropperModal" data-backdrop="static" class="modal">';
            imageCropperModal += '<div class="modal-dialog">';
            imageCropperModal += '<div class="modal-content">';
            imageCropperModal += '<div class="modal-header">';
            imageCropperModal += '<h5 class="modal-title">Change Status</h5>';
            imageCropperModal += '<button type="button" class="close" data-dismiss="modal">&times;</button>';
            imageCropperModal += '</div>';
            imageCropperModal += '<div class="modal-body">';
            imageCropperModal += '<canvas id="image" style="max-width:100%;">';
            imageCropperModal += '</div>';
            imageCropperModal += '<div>';
            imageCropperModal += '<label for="brightness" class="form-label ml-4">Brightness</label>';
            imageCropperModal += '<input type="range" class="form-range ml-3" id="brightness" value="1" min="1" max="100">';
            imageCropperModal += '</div>';
            imageCropperModal += '<div>';
            imageCropperModal += '<label for="contrast" class="form-label ml-4">Contrast</label>';
            imageCropperModal += '<input type="range" class="form-range ml-4" id="contrast" value="0" min="-20" max="20">';
            imageCropperModal += '</div>';
            imageCropperModal += '<div class="modal-footer">';
            imageCropperModal += '<button class="btn btn-info" id="saveImage">Crop</button>';
            imageCropperModal += '<button class="btn btn-danger" id="cancelImage">Cancel</button>';
            imageCropperModal += '</div>';
            imageCropperModal += '</div>';
            imageCropperModal += '</div>';
            imageCropperModal += '</div>';

        $('body').append(imageCropperModal);
        
        var URL = window.URL || window.webkitURL;

        // Import image
        var $inputImage = $('#imageid');
        var $image = $('#image');
        var caman;

        var options = {
            aspectRatio: 9 / 9,
            preview    : '.img-preview',
            showZoomer: false,
            enableResize: false,
            enableOrientation: false,
            mouseWheelZoom: 'ctrl',
            crop       : function (e) {
                $image.val(e.x + ", " + e.y +"," + e.width + "," + e.height);
            }
        };

        var originalImageURL = $image.attr('src');
        var uploadedImageType = 'image/jpeg';
        var uploadedImageURL;


        function startCropper() {
            if ($image.data('cropper')) {
              $image.cropper('destroy');
          }
            // Cropper
            $image.on({
                ready    : function (e) {
                    // console.log(e.type);
                },
                cropstart: function (e) {
                    // console.log(e.type, e.action);
                },
                cropmove : function (e) {
                    // console.log(e.type, e.action);
                },
                cropend  : function (e) {
                    // console.log(e.type, e.action);
                },
                crop     : function (e) {
                    // console.log(e.type, e.x, e.y, e.width, e.height, e.rotate, e.scaleX, e.scaleY);
                },
                zoom     : function (e) {
                    // console.log(e.type, e.ratio);
                }
            }).cropper(options);

        }

        function startCaman(url) {
          caman = Caman('#image', url, function () {
            startCropper();

       });
      }


      //  Keyboard
        $(document.body).on('keydown', function (e) {

            if (!$image.data('cropper') || this.scrollTop > 300) {
                return;
            }

            switch (e.which) {
                case 37:
                    e.preventDefault();
                    $image.cropper('move', -1, 0);
                    break;

                case 38:
                    e.preventDefault();
                    $image.cropper('move', 0, -1);
                    break;

                case 39:
                    e.preventDefault();
                    $image.cropper('move', 1, 0);
                    break;

                case 40:
                    e.preventDefault();
                    $image.cropper('move', 0, 1);
                    break;
            }

        });

        $(document).on('click','#saveImage',function(e){

            var result = $image.cropper('getCroppedCanvas',{ width: 400, height: 400 });

            var data = result.toBlob(function(blob) {
                let container = new DataTransfer();

                let file = new File([blob], "upload.jpg",{type:"image/jpeg", lastModified:new Date().getTime()});

                container.items.add(file);

                document.getElementById('imageid').files = container.files;
            }, 'image/jpeg');

            $('#item-img-output').attr('src',result.toDataURL(uploadedImageType));

            $('#item-img-output').parent('div').show();

            $('#imageCropperModal').modal('hide');

        });

        $(document).on('click','#cancelImage',function(e){

            // var result = $image.cropper('destroy');

            document.getElementById('imageid').value = null;

            $('#item-img-output').attr('src',"");

            $('#item-img-output').parent('div').hide();

            $('#imageCropperModal').modal('hide');

        }); 


        if (URL) {
            $inputImage.change(function () {
                var files = this.files;
                var file;
                
                // if (!$image.data('cropper')) {
                //     return;
                // }

                if (files && files.length) {
                    file = files[ 0 ];

                    if (/^image\/\w+$/.test(file.type)) {
                        uploadedImageType = file.type;


                        if (uploadedImageURL) {
                            URL.revokeObjectURL(uploadedImageURL);
                        }

                        uploadedImageURL = URL.createObjectURL(file);

                        document.getElementById('image').removeAttribute('data-caman-id');

                         // $image.attr('src',uploadedImageURL);

                        // $inputImage.val('');

                        startCaman(uploadedImageURL);

                        $('#imageCropperModal').modal('show');

                    } else {
                        window.alert('Please choose an image file.');
                    }
                }
            });
        } else {
            $inputImage.prop('disabled', true).parent().addClass('disabled');
        }

      $(document).on('change','#brightness', function () {  
        if (caman) {
          caman.revert(false);
          caman.brightness(this.value).render(startCropper);
        }
      });
      $(document).on('change','#contrast', function () {  
        if (caman) {
          caman.revert(false);        
          caman.contrast(this.value).render(startCropper);
        }
      });

    });