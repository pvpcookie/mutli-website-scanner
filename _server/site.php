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
					$foundString = '<td class="match no">Match: No</td>';
				}
				else
				{
					$foundString = '<td class="match yes">Match: Yes!</td>';
				}
			}
			else {
				echo 'No sreach';	
			}
			
			switch ($headers[0]) {
					case 'HTTP/1.1 200 OK' :
					
						$message .= '<tr class="" data-status="success" data-toggle="modal" data-id="'.$site.'" data-target="#frameModal">';
						$message .= '<td>'.$n++.'</td>';
						$message .= '<td><i class="ico glyphicon glyphicon-ok"></i></td>';
						$message .= '<td><a href="'.$site.'" target="_blank">'.$domain.'</a></td>';
						$message .= '<td><strong>OK!</strong> Your Domain is UP!</td><td><small>Congratulations no problems here</small></td>';
						$message .= '<td>'.$headers[0].'</td>';
						$message .= $foundString;
						$message .= '<td><small><strong>'.$ip.'</strong></small></td>';
						$message .= '<tr>';
						$success++;
						
					break;
					case '' :
					
						$message .= '<tr class="danger" data-status="Failed" data-toggle="modal" data-id="'.$site.'" data-target="#frameModal">';
						$message .= '<td>'.$n++.'</td>';
						$message .= '<td><i class="ico glyphicon glyphicon-remove"></i></td>';
						$message .= '<td><a href="'.$site.'" target="_blank">'.$domain.'</a></td>';
						$message .= '<td><strong>Failed!</strong> DNS Lookup Failed!</td><td><small>please check domina is resolved or registered</small></td>';
						$message .= '<td>'.$headers[0].'</td>';
						$message .= $foundString;
						$message .= '<td><small><strong>'.$ip.'</strong></small></td>';
						$message .= '<tr>';
						$danger++;
						
					break;
					case 'HTTP/1.1 302 Found' :
					
						$message .= '<tr class="warning" data-status="warning" data-toggle="modal" data-id="'.$site.'" data-target="#frameModal">';
						$message .= '<td>'.$n++.'</td>';
						$message .= '<td><i class="ico glyphicon glyphicon-wrench"></i></td>';
						$message .= '<td><a href="'.$site.'" target="_blank">'.$domain.'</a></td>';
						$message .= '<td><strong>Warning!</strong> Account suspened!</td><td><small>Account has been suspened or is restriced</small></td>';
						$message .= '<td>'.$headers[0].'</td>';
						$message .= $foundString;
						$message .= '<td><small><strong>'.$ip.'</strong></small></td>';
						$message .= '<tr>';
						$warning++;
						
					break;
					case 'HTTP/1.1 301 Moved Permanently' :
					
						$message .= '<tr class="" data-status="success" data-toggle="modal" data-id="'.$site.'" data-target="#frameModal">';
						$message .= '<td>'.$n++.'</td>';
						$message .= '<td><i class="ico glyphicon glyphicon-ok"></i></td>';
						$message .= '<td><a href="'.$site.'" target="_blank">'.$domain.'</a></td>';
						$message .= '<td><strong>OK!</strong> OK Redirected!</td>';
						$message .= '<td><small>There is a permanat redirect on this account try www.'.$domain.'</small></td>';
						$message .= '<td>'.$headers[0].'</td>';
						$message .= $foundString;
						$message .= '<td><small><strong>'.$ip.'</strong></small></td>';
						$message .= '<tr>';
						$success++;
						
					break;
					case 'HTTP/1.1 302 Object moved' :
					
						$message .= '<tr class="warning" data-status="warning" data-toggle="modal" data-id="'.$site.'" data-target="#frameModal">';
						$message .= '<td>'.$n++.'</td>';
						$message .= '<td><i class="ico glyphicon glyphicon-wrench"></i></td>';
						$message .= '<td><a href="'.$site.'" target="_blank">'.$domain.'</a></td>';
						$message .= '<td><strong>Warning!</strong> OK Redirected!</td>';
						$message .= '<td><small>There is a redirect on this account try www.'.$domain.' or another TLD</small></td>';
						$message .= '<td>'.$headers[0].'</td>';
						$message .= $foundString;
						$message .= '<td><small><strong>'.$ip.'</strong></small></td>';
						$message .= '<tr>';
						$warning++;
						
					break;	
					case 'HTTP/1.1 302 Moved' :
						
						$message .= '<tr class="" data-status="success" data-toggle="modal" data-id="'.$site.'" data-target="#frameModal">';
						$message .= '<td>'.$n++.'</td>';
						$message .= '<td><i class="ico glyphicon glyphicon-ok"></i></td>';
						$message .= '<td><a href="'.$site.'" target="_blank">'.$domain.'</a></td>';
						$message .= '<td><strong>Warning!</strong> OK Redirected!</td>';
						$message .= '<td><small>There is a redirect on this account try '.$header[5].' ( etc )</small></td>';
						$message .= '<td>'.$headers[0].'</td>';
						$message .= $foundString;
						$message .= '<td><small><strong>'.$ip.'</strong></small></td>';
						$message .= '<tr>';
						$success++;
						
					break;
					
					case 'HTTP/1.1 403 Forbidden' :
						
						$message .= '<tr class="warning" data-status="warning" data-toggle="modal" data-id="'.$site.'" data-target="#frameModal">';
						$message .= '<td>'.$n++.'</td>';
						$message .= '<td><i class="ico glyphicon glyphicon-wrench"></i></td>';
						$message .= '<td><a href="'.$site.'" target="_blank">'.$domain.'</a></td>';
						$message .= '<td><strong>Warning!</strong> Restricted!</td>';
						$message .= '<td><small>Website is restricted!</small></td>';
						$message .= '<td>'.$headers[0].'</td>';
						$message .= $foundString;
						$message .= '<td><small><strong>'.$ip.'</strong></small></td>';
						$message .= '<tr>';
						$warning++;
						
					break;
					
					default :
					
						$message .= '<tr class="warning" data-status="warning" data-toggle="modal" data-id="'.$site.'" data-target="#frameModal">';
						$message .= '<td>'.$n++.'</td>';
						$message .= '<td><i class="ico glyphicon glyphicon-wrench"></i></td>';
						$message .= '<td><a href="'.$site.'" target="_blank">'.$domain.'</a></td>';
						$message .= '<td><strong>Warning!</strong> Warning!!</td>';
						$message .= '<td><small>Site seems to be having problems</small></td>';
						$message .= '<td>'.$headers[0].'</td>';
						$message .= $foundString;
						$message .= '<td><small><strong>'.$ip.'</strong></small></td>';
						$message .= '<tr>';
						$warning++;
						
					break;
			}
			
			$i++;
			$percent 					= intval($i/$total * 100);
			$ip 						= 'No Host found!';
			$arr_content['currant']		= "Just finished working on / ".$site;
	  		$arr_content['percent'] 	= $percent;
	  		$arr_content['rows'] 		= $i . " row(s)";
			$arr_content['total'] 		= $total;
			$arr_content['success']		= $success;
			$arr_content['warning']		= $warning;
			$arr_content['danger'] 		= $danger;
			$arr_content['message']		= $message;
	
	  		file_put_contents("tmp/" . session_id() . ".txt", json_encode($arr_content));
			
	}

?>				
                
