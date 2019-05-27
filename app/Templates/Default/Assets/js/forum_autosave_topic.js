$(document).ready(function(){
  var timer;
  $('#forum_content,#fourm_title').keyup(function(){

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

  var forum_post_id = $('#forum_post_id').val();
  var forum_cat_id = $('#forum_cat_id').val();
  var forum_title = $('#forum_title').val().trim();
  var forum_content = $('#forum_content').val().trim();
  var token_forum = $('#token_forum').val();
  var forum_topic_autosave = "autosave_topic";

  if(forum_title != '' || forum_content != ''){
   // AJAX request
   $.ajax({
    url: forum_cat_id+'/',
    type: 'post',
    data: {forum_post_id:forum_post_id,forum_title:forum_title,forum_content:forum_content,token_forum:token_forum,forum_topic_autosave:forum_topic_autosave},
    success: function(data){
      if(data != '')
      {
        $('#forum_post_id').val(data);
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
