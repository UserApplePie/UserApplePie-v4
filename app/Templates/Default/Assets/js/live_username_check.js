$(document).ready(function()
{
 $("#username").keyup(function()
 {
  var name = $(this).val();

  if(name.length >= char_limit.username_min && name.length <= char_limit.username_max)
  {
   $("#resultun").html('');

   /*$.post("username-check.php", $("#reg-form").serialize())
    .done(function(data){
    $("#result").html(data);
   });*/

   $.ajax({

    type : 'POST',
    url  : 'LiveCheckUserName',
    data : $(this).serialize(),
    success : function(data)
        {
              /*$("#resultun").html(data);*/
			if(data == 'OK')
			{
			   $("#resultun").html("<i class='glyphicon glyphicon-ok text-success'></i>");
			   $("#resultun2").html("");
			}
			if(data == 'CHAR')
			{
			   $("#resultun").html("<i class='glyphicon glyphicon-remove text-danger'></i>");
			   $("#resultun2").html("<div class='alert alert-danger alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>" + lang.username_invalid + "</div>");
			}
			if(data == 'INUSE')
			{
			   $("#resultun").html("<i class='glyphicon glyphicon-remove text-danger'></i>");
			   $("#resultun2").html("<div class='alert alert-danger alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>" + lang.username_inuse + "</div>");
			}
        }
    });
    return false;

  }
  else
  {
   $("#resultun").html("<i class='glyphicon glyphicon-remove text-danger'></i>");
   $("#resultun2").html("<div class='alert alert-danger alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>" + lang.username_char_count + "</div>");
  }
 });

});
