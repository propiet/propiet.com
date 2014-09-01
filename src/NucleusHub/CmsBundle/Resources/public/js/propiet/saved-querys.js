var savedQuerys = function () {

  var $buttonRemove = $('.button-remove'),
      $form = $('#form-saved-query-edit');

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
    
    $buttonRemove.on('click', function(e) {
        $('#errorSavedQuery').hide();
        $('#successSavedQuery').hide();
        var id = $(this).data("id"),
            user = $(this).data("user");
        deleteSavedQuery(id, user);
        e.preventDefault();
        return false;          
    });

    var $formValidate = $("#form-saved-query-edit").parsley(parsley_config);
    $('#back').hide();
      $('#form-saved-query-submit').on('click', function(e) {
                    
          var isValid = $formValidate.validate();
          if(isValid){            
            updateSavedQuery();
          }
          e.preventDefault();
          return false;          
      });
   
  }

  function updateSavedQuery(){    
    $.post("/api/v1/saved_query/update.json", { form:$form.serializeArray() }, function(result) {          
           if (result == 'SCC_UPDATED') {
                $('#successSavedQuery').show();
                $('#back').show();
            } else {
                $('#errorSavedQuery').show();            
            }
    });
  }

  function deleteSavedQuery(id, user){
    var $data = {      
      'id': id,
      'user': user,
    }
    $.post("/api/v1/saved_query/delete.json", { form:$data }, function(result) {            
            
           if (result == 'SCC_DELETED') {                
                $("#post-"+element.value).hide( "slow");            
                $('#successSavedQuery').show();                           
            } else {
                $('#errorSavedQuery').show();            
            }
    });
  }
      
  return {
      init: init      
  };

}();