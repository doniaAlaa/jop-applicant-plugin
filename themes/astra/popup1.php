<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
/* Popup container - can be anything you want */
.popup {
  position: relative;
  display: inline-block;
  cursor: pointer;
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
}

/* The actual popup */
.popup .popuptext {
  visibility: hidden;
  width: 160px;
  background-color: #555;
  color: #fff;
  text-align: center;
  border-radius: 6px;
  padding: 8px 0;
  position: absolute;
  z-index: 1;
  bottom: 125%;
  left: 50%;
  margin-left: -80px;
}

/* Popup arrow */
.popup .popuptext::after {
  content: "";
  position: absolute;
  top: 100%;
  left: 50%;
  margin-left: -5px;
  border-width: 5px;
  border-style: solid;
  border-color: #555 transparent transparent transparent;
}

/* Toggle this class - hide and show the popup */
.popup .show {
  visibility: visible;
  -webkit-animation: fadeIn 1s;
  animation: fadeIn 1s;
}

/* Add animation (fade in the popup) */
@-webkit-keyframes fadeIn {
  from {opacity: 0;} 
  to {opacity: 1;}
}

@keyframes fadeIn {
  from {opacity: 0;}
  to {opacity:1 ;}
}
</style>
</head>
<body>

<div class="menu">

</div>

<h1>Welcome to my home page!</h1>
<p>Some text.</p>
<p>Some more text.</p>

<?php 
$response = wp_remote_get( 'https://jsonplaceholder.typicode.com/users' );
$posts = json_decode( wp_remote_retrieve_body( $response ) );

$htmltext='<div class="popup" onclick="myFunction()">'.$posts[1]->name.'
  <span class="popuptext" id="myPopup">Transaction Id : 189486834663755</span>
</div>';

$htmltext='<div class="popup" onclick="myFunction()"><form action="javascript:void(0);" method="post">
    <button id="post-btn" name="foo" value="upvote">Buy Now</button>
</form>
  <span class="popuptext" id="myPopup">Transaction Id : 189486834663755</span>
</div>

<p id="name">Donia</p>
<input type="hidden" value="Donia" id="name_input"> 
<p id="price">600 EGP</p>
<input type="hidden" value="600"  id="price_input"> 
 
';

Echo $htmltext;
 


?>

<script>


var price_input = document.getElementById('price_input');
var name_input = document.getElementById('name_input');
console.log(name_input.value);
console.log(price_input.value);
console.log("price_input.value");



// When the user clicks on div, open the popup
function myFunction() {
  var popup = document.getElementById("myPopup");
  popup.classList.toggle("show");
}

 var request = new Request('https://jsonplaceholder.typicode.com/users', 
         { method: 'GET', 
            
           headers: new Headers({ 'Content-Type': 'application/json' }) 
         });

const button = document.getElementById('post-btn');

button.addEventListener('click', async _ => {
  try {     
    const response = await fetch('https://jsonplaceholder.typicode.com/users', {
      method: 'POST',
       
    });
    console.log('Completed!', response);
  } catch(err) {
    console.error(`Error: ${err}`);
  }
});
</script>
</body>
</html>