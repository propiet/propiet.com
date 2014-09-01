var posts = function () {

  var $buttonAssign = $('#button-assign'),
      $buttonRemove = $('#button-unassign'),
      $buttonCheckbox = $('.button-checkbox');
      $buttonStatus = $('.button-status');

  function init(){
    initialize();   
  }

  function initialize(){
    
    $buttonCheckbox.on('click', function(e) {
        var $selectedPosts = $("#form-assign").serializeArray();
        if($selectedPosts.length > 0) {        
            $buttonAssign.removeAttr("disabled");
            $buttonRemove.removeAttr("disabled");
        } else {
            $buttonAssign.attr("disabled", "disabled");
            $buttonRemove.attr("disabled", "disabled");
        }
        e.preventDefault();
        return false;          
    });
    $buttonAssign.on('click', function(e) {
        $('#errorAssign').hide();
        $('#successStatus').hide();
        assignToAgent();
        e.preventDefault();
        return false;          
    });
    $buttonRemove.on('click', function(e) {
        $('#errorAssign').hide();
        $('#successUnassign').hide();
        unassignAgent();
        e.preventDefault();
        return false;          
    });
    $buttonStatus.on('click', function(e) {
        var post = $(this).data("id"),
            status = $(this).data("status");
        $('#errorAssign').hide();
        $('#successStatus').hide();
        updateStatus(post, status);
        e.preventDefault();
        return false;          
    });    
  }

  function assignToAgent(){
    var $data = {      
      'posts': $("#form-assign").serializeArray(),
    }
    $.post("/api/v1/post/agent/assign.json", { form:$data }, function(result) {            
            
           if (result == 'SCC_UPDATED') {
                $.each($data.posts,function(i,element){
                   $("#post-"+element.value).hide( "slow");
                });                
                $('#successAssign').show();
                $buttonAssign.attr("disabled", "disabled");           
            } else {
                $('#errorAssign').show();            
            }
    });
  }

  function unassignAgent(){
    var $data = {      
      'posts': $("#form-assign").serializeArray(),
    }
    $.post("/api/v1/post/agent/unassign.json", { form:$data }, function(result) {            
            
           if (result == 'SCC_UPDATED') {
                $.each($data.posts,function(i,element){
                   $("#post-"+element.value).hide( "slow");
                });                
                $('#successUnassign').show();
                $buttonAssign.attr("disabled", "disabled");           
            } else {
                $('#errorAssign').show();            
            }
    });
  }

  function updateStatus(post, status){
    var $data = {      
      'post': post,
      'status': status
    }
    $.post("/api/v1/post/status/update.json", { form:$data }, function(result) {            
            
           if (result == 'SCC_UPDATED') {
                
                $("#post-"+post).hide( "slow");
                $('#successStatus').show();
                $buttonAssign.attr("disabled", "disabled");           
            } else {
                $('#errorAssign').show();            
            }
    });
  }
      
  return {
      init: init      
  };

}();