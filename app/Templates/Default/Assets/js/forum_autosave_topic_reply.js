$(document).ready(function(){
  var timer;
  $('#fpr_content').keyup(function(){

   if(timer) {
    clearTimeout(timer);
   }
   timer = setTimeout(saveData, 1000);

  });

  $('#submit').click(function(){
   saveData();
  });
 });

 // Save data
 function saveData(){

  var fpr_post_id = $('#fpr_post_id').val();
  var topic_id = $('#topic_id').val();
  var fpr_content = $('#fpr_content').val().trim();
  var token_forum = $('#token_forum').val();
  var forum_topic_reply_autosave = "autosave_topic_reply";

  if(fpr_content != ''){
   // AJAX request
   $.ajax({
    url: '/Topic/' + topic_id + "/",
    type: 'post',
    data: {fpr_post_id:fpr_post_id,topic_id:topic_id,fpr_content:fpr_content,token_forum:token_forum,forum_topic_reply_autosave:forum_topic_reply_autosave},
    success: function(data){
      if(data != '')
      {
        $('#fpr_post_id').val(data);
      }
      $('#autoSave').html("<b><font color=green>Saved as draft...</font></b>");
      console.log("Save Data as Draft");
      setInterval(function(){
        $('#autoSave').text('');
      }, 5000);
    }
   });
  }
 }
