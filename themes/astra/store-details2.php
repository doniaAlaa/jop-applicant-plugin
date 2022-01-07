 <?php /*Template Name:Store Details Page*/



?>
<main id="site-content"  role"main">

<?php 
$response = wp_remote_get( 'https://jsonplaceholder.typicode.com/users' );
$posts = json_decode( wp_remote_retrieve_body( $response ) );

/* Echo $_GET["storeID"]; */

$CheckInput='';
$Design1='<input type="submit" class="button" value="Shop Now" style="background-color: #004AAD; margin-top:30px;
border: none;
color: white;" >';
$Design2='<input type="submit" class="button" value="Buy Now" style="background-color: #004AAD; margin-top:30px;
border: none;
color: white;" >';


$Input='';
$Design3='<p style="font-weight: 700;  font-size:15px; color:#FF1616;">Cashback: 10%</p>';
$Design4='<div id="parent">
<div class="child"><p style="font-weight: 700;  font-size:15px; color:#004AAD;">Code:</p></div>
<div class="child" style="margin-left:10px; "><p class="coupon code" style="font-weight: 600;  font-size:13px; color:#004AAD;">66458578</p></div>
</div>';


$htmltext='<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous"><div class="row">';

$htmltext.='<div class="row center"  id="landigcard" style=""><br><br>
  <div class="col-sm-4" style="padding-top:65px;padding-bottom:40px;"><img src="https://wp-kashati-prod.lavaloon.com/wp-content/uploads/2021/11/ace.png"></img></div>
  <div class="col-sm-8" >
<p style="padding-top:20px;" class="storename vertical-center" style="font-size:22px;font-weight:700px,color:white;" id="bottom">'.$posts[1]->name.'</p>

</div>
</div> 
<div class="row center" style="background-color:white; margin-bottom:40px;" id="aboutlandigcard">
  <div  class="storeabout" id="parent" style="padding-top:20px;">
<div class="child">About</div>
<div class="child">'.$posts[1]->name.'</div>
</div>
<p style="padding-top:20px;" class="storedescription">Since 1924, ACE has been the world’s local home improvement store committed to delivering quality and helpful service. They provide exemplary customer service with global ACE standards. ACE stores in the UAE offer a wide selection of outdoor & garden, DIY & tools, electrical & security, car, paints, pets, and kids. With more than 50,000 products, ACE features world-class brands such as Karcher, Wilko, Dyson, Weber and more.</p>

</div> 

';

$htmltext.='<div id="parent">
<div class="child"><p class="Coupons&Promocode ">'.$posts[1]->name.'</p></div>
<div class="child"><p class="Coupons&Promocode"> Coupons & Promocodes</p></div>
</div>';



Foreach($posts as $key =>  $post)
{
    if($post->name=='Ervin Howell')
   { $CheckInput= $Design1;$Input=$Design4;}
    else
  {  $CheckInput= $Design2;$Input=$Design3;}
$htmltext.='<div class="row"><div class="col-sm-8 servicedata" style=" background-color:white;
  border-radius:10px; margin-bottom:20px;">
 <div id="expand-box">
 <div id="expand-box-header" class="clearfix">
  <span style="float: left; color:#004AAD;
">
<div id="parent">
<div class="child"><h4 style="text-align:left; float:left; padding-top:40px; padding-bottom:20px; font-weight: 700; font-family:Montserrat; font-size:22px; color:#004AAD;">'.$post->name.'</h4></div>
<div class="child"><h4 style="text-align:left; float:left; padding-top:40px; padding-bottom:20px; font-weight: 700; font-family:Montserrat; font-size:22px; color:#004AAD;">-</h4></div>
<div class="child"><h4 style="text-align:left; float:left; padding-top:40px; padding-bottom:20px; font-weight: 700; font-family:Montserrat; font-size:22px; color:#004AAD;">'.$post->name.'</h4></div>
</div>
</span> 
  <span style="float: right;"><form action="https://www.w3schools.com/cssref/css3_pr_border-radius.asp">'.$CheckInput.'</form></span>
 </div>

<div>'.$Input.'</div>


<p style="font-weight: 400;  font-size:12px;  class="create"><img src="https://wp-kashati-prod.lavaloon.com/wp-content/uploads/2021/12/Tag.svg" style=" margin-right:15px;"/> Electronics, Electronics and Gadgets</p>
<p style="font-weight: 400;  font-size:12px;  class="create"><img src="https://wp-kashati-prod.lavaloon.com/wp-content/uploads/2021/12/Location-1.svg" style=" margin-right:15px;"/>UAE</p>


 </div>
 </div>
</div>';
}
   
$htmltext.='</div>';


Echo $htmltext;



?>
</main>

