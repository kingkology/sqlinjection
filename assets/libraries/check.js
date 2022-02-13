var interval = setInterval(function()
{

	let url1='../../../apis/controllers/exams/check.php';
	let url2='../../../apis/controllers/exams/confirm_selection.php';

	$.ajax({
	    type : 'POST', // define the type of HTTP verb we want to use (POST for our form)
	    url : url1, // the url where we want to POST
	    //data        : data, // our data object
	    dataType : 'json', // what type of data do we expect back from the server
	    async : true
	})
	// using the done promise callback
	    .done(function(data,textStatus, jqXHR)
	    {
	    	// nreceive the response after sending the request
	    	response=jqXHR.responseText;
	    	response=JSON.parse(response);
	    	let response_Data=response.data;
	    	$('#time_Remaining').html(response_Data);
	    })
	    // using the fail promise callback
	    .fail(function(jqXHR, textStatus, errorThrown)
	    {
	      //Server failed to respond - Show an error message
	      response=jqXHR.responseText;
	      response=JSON.parse(response);
	      let response_Data=response.data;
	      if (response_Data=='Expired') 
	      {
	      	clearInterval(interval);
	      	Toastz('info','','Your time has expired');

	      	let response="";
	      	response=get_data(url2);
	      	response=JSON.parse(response);

	      	let to_display="";
	      	
	      	if (response.status=="200") 
	      	{

	      		/*to_display='<br><div class="col-md-1 col-l-1"></div><div class="col-md-10 col-l-10 all"><div class="all"><div><div class="mid-big-subtitle"><h2><center><font color="green"><i class="fa fa-edit"></i><font></center></h2><br><h3><center><b>'+response.data+'</b></center></h3></div></div></div></div><div class="col-md-1 col-l-1"></div>';
	      		*/
	      		to_display=response.data;
	      		$('#results').html(to_display);
	      		//Swal.fire('<i class="fa fa-edit"></i>',response.data,'info')
	      		//window.location.href='../../../apis/controllers/access/logoutz.php';
	      	}
	      	else
	      	{
	      		
	      		to_display='<br><div class="col-md-1 col-l-1"></div><div class="col-md-10 col-l-10 all"><div class="all"><div><div class="mid-big-subtitle"><h2><center><font color="red">'+response.status+'<font></center></h2><br><h3><center><b>'+response.status_message+'</b></center></h3></div></div></div></div><div class="col-md-1 col-l-1"></div>';

	      		$('#results').html(to_display);

	      	}

	      	//confirm_answers('../../../apis/controllers/exams/confirm_selection.php');
	      }
	      
	    });
	

},1000);