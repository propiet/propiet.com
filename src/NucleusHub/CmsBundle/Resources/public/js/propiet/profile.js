var profile = function () {

  var $form = $('#form-profile'),
      $formPassword = $('#form-change-password');

  var parsley_config = {

      successClass: 'has-success',
      errorClass: 'has-error',            
      classHandler: function(el) {                
          return $(el.$element).closest('.form-group');
      },
      errorsWrapper: '<span class=\"help-block\"></span>',
      errorElem: '<span></span>'
  }

  function init(){
    initialize();   
  }

  function initialize(){
    check_profile_submit();
    check_password_submit();
    var $formValidate = $form.parsley(parsley_config);
    
      $('#form-profile-submit').on('click', function(e) {
                    
          var isValid = $formValidate.validate();
          if(isValid){            
            updateProfile();
          }
          e.preventDefault();
          return false;          
      });

    var $formValidatePwd = $formPassword.parsley(parsley_config);    
      $('#form-change-password-submit').on('click', function(e) {
                    
          var isValid = $formValidatePwd.validate();
          if(isValid){            
            updatePassword();
          }
          e.preventDefault();
          return false;          
      });
   
  }

  function check_profile_submit() {
    // Store original values
    var orig = [];             
    $("#form-profile :input").each(function () {
      
        var type = $(this).attr("type");
        var tmp = {'type': type, 'value': $(this).val()};
        orig[$(this).attr('id')] = tmp;
    });

    // Check values on change
    $('#form-profile').bind('change keyup', function () {
        var disable = true;
        $("#form-profile :input").each(function () {
            var type = $(this).attr("type");            
            var id = $(this).attr('id');    
            if (type == 'text' || type == 'email') {
                disable = (orig[id].value == $(this).val());
            }    
            if (!disable) { return false; } // break out of loop
        });        
        $('#form-profile-submit').prop('disabled', disable); // update button
    });
  }

  function check_password_submit() {
    // Store original values
    var orig = [];             
    $("#form-change-password :input").each(function () {
      
        var type = $(this).attr("type");        
        var tmp = {'type': type, 'value': $(this).val()};       
        orig[$(this).attr('id')] = tmp;
    });

    // Check values on change
    $('#form-change-password').bind('change keyup', function () {
        var disable = true;
        $("#form-change-password :input").each(function () {
            var type = $(this).attr("type");            
            var id = $(this).attr('id');             
            if (type == 'password') {
                disable = (orig[id].value == $(this).val());
            }  
            if (!disable) { return false; } // break out of loop
        });        
        $('#form-change-password-submit').prop('disabled', disable); // update button
    });
  }

  function updateProfile(){    
    $.post("/api/v1/user/update.json", { form:$form.serializeArray() }, function(result) {          
         if (result == 'SCC_UPDATED') {
              $('#successProfile').show();
              $('#form-profile-submit').prop('disabled', true);

          } else {
              $('#errorProfile').show();            
          }
    });
  }

  function updatePassword(){    
    $.post("/api/v1/user/password/update.json", { form:$formPassword.serializeArray() }, function(result) {          
         if (result == 'SCC_UPDATED') {
              $('#successProfilePassword').show();
              $('#form-change-password-submit').prop('disabled', true);              
          } else {
              $('#errorProfilePassword').show();            
          }
    });
  }
      
  return {
      init: init      
  };

}();