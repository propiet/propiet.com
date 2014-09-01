var sell = function () {

  function init(){
    initialize();   
  }

  function initialize(){
    var $submit = $('#btn-submit'),
        $form = $("#form-sell");

    var parsley_config = {
      successClass: 'has-success',
      errorClass: 'has-error',            
      classHandler: function(el) {                
          return $(el.$element).closest('.form-group');
      },
      errorsWrapper: '<span class=\"help-block\"></span>',
      errorElem: '<span></span>'
    }

    var $formParsley = $("#form-sell").parsley(parsley_config);
    $('#btn-submit').on('click', function(e) {
                    
          var isValid = $formParsley.validate();
          if(isValid){
            $("form#form-sell").submit();
          }
          e.preventDefault();
          return false;          
      });  
  }
      
  return {
      init: init      
  };

}();