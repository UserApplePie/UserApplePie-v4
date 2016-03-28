$(document).ready(function()
{
 $("#email").keyup(function()
 {
  var name = $(this).val();

  if(name.length >= 5)
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
			   $("#resultemail").html("<i class='glyphicon glyphicon-ok text-success'></i>");
			   $("#resultemail2").html("");
			}
			if(data == 'BAD')
			{
			   $("#resultemail").html("<i class='glyphicon glyphicon-remove text-danger'></i>");
			   $("#resultemail2").html("<div class='alert alert-danger alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>Email Address Invalid!</div>");
			}
			if(data == 'INUSE')
			{
			   $("#resultemail").html("<i class='glyphicon glyphicon-remove text-danger'></i>");
			   $("#resultemail2").html("<div class='alert alert-danger alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>Email is already in use.</div>");
			}
        }
    });
    return false;

  }
  else
  {
   $("#resultemail").html("<i class='glyphicon glyphicon-remove text-danger'></i>");
   $("#resultemail2").html("<div class='alert alert-danger alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>Email must be at least <strong>5</strong> characters.</div>");
  }
 });

});
