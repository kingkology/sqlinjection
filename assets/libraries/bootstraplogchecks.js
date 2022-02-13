setInterval(function()
{

	$.ajax({
		url : "../../login/apis/controllers/access/checklog.php?type=multi_log",
		type : "POST",
		success : function(data)
		{
			// now you state the redirection page after sending the request
			if (data=="out") 
			{
				window.location.href='../../';
			}
			return;
		},
		error : function(data) 
		{
			return;
		}
	});

},20000);