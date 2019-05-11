$(document).ready(function(){
  var timer;
  var timeout = 5000; // Timout duration
  $('#forum_title,#forum_Content').keyup(function(){

   if(timer) {
    clearTimeout(timer);
   }
   timer = setTimeout(saveData, timeout);

  });

  $('#submit').click(function(){
   saveData();
  });
 });

 // Save data
 function saveData(){

  var forum_post_id = $('#forum_post_id').val();
  var forum_title = $('#forum_title').val().trim();
  var forum_content = $('#forum_content').val().trim();
  var token_forum = $('#token_forum').val();

  if(forum_title != '' || forum_content != ''){
   // AJAX request
   $.ajax({
    url: 'NewTopic/2',
    type: 'post',
    data: {forum_post_id:forum_post_id,forum_title:forum_title,forum_content:forum_content,token_forum:token_forum},
    success: function(data){
      if(data != '')
      {
        $('#forum_post_id').val(data);
      }
      $('#autoSave').text("Saved as draft");
      setInterval(function(){
        $('#autoSave').text('');
      }, 5000);
    }
   });
  }
 }
