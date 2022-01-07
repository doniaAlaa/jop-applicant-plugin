<?php

function using_curl(){	
	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, '127.0.0.1:8001/api/method/lava_custom.lava_hr.add_job_applicant');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);
	$post = array(
	    'full_name' => 'test_123',
	    'email' => 'test@test.com',
	    'job_opening_id' => 'test-job-opening',
	    'cover_letter' => 'blablabla',
	    'resume' => curl_file_create('/home/mahmoudrizk/Desktop/1.jpg', 'image/jpeg', 'resume')
	);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

	$headers = array();
	$headers[] = 'Cookie: full_name=Administrator; sid=fb08defa7c9f2b5fa1fd95cf2e9b2d41d3cbd37c232ecc79f8e0f079; system_user=yes; user_id=Administrator; user_image=';
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

	$result = curl_exec($ch);
	if (curl_errno($ch)) {
	    echo 'Error:' . curl_error($ch);
	}
	curl_close($ch);
}


function send_using_wp_remote_post(){
	$base_url = "http://127.0.0.1:8001";
	$url = $base_url . "/api/method/lava_custom.lava_hr.add_job_applicant";
	
	// POST fields
	$full_name = "mmmm";
	$email = "m@test.com";
	$job_opening_id = "test-job-opening";
	$cover_letter = "asdfawedawed";
	$resume_path = '/home/mahmoudrizk/Desktop/queries.pdf';
	
	//Headers Authorization
	$api_token = "4c82082b44ac3d6";
	$api_secret = "44952e4ec717b25";
	
	$post_fields = array(
		'full_name' => $full_name,
		'email' => $email,
		'job_opening_id' => $job_opening_id,
		'cover_letter' => $cover_letter	
	);

	$boundary = wp_generate_password( 24 );

	$headers  = array(
		'Authorization' => 'token ' . $api_token . ":" . $api_secret,
		'content-type' => 'multipart/form-data; boundary=' . $boundary,
	);

	$payload = '';
	// First, add the standard POST fields:
	foreach ( $post_fields as $name => $value ) {
		$payload .= '--' . $boundary;
		$payload .= "\r\n";
		$payload .= 'Content-Disposition: form-data; name="' . $name .
			'"' . "\r\n\r\n";
		$payload .= $value;
		$payload .= "\r\n";
	}
	// Upload the file
	if ( $resume_path ) {
		$payload .= '--' . $boundary;
		$payload .= "\r\n";
		$payload .= 'Content-Disposition: form-data; name="' . 'resume' .
			'"; filename="' . basename( $resume_path ) . '"' . "\r\n";
		$payload .= "\r\n";
		$payload .= file_get_contents( $resume_path );
		$payload .= "\r\n";
	}

	$payload .= '--' . $boundary . '--';

	$response = wp_remote_post( $url,
		array(
			'headers'    => $headers,
			'body'       => $payload,
		)
	);

}

?>