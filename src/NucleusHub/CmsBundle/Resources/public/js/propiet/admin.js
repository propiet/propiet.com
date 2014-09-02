var button_phone = $(".show_phone");
var button_note = $(".show_note");
var button_agent = $(".show_agent");
var phone = $(".phone");
var note_close = "Cerrar Nota";
var note_open = "Ver Nota";
var agent_close = "Cerrar Agente";
var agent_open = "Datos Agente";
  
button_phone.on('click',function(e){
  var id = $(this).data("id");
  $(this).hide();
  $('.phone_'+id).show();

});

button_note.on('click',function(e){
  var id = $(this).data("id");
  $('.note_'+id).slideToggle('fast', function() {
        if ($(this).is(':visible')) {
             $(".show_note[data-id='"+id+"']").text(note_close);                
        } else {
             button_note.text(note_open);                
        }        
    });

});

button_agent.on('click',function(e){
  var id = $(this).data("id");
  $('.agent_'+id).slideToggle('fast', function() {
        if ($(this).is(':visible')) {
             $(".show_agent[data-id='"+id+"']").text(agent_close);                
        } else {
             button_agent.text(agent_open);                
        }        
    });

});