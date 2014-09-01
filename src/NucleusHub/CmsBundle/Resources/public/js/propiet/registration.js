var registration = function () {

  function init(){
    initialize();   
  }

  function initialize(){
    var $selUserRole = $('#user_registration_role'),
        $inputFname = $('#user_registration_first_name'),
        $inputLname = $('#user_registration_last_name'),
        $inputAname = $('#user_registration_agency_name'),
        $blockAgency = $('#agency-block'),
        $blockName = $('#name-block'),
        $submit = $('#btn-submit'),
        $form = $("#form-registration");

    var parsley_config = {
      successClass: 'has-success',
      errorClass: 'has-error',            
      classHandler: function(el) {                
          return $(el.$element).closest('.form-group');
      },
      errorsWrapper: '<span class=\"help-block\"></span>',
      errorElem: '<span></span>'
    }

    // Default Agency hidden
    $blockAgency.addClass('hide');
    $inputAname.attr('required', false);

    $selUserRole.on('change', function(e) {
        var role = $(this).val();
        $inputAname.val('');
        $inputFname.val('');
        $inputLname.val('');
        if(role == 'ROLE_USER' || role == 'ROLE_AGENT') {
          $inputAname.attr('required', false);
          $inputFname.attr('required', true);
          $inputLname.attr('required', true);
          $blockAgency.addClass('hide');
          $blockName.removeClass('hide');
        }
        if(role == 'ROLE_COMPANY') {
          $inputAname.attr('required', true);
          $inputFname.attr('required', false);
          $inputLname.attr('required', false);
          $blockName.addClass('hide');
          $blockAgency.removeClass('hide');
        }
        e.preventDefault();
        return false;          
    });

    var $formParsley = $form.parsley(parsley_config);
    $('#btn-submit').on('click', function(e) {
                    
          var isValid = $formParsley.validate();
          if(isValid){
            $("form#form-registration").submit();
          }
          e.preventDefault();
          return false;          
      });  
  }
      
  return {
      init: init      
  };

}();