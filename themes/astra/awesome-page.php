<?php /*Template Name:Awesome Page*/

/*get_header();*/
?>
<main id="site-content"  role="main">

<?php 
$response = wp_remote_get( 'https://jsonplaceholder.typicode.com/users' );
$posts = json_decode( wp_remote_retrieve_body( $response ) );



$htmltext='<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous"><div class="row">';
Foreach($posts as $post)
{
$htmltext.='<div class=" col-md-4" style="margin-bottom:20px;" ><div style=" border: 1px solid #004AAD; border-radius:10px;
"><center>
 
<form action ="http://testapi.local/landing-page" onsubmit="redirect();return false;">
  
    <input type="hidden"   name="storeID" value="'. $post->name.'">
       <input type="hidden"   name="type" value="'. $post->name.'">
    <input type="image" name="submit" src="https://wp-kashati-prod.lavaloon.com/wp-content/uploads/2021/11/elsamari.png" border="0" alt="Submit"   />
   
</form>

 

 <br> '.$post->name.'</center></div></div>';
}
   



Echo $htmltext;
?>
</main>

/*<?php get_template_part('template-parts/content',get_post_type()); ?>*/


