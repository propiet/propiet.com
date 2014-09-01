var wizard = function () {

  var $categories = $("#categories"),
      $subcategories = $("#subcategories"),
      $operations = $("#operations"),
      $countries = $("#countries"),
      $regions = $("#regions"),
      $cities = $("#cities"),
      $step1Container = $("#step1-container"),
      $step2Container = $("#step2-container"),
      $step3ContainerServices = $("#step3-container-services"),
      $step3ContainerAmbiences = $("#step3-container-ambiences"),
      $step3ContainerFeatures = $("#step3-container-features"),      
      $step4Container = $("#step4-container");

  var parsley_config = {

      successClass: 'has-success',
      errorClass: 'has-error',            
      classHandler: function(el) {                
          return $(el.$element).closest('.form-group');
      },
      errorsWrapper: '<span class=\"help-block\"></span>',
      errorElem: '<span></span>'
  }  

  function init(swf){
    initialize_navigation();    
    get_categories();
    get_subcategories();
    get_form_step_1();
    validate_step_1();
    get_form_step_2();
    validate_step_2();
    get_form_step_3();
    validate_step_3(swf);

  }

  function initialize_navigation(){

    var navListItems = $('ul.setup-panel li a'),
        allWells = $('.setup-content');
        allWells.hide();
        navListItems.click(function(e)
        {
            e.preventDefault();
            var $target = $($(this).attr('href')),
                $item = $(this).closest('li');
            
            if (!$item.hasClass('disabled')) {
                navListItems.closest('li').removeClass('active');
                $item.addClass('active');
                allWells.hide();
                $target.show();
            }
        });
        
        $('ul.setup-panel li.active a').trigger('click');
  }

  function get_categories() {
    $.getJSON("/api/v1/categories/list.json", function(result) {      
      //don't forget error handling!
      $.each(result, function(i) {      
          $categories.append($("<option />").val(i).text(result[i]));
      });      
    });
  }

  function get_subcategories() {
    $categories.change(function() {
        $('#activate-step-3').attr('disabled','disabled');
        $step2Container.empty();
        $operations.prop('selectedIndex',0);            
        var $dropdown = $(this);        
        $.getJSON("/api/v1/subcategories/list.json", {category:$dropdown.val()}, function(result) {            
            //don't forget error handling!
            $subcategories.empty();
            $.each(result, function(i) {
                $subcategories.append($("<option />").val(i).text(result[i]));
            });       
        });  
    }); 
  }

  function get_form_step_1() {

        $.getJSON("/api/v1/cities/list.json",{region:$regions.val()}, function(result) {      
          //don't forget error handling!          
          $.each(result, function(i, element) {      
              $cities.append($("<option />").val(element.id).text(element.name));
          });
          $('#activate-step-2').removeAttr('disabled');    
        });
  }

  function get_form_step_2(category) {
    $operations.change(function() {             
        var $dropdown = $(this);            
        $.get("/api/v1/wizard/step/1.json", 
          { category:$categories.val(),
            subcategory:$subcategories.val(),
            operation_type:$dropdown.val()
          }, function(result) {
                      
            $step2Container.empty();
            $.each(result, function(index) {     

                var $label = $('<label class="col-sm-4 control-label">'+result[index]['label']+'</label>'),
                    $element = $(''+result[index]['field']+'');            
                    $col8 = $('<div class="col-sm-8"></div>');                    
                    $formgroup = $('<div class="form-group"></div>');                
                    $col4 = $('<div class="col-md-4"></div>');                
                
                $element.attr('data-parsley-required-message', 'Este campo es requerido');
                $element.attr('data-parsley-type', 'number');
                $element.attr('data-parsley-type-message', 'Debe ingresar solo números');               
                $element.appendTo($col8);                
                $label.appendTo($formgroup);
                $col8.appendTo($formgroup);
                $formgroup.appendTo($col4);                        
                $col4.appendTo($step2Container);
                $('#activate-step-3').removeAttr('disabled');
            });            
        });  
    }); 
  }

  function get_form_step_3() {

        $.getJSON("/api/v1/wizard/step/2.json", function(result) {
          
            $.each(result['services'], function(i, element) {
                $span = $('<span class="button-checkbox"></span>');
                $button = $('<button type="button" class="btn ml1 mb1" data-color="success">'+element.name+'</button>');
                $input = $('<input type="checkbox" name="services[]" class="hidden" value="'+element.id+'" />');
                $button.appendTo($span);
                $input.appendTo($span);
                $span.appendTo($step3ContainerServices);                
            });
            $.each(result['ambiences'], function(i, element) {
                $span = $('<span class="button-checkbox"></span>');
                $button = $('<button type="button" class="btn ml1 mb1" data-color="success">'+element.name+'</button>');
                $input = $('<input type="checkbox" name="ambiences[]" class="hidden" value="'+element.id+'" />');
                $button.appendTo($span);
                $input.appendTo($span);
                $span.appendTo($step3ContainerAmbiences);                
            });
            $.each(result['features'], function(i, element) {
                $span = $('<span class="button-checkbox"></span>');
                $button = $('<button type="button" class="btn ml1 mb1" data-color="success">'+element.name+'</button>');
                $input = $('<input type="checkbox" name="features[]" class="hidden" value="'+element.id+'" />');
                $button.appendTo($span);
                $input.appendTo($span);
                $span.appendTo($step3ContainerFeatures);                
            });

            buttonCheckbox.init();
            $('#activate-step-4').removeAttr('disabled');
        });
  }

  function validate_step_1(){
      var $form = $("#form-step-1").parsley(parsley_config);
      $('#activate-step-2').on('click', function(e) {
                    
          var isValid = $form.validate();
          if(isValid){
            $('ul.setup-panel li:eq(1)').removeClass('disabled');
            $('ul.setup-panel li a[href="#step-2"]').trigger('click');
            $('html, body').animate({
                scrollTop: 0
            },500);   
          }
          e.preventDefault();
          return false;          
      });
  }

  function validate_step_2(){
      var $form = $("#form-step-2").parsley(parsley_config);
      $('#activate-step-3').on('click', function(e) {
                    
          var isValid = $form.validate();
          if(isValid){
            $('ul.setup-panel li:eq(2)').removeClass('disabled');
            $('ul.setup-panel li a[href="#step-3"]').trigger('click');
            $('html, body').animate({
                scrollTop: 0
            },500);   
          }
          e.preventDefault();
          return false;          
      });
  }

  function validate_step_3(swf){      
      $('#endButton').hide();
      $('#last-step').on('click', function(e) {
          submit_wizard(swf);
          $('ul.setup-panel li:eq(3)').removeClass('disabled');
          $('ul.setup-panel li a[href="#step-4"]').trigger('click');
          $('html, body').animate({
                scrollTop: 0
            },500); 
          e.preventDefault();
          return false;          
      });
  }

  var delete_img = function(e){
      var img = $(this);
      var imgId = $(this).data('id');
      var r = confirm("¿ Seguro que desea eliminar ?");
      if (r == true) {
          $.post("/api/v1/file/delete.json", { imgId: imgId }, function (result) {
              if (result == 'SCC_DELETED') {
                  $('#successWizard').show();
                  img.remove();
                  $('#image-' + imgId).remove();
              } else {
                  $('#errorWizard').show();
              }
          });
          e.preventDefault();
          return false;
      } else {
          e.preventDefault();
          return false;
      }
  }

  function submit_wizard(swf){
    var $data = {
      'location-post': $("#form-step-1").serializeArray(),      
      'property': $("#form-step-2").serializeArray(),
      'others': $("#form-step-3").serializeArray(),      
    }

      $.post("/api/v1/submit/wizard.json", { form: $data }, function (result) {
          $("#form-step-3").hide("slow", function () {
              if (result.data == 'SCC_CREATED') {
                  $('#successWizard').show();
                  $('#view-post').attr("href", '/publicacion/' + result.id + '-' + result.title.replace(' ', '-'));

                  $('#file_upload').uploadify({
                      'formData': {
                          'post': result.id
                      },
                      "swf": swf,
                      'uploader': '/api/v1/file/upload.json',
                      'buttonText': 'Buscar...',
                      'uploadLimit': 15,
                      'fileSizeLimit': '5MB',
                      'fileTypeDesc': 'Formatos: Jpg, Jpeg, PNG',
                      'onUploadSuccess': function (file, data, response) {
                          var $json = $.parseJSON(data);
                          var $row = $('#images-row');
                          $row.append('<div class="col-xs-6 col-md-3"><a href="#" id="image-' + $json.id + '" class="thumbnail"><img src="http://api.propiet.com/media/' + $json.photo + '" alt="..."></a><a href="#" class="deleteImg" data-id="' + $json.id + '">Eliminar</a></div>')
                          $('#image-panel').removeClass('hidden');
                          $('.deleteImg').on('click', delete_img);
                      },
                      'onUploadComplete': function (file) {
                          $('#successWizardImage').show();
                      },
                      'onUploadError': function (file, errorCode, errorMsg, errorString) {
                          $('#errorWizard').show();
                      }
                  });
              } else {
                  $('#errorWizard').show();
              }
          });
          $('ul.setup-panel li:eq(0)').addClass('disabled');
          $('ul.setup-panel li:eq(0)').removeClass('active');
          $('ul.setup-panel li:eq(1)').addClass('disabled');
          $('ul.setup-panel li:eq(1)').removeClass('active');
          $('ul.setup-panel li:eq(2)').addClass('disabled');
          $('ul.setup-panel li:eq(2)').removeClass('active');
          $('ul.setup-panel li:eq(3)').removeClass('disabled');
          $('ul.setup-panel li:eq(3)').addClass('active');
          $('#endButton').show();
      });
  }
      
  return {
      init: init      
  };

}();