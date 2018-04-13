$(document).ready(function()
{
 $("#email").keyup(function()
 {
  var name = $(this).val();

  if(name.length >= char_limit.email_min && name.length <= char_limit.email_max)
  {
   $("#resultemail").html('');

   $.ajax({

    type : 'POST',
    url  : 'LiveCheckEmail',
    data : $(this).serialize(),
    success : function(data)
        {
              /*$("#resultun").html(data);*/
			if(data == 'OK')
			{
			   $("#resultemail").html("<i class='fas fa-check text-success'></i>");
			   $("#resultemail2").html("");
			}
			if(data == 'BAD')
			{
			   $("#resultemail").html("<i class='fas fa-times text-danger'></i>");
			   $("#resultemail2").html("<div class='alert alert-danger alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>" + lang.email_invalid + "</div>");
			}
			if(data == 'INUSE')
			{
			   $("#resultemail").html("<i class='fas fa-times text-danger'></i>");
			   $("#resultemail2").html("<div class='alert alert-danger alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>" + lang.email_inuse + "</div>");
			}
        }
    });
    return false;

  }
  else
  {
   $("#resultemail").html("<i class='fas fa-times text-danger'></i>");
   $("#resultemail2").html("<div class='alert alert-danger alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>" + lang.email_char_count + "</div>");
  }
 });

});
