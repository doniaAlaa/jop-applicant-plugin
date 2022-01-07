<?php /*Template Name:Store test Page v.001*/
$base_url=$_SESSION["base_url"] //configration (get session)
?>
<main id="site-content"  role="main">

<?php


$response = wp_remote_get( ''.$base_url.'/api/method/lava_custom.lava_cashback_api.get_cashback_list' );
$posts = json_decode( wp_remote_retrieve_body( $response ) );



$htmltext='<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous"><div class="row" style="width:60%;">';

if($posts->message->code==200){
 
Foreach($posts->message->data as $post)
{
$htmltext.='<div class=" col-md-6" style="margin-bottom:20px;" ><div style=" 
">


<form action ="http://testapi.local/landing-page" onsubmit="redirect();return false;" class="fill" style="height:200px; border: 1px solid #004AAD; border-radius:10px;">
  
    <input type="hidden"   name="storeID" value="">
       <input type="hidden"   name="type" value="">
    <input type="image" name="submit" src="https://erp-lava-dev13.lavaloon.com/'.$post->logo.'" border="0" alt="Submit"  style="   border-radius: 10px;
    min-width: 100%;
    min-height: 100%" class="imageform" />
   
</form>


 
</center></div></div>';
}
}


function console_log( $response ){
  echo '<script>';
  echo 'console.log('. json_encode( $response ) .')';
  echo '</script>';
}

console_log( $response );



Echo $htmltext;

?>
</main>




