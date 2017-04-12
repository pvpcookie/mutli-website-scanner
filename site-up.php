<?php session_start(); ?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>is it up</title>

<!-- Bootstrap 3 css -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<!-- Animation and modification css -->
<link rel="stylesheet" href="css/loader.css">
<link rel="stylesheet" href="css/style.css">

<!-- Bootstrap 3 and Jquery javascript -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

</head>

<body>
	<div class="container" >
    
    	<div class="row">
    	<?php require_once( 'header.php' );?>
		<div style="display:none";>
    	<input id="check"/>
    	</div>
    
    	<div id="message" class="alert alert-success alert-dismissible" role="alert" style="width:200px; position:fixed; left:15px; bottom:0; display:none; z-index:999999">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <strong>Finished !</strong> <span id="server_res"></span>
        </div>
    
    	<div class="row">
        	<div class="col-md-4">
				<div class="panel panel-default">
              		<div class="panel-heading">Upload Files</div>
              		<div class="panel-body">
                		 <form id="uploadForm" action="_server/site-upload.php" method="post" class="form-vertical">
                            <div class="form-group">
                                <label>Upload .txt document:</label><br/>
                                <input class="form-control" name="userImage" type="file" class="inputFile" />
                            </div>
                                <button class="btn btn-success pull-right"> Upload domains </button>
						</form>
              		</div>
            	</div>
                
                <div id="stringe_wrapper" class="panel panel-default">
              		<div class="panel-heading">Search for string?</div>
              		<div class="panel-body">
						<input class="str form-control col-md-12" placeholder="Input searchable string here" value=""/>
                        <small> Input string if you want to check you website for it :)</small>
              		</div>
            	</div>  
                  
            </div>	
            
            <div class="col-md-8">
            	
                <div class="panel panel-default" style="min-height: 325px;">
              		<div class="panel-heading">Upload Preview</div>
              		<div class="panel-body">
                    	<div style="display:none; text-align:center; padding: 25px;" class='uil-facebook-css' style='-webkit-transform:scale(1)'><div></div><div></div><div></div></div>
            			<iframe style="display:none" class="framed_txt" src="" width=100% style="width:100%" height=250 frameborder=0 ></iframe>
            		</div>
            	</div>
            </div>
        </div>
        
    	<hr />
        
        <span id="begin" style="display:none">
        
            <div class="row">	
            
                <div class="col-md-12">
                    <button id="submision" onClick="checkDomains()" class="btn btn-success" style="width:100%"> Please click here to test domains </button>
                </div>
            
            </div>
            
            <div class="row">
                <div style="text-align:center;padding: 8px;" class="col-md-12" id="loader"></div>
            </div>
            
            <div class="row" id="progress-section">
                
                <div class="col-md-12">
                    <div class="progress" style="width:100%; min-height: 25px;">
                        <div id="progress" class="progress-bar progress-bar-info progress-bar-striped" role="progressbar"
                      aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:%; ">
                        </div>
                    </div>
                    <ol class="breadcrumb" >
                      <li id="currant_domain"></li>
                    </ol>
                </div>
            
            </div>
        
        </span>
        
        
        <div class="row" style="display:none" id="responce_wrapper">
           
           <section>
			<div class="col-md-12">
				<div class="panel panel-default">
					<div class="panel-body">
						<div class="pull-right">
							<div class="btn-group">
								<button type="button" class="btn btn-success btn-filter" data-target="success">Success <span class="badge" id="res_001"></span></button>
								<button type="button" class="btn btn-warning btn-filter" data-target="warning">Warning <span class="badge" id="res_002"></span></button>
								<button type="button" class="btn btn-danger btn-filter" data-target="Failed">Failed <span class="badge" id="res_003"></span></button>
								<button type="button" class="btn btn-default btn-filter" data-target="all">No Filter <span class="badge" id="res_004"></span></button>
							</div>
						</div>
                        <div style="clear:both"><br></div>
						<div class="table-container">
                        	
							<table class="table table-responsive table-hover table-filter">
                            	<thead>
                                	<tr>
                                    	<th>No.</th>
                                        <th></th>
                                        <th>Domain</th>
                                        <th>Status</th>
                                        <th>message</th>
                                        <th>Code</th>
                                        <th>Server IP</th>
                                    </tr>
                                </thead>
								<tbody id="server-responce">
									
								</tbody>
							</table>
						</div>
                        
					</div>
				</div>
				<div class="content-footer">
					Created by PVP cookie! 2017 v2.1
				</div>
			</div>
		</section>
        </div>

    </div>

<!-- 
<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="frameModal" id="frameModal">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <iframe class="framed_site" src="" width=100% style="width:100%" height=600 frameborder=0 ></iframe>
    </div>
  </div>
</div>
-->


</body>

<!-- Core functions.js page -->

<script>

	var timer;
	
	$("#uploadForm").on('submit',(function(e){
		$( '.uil-facebook-css' ).fadeIn( 50 );
		e.preventDefault();
		$.ajax({
			url: "_server/site-upload.php",
			type: "POST",
			data:  new FormData(this),
			contentType: false,
			cache: false,
			processData:false,
			success: function(data){
				$( '.uil-facebook-css').hide(50);
				$( '.framed_txt' ).fadeIn( 300 );
				$( '.framed_txt' ).attr( 'src', '_server/' + data );
				$( '#begin' ).fadeIn( 300 );
				$( "#server_res" ).fadeIn( 100 );
				$( "#server_res" ).html( 'File Uploaded Successfully: ' + data );
				
			},
			error: function(){
				$("#message").fadeIn(100);
				$("#server_res").html( data );
			} 	        
		});
	}));
	
    function refreshProgress() {

      $.ajax({
        url: "_server/checker.php?file=<?php echo session_id() ?>",
        success:function(data){
			
		  	//Progress Bar
          	$("#progress").css( 'width', data.percent + '%');
          	$(".progress-bar").text(data.rows + ' of ' + data.total);
			//Show currant doomain
			$('#currant_domain').text(data.currant);
			// Summery Blocks
			$("#res_001").text(data.success);
			$("#res_002").text(data.warning);
			$("#res_003").text(data.danger);
			$("#res_004").text(data.total);
			// Domains Server sponce on headers 
			$("#server-responce").html(data.message);

          if (data.percent == 100) {
            window.clearInterval(timer);
            timer = window.setInterval(completed, 1000);
          }
        }
      });
    }
	
</script>

<!-- Filters and Animations -->
<script src="js/script.js"></script>


</html>

