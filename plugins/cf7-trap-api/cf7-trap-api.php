<?php 
/**
* Plugin Name: CF7 Form Trap
* Plugin URI: https://github.com/yttechiepress/cf7-trap-api
* Author: Techiepress
* Author URI: https://github.com/yttechiepress/cf7-trap-api
* Description: Get CF7 Data and send to API
* Version: 0.1.0
* License: GPL2
* License URL: http://www.gnu.org/licenses/gpl-2.0.txt
* text-domain: cf7-trap-api
*/

   

defined( 'ABSPATH' ) or die( 'Unauthorized access!' );



add_action( 'cfdb7_before_save', 'techiepress_cf7_data' );






function techiepress_cf7_data( $form_data ) {

   echo "----------------------> techiepress_cf7_data start";
   $base_url = "https://erp-lava-dev13.lavaloon.com";
	$url = $base_url . "/api/method/lava_custom.lava_hr.add_job_applicant";
	// POST fields
	$full_name = $form_data["full_name"];
	$email = $form_data["email"];
	$job_opening_id = $form_data["job_opening_id"][0];
	$cover_letter = $form_data["cover_letter"];
	$City = $form_data["City"];  
	$mobile = $form_data["mobile"];  
	$Nationality = $form_data["Nationality"];  
	$Birthdate= $form_data["Birthdate"];  
	$AddressDetails = $form_data["AddressDetails"];  
	$CompanyName = $form_data["CompanyName"];  
	$JobTitle = $form_data["JobTitle"];  
	$JobDiscription = $form_data["JobDiscription"];  
	$DurationYears = $form_data["DurationYears"];  
	$CurrentJobTitle = $form_data["CurrentJobTitle"];  
	$TotalYearsofExperience = $form_data["TotalYearsofExperience"];  
	$ExpectedNetSalaryAmountEGP = $form_data["ExpectedNetSalaryAmountEGP"];
	$NoticePeriodinDays = $form_data["NoticePeriodinDays"];
	// $source = $form_data["source"][0];

	
	// $co = $form_data["co"];   
   $resume_path = "wp-content/uploads/cfdb7_uploads/" . $form_data['resumecfdb7_file'];

   echo "===================> " . $form_data['resume'];

	//Headers Authorization
	$api_token = "4304264045dd115";
	$api_secret = "e413d23aac1d12e";
	
	$post_fields = array(
		'full_name' => $full_name,
		'email' => $email,
		'job_opening_id' => $job_opening_id,
		'cover_letter' => $cover_letter,
		'City' => $City,
		'mobile' => $mobile,
		'Nationality' => $Nationality,
		'Birthdate' => $Birthdate,
		'AddressDetails' => $AddressDetails,
		'CompanyName' => $CompanyName,
		'JobTitle' => $JobTitle,
		'JobDiscription' => $JobDiscription,
		'DurationYears' => $DurationYears,
		'CurrentJobTitle' => $CurrentJobTitle,
		'TotalYearsofExperience' => $TotalYearsofExperience,
		'ExpectedNetSalaryAmountEGP' => $ExpectedNetSalaryAmountEGP,
		'NoticePeriodinDays' => $NoticePeriodinDays,
		// 'cover_letter' => $source,
		// 'co' => $co	
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
         'timeout'     => 100000
		)
	);

      if ( is_wp_error( $response ) ) {
       $error_message = $response->get_error_message();
       echo "Something went wrong: $error_message";
       }
      else {
      echo 'Response: <pre>';
       print_r( $response );
      echo '</pre>';
   }

   echo "----------------------> techiepress_cf7_data end";
   
}




?>
