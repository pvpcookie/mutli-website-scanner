<?php 

session_start();

	$n 					= 1; 
	$success 			= 0; 
	$warning 			= 0; 
	$danger 			= 0; 
	$redirect 			= 0;
	$txt_file    		= file_get_contents( "docs/". session_id().".txt" );
	$domains 			= array_map('trim', explode("\n", $txt_file));
	$total				= count($domains);
	$arr_content 		= array();
	$string 			= $_GET['str'];
	
	$message = '';
	
		foreach ( $domains as $domain ) {
			
			
			
			$site 		= 'http://'.$domain;
			$headers	= get_headers($site);
			$ip 		= gethostbyname( $domain );
			
			if($headers[0] == 'HTTP/1.1 301 Moved Permanently') {
				
				$site 		= 'http://www.'.$domain;
				$headers	= get_headers($site);
				
			}
			
			if($string != '') {
				$ch = curl_init($site);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				$text = curl_exec($ch);
				$test = strpos($text, $string);
				if ($test==false)
				{
					$foundString = '<h3>Match: No</h3>';
				}
				else
				{
					$foundString = '<h3>Match: Yes!</h3>';
				}
			}
			else {
				echo 'No sreach';	
			}
			
			switch ($headers[0]) {
					case 'HTTP/1.1 200 OK' :
						$message .= '<div class="alert alert-success">'.$n++.' - <strong> '.$site.': OK </strong> Your site has no Errors!<br />';
						$message .= '<small>IP/Server: <strong>'.$ip.'</strong></small>';
						$message .= $foundString;
						$message .= '</div>';
						$success++;
					break;
					case '' :
						$message .= '<div class="alert alert-warning">'.$n++.' - <strong> '.$site.': Warning </strong> DNS Lookup Failed!<br>';
						$message .= '<small>please check domina is resolved or registered</small><br/>';
						$message .= '<small>IP/Server: <strong>'.$ip.'</strong></small>';
						$message .= $foundString;
						$message .= '</div>';
						$warning++;
					break;
					case 'HTTP/1.1 302 Found' :
						$message .= '<div class="alert alert-warning">'.$n++.' - <strong> '.$site.': Warning </strong> Account suspened<br>';
						$message .= '<small>Account has been suspened or is restriced</small><br />';
						$message .= '<small>IP/Server: <strong>'.$ip.'</strong></small>';
						$message .= $foundString;
						$message .= '</div> ';
						$warning++;
					break;
					case 'HTTP/1.1 301 Moved Permanently' :
						$message .= '<div class="alert alert-success">'.$n++.' - <strong> '.$site.': OK Redirected! </strong> Your site has no Errors! <br>';
						$message .= '<small>There is a permanat redirect on this account try www.'.$domain.'</small><br>';
						$message .= '<small>Code: '.$headers[0].'</small><br />';
						$message .= '<small>IP/Server: <strong>'.$ip.'</strong></small>';
						$message .= $foundString;
						$message .= '</div>';
						$redirect++;
					break;
					case 'HTTP/1.1 302 Object moved' :
						$message .= '<div class="alert alert-success">'.$n++.' - <strong> '.$site.': OK Redirected! </strong> Your site has no Errors! <br>';
						$message .= '<small>There is a redirect on this account try www.'.$domain.' or another TLD</small><br>';
						$message .= '<small>Code: '.$headers[0].'</small><br />';
						$message .= '<small>IP/Server: <strong>'.$ip.'</strong></small>';
						$message .= $foundString;
						$message .= '</div>';
						$redirect++;
					break;
					case 'HTTP/1.1 302 Moved' :
						$message .= '<div class="alert alert-success">'.$n++.' - <strong> '.$site.': OK Redirected! </strong> Your site has no Errors! <br>';
						$message .= '<small>There is a redirect on this account try '.$header[5].' ( etc )</small><br>';
						$message .= '<small>Code: '.$headers[0].'</small><br />';
						$message .= '<small>IP/Server: <strong>'.$ip.'</strong></small>';
						$message .= $foundString;
						$message .= '</div>';
						
						$redirect++;
					break;
					default :
						$message .= '
						<div class="alert alert-danger">'.$n++.' - <strong> '.$site.': Failed! </strong> Site seems to be having problems.'
						.'<br>Code 0: '
						.'<br />';
						$message .= '<small>IP/Server: <strong>'.$ip.'</strong></small>';
						$message .= $foundString;
						$message .= '</div>';
						$danger++;
					break;
			}
			
			$i++;
			$percent 					= intval($i/$total * 100);
			$ip 						= 'No Host found!';
	  		$arr_content['percent'] 	= $percent;
	  		$arr_content['rows'] 	= $i . " row(s)";
			$arr_content['total'] 		= $total;
			$arr_content['success']		= $success;
			$arr_content['redirect']	= $redirect;
			$arr_content['warning']		= $warning;
			$arr_content['danger'] 		= $danger;
			$arr_content['message']		= $message;
	
	  		file_put_contents("tmp/" . session_id() . ".txt", json_encode($arr_content));
			
	}
	/*
	echo '<div class="col-md-3">'
		.'<div style="text-align:center" class="alert alert-success"><h1> '.$success.' </h1><br /><p>OK Accounts</p></div>'
		.'</div>';
		
	echo '<div class="col-md-3">'
		.'<div style="text-align:center" class="alert alert-success"><h1> '.$redirect.' </h1><br /><p>OK Redirections</p></div>'
		.'</div>';
	
	echo '<div class="col-md-3">'
		.'<div style="text-align:center" class="alert alert-warning"><h1> '.$warning.' </h1><br /><p>Warning Accounts</p></div>'
		.'</div>';
		
	echo '<div class="col-md-3">'
		.'<div style="text-align:center" class="alert alert-danger"><h1> '.$danger.' </h1><br /><p>Failed Accounts</p></div>'
		.'</div>';
	
	echo $message;
	*/
?>				
                
