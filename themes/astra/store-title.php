<?php /*Template Name:Awesome Page*/


?>
<main id="site-content"  role"main">

<?php 
$response = wp_remote_get( 'https://jsonplaceholder.typicode.com/users' );
$posts = json_decode( wp_remote_retrieve_body( $response ) );



$htmltext='<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">';

$htmltext.='<div><p style="color:white;"> '.$posts[0]->name.'</p></div>';
 
$htmltext.='</div>';


Echo $htmltext;
?>
</main>

<?php get_template_part('template-parts/content',get_post_type()); ?>


