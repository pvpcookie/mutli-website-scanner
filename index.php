<?php session_start(); ?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>is it up</title>

<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>

<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

</head>

<body>

	<div style="display:none";>
    	<input id="check"/>
    </div>
    
	<div class="container">
    
    	<div class="row">	
        	<div id="message"></div>
        </div>
    
    	<div class="row">
        	
        	<div class="col-md-4">
            
            	<h2> Upload FIles </h2> <hr>
            
                <form id="uploadForm" action="upload.php" method="post" class="form-vertical">
                <div class="form-group">
                	<label>Upload .txt document:</label><br/>
                    <input class="form-control" name="userImage" type="file" class="inputFile" />
                </div>
                
                <form id="uploadForm" action="upload.php" method="post" class="form-vertical">
                <div class="form-group">
					<button class="btn btn-success pull-right"> Upload domains </button>
                </div>
                    
                </form>
            </div>
            
            <div class="col-md-8">
            	<h2> Preview </h2> <hr>
            	<iframe style="display:none" class="framed_txt" src="" width=100% style="width:100%" height=200 frameborder=0 ></iframe>
            </div>
        
        </div>
        
    	<hr />
        
        <span id="begin" style="display:none">
        
            <div class="row">	
            
                <div class="col-md-12">
                	<input class="str form-control col-md-12" placeholder="Input searchable string here" value=""/>
                    <button id="submision" onClick="checkDomains()" class="btn btn-success" style="width:100%"> Please click here to test domains </button>
                </div>
            
            </div>
            
            <div class="row">
                <div style="text-align:center;padding: 8px;" class="col-md-12" id="loader"></div>
            </div>
            
            <div class="row" >
                
                <div class="col-md-12">
                    <div class="progress" style="width:100%;">
                        <div id="progress" class="progress-bar progress-bar-info progress-bar-striped" role="progressbar"
                      aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:%; ">
                            
                        </div>
                    </div>
                </div>
            
            </div>
        
        </span>
        
        <div class="row" style="display:none" id="responce_wrapper">
            
            <div class="col-md-3">
                    <div style="text-align:center" class="alert alert-success" ><h1 id="res_001"></h1><br /><p>OK Accounts</p></div>
                </div>
            
                <div class="col-md-3">
                    <div style="text-align:center" class="alert alert-success" ><h1 id="res_002"></h1><br /><p>OK Redirections</p></div>
                </div>
        
                <div class="col-md-3">
                    <div style="text-align:center" class="alert alert-warning" ><h1 id="res_003"></h1><br /><p>Warning Accounts</p></div>
                </div>
            
                <div class="col-md-3">
                    <div style="text-align:center" class="alert alert-danger" ><h1 id="res_004"></h1><br /><p>Failed Accounts</p></div>
			</div>
            
            <div id="server-responce" class="col-md-12"> </div>
            
        </div>

    </div>

</body>

<script>

	var timer;
	
	$("#uploadForm").on('submit',(function(e){
		
		e.preventDefault();
		$.ajax({
			url: "upload.php",
			type: "POST",
			data:  new FormData(this),
			contentType: false,
			cache: false,
			processData:false,
			success: function(data){
				$( '.framed_txt' ).fadeIn( 300 );
				$( '.framed_txt' ).attr( 'src', data );
				$( '#begin' ).fadeIn( 300 );
			},
			error: function(){
				$("#message").html('<div class="alert">' + data + '</div>');
			} 	        
		});
	}));
	
    function refreshProgress() {

      $.ajax({
        url: "checker.php?file=<?php echo session_id() ?>",
        success:function(data){
			
		  	//Progress Bar
          	$("#progress").css( 'width', data.percent + '%');
          	$(".progress-bar").text(data.rows + ' of ' + data.total);
			// Summery Blocks
			$("#res_001").text(data.success);
			$("#res_002").text(data.redirect);
			$("#res_003").text(data.warning);
			$("#res_004").text(data.danger);
			// Domains Server sponce on headers 
			$("#server-responce").html(data.message);

          if (data.percent == 100) {
            window.clearInterval(timer);
            timer = window.setInterval(completed, 1000);
          }
        }
      });
    }
	
	function completed() {
      $("#message").html("Completed");
      window.clearInterval(timer);
    }
	
	function checkDomains() {
		var str = $( '.str' ).val();
		$( '#submision' ).hide();
		$( '#responce_wrapper' ).fadeIn( 300 );
		
		$.ajax({
			url:"site.php",
			data: 'str=' + str,
			beforeSend: function() {
				$( '#loader' ).html( '<img style="margin:0 auto; padding: 50px;" src="http://webdomains.co.za//shaun/site-up/38.gif"/><br><h4>Loading...</h4><p> This might take some time</p>' );
			},
			success: function( data ) {
				$( '#loader' ).html( '' );
			}
		});
		timer = window.setInterval(refreshProgress, 1000);
	}
	
</script>


</html>

