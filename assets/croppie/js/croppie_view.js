
$(document).ready(function(){



  $('#thumbnail').on('change', function(){
    var reader = new FileReader();




    reader.onload = function (event) {


      $image_crop.croppie('bind', {
        url: event.target.result
      }).then(function(){
        console.log(event);
      });
    }
    reader.readAsDataURL(this.files[0]);
    $('#uploadimageModal').modal('show');


  });

  $('.crop_image').click(function(event){
    $image_crop.croppie('result', {
      type: 'canvas',
      size: 'viewport'
    }).then(function(response){

      console.log(response);
      
      $.ajax({
        url:"/wp-content/plugins/gestus_nord/assets/croppie/js/upload.php",
        type: "POST",
        data:{"image": response},
        success:function(data)
        {
         
          $('#uploadimageModal').modal('hide');
          $('#uploaded_image').html(data);
         
        },
        error: function() {
          alert('ajax error');
        }
      });
    })
  });

});  