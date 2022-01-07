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

   

// defined( 'ABSPATH' ) or die( 'Unauthorized access!' );



// add_action( 'wpcf7_before_send_mail', 'cf7import', 1 );
// // This function allows the $contact_form object to be passed
// function cf7import($contact_form) {
//     $title = $contact_form->invoice;
//     $submission = WPCF7_Submission::get_instance();
//     if ( $submission ) {
//         // get posted data as array
//         $posted_data = $submission->get_posted_data(); 
//         if ( $title == 'Form title') { 
//             $user_id = get_current_user_id();
//             update_user_meta( $user_id, 'your-name', $posted_data['your-name'] );
//             update_user_meta( $user_id, 'email', $posted_data['your-email'] );
//             // update_user_meta( $user_id, 'user_login', $posted_data['Gebruikersnaam'] );
//             update_user_meta( $user_id, 'your-status', $posted_data['your-status'] );
//             update_user_meta( $user_id, 'your-message', $posted_data['your-message'] );
//             // update_user_meta( $user_id, 'billing_company', $posted_data['billing-company'] );
//             // update_user_meta( $user_id, 'billing_address_1', $posted_data['billing-address-1'] );
//             // update_user_meta( $user_id, 'billing_city', $posted_data['billing-city'] );
//             // update_user_meta( $user_id, 'billing_postcode', $posted_data['billing-postcode'] );
//         }
//     }
// }

  


    
// }