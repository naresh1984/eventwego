<?php
include('includes/header.php'); 
if($_SESSION['user']['username']!=''){
header("Location:index.php");
}
if($_REQUEST['button_x']!='' &&  $_REQUEST['button_y']!=''){ 
$username=$_REQUEST['username'];
$password=$_REQUEST['password'];
$remember=$_REQUEST['remember'];
if($remember==""){
$remember='off';
}

userLogin($username,$password,'user',$remember);

}
if($_COOKIE['username']!='' && $_COOKIE['user']!='' && $_COOKIE['password']){	

$_REQUEST['username']=$_COOKIE['username'];
$_REQUEST['password']=$_COOKIE['password'];

}


if($_REQUEST['fb_email']!='' && $_REQUEST['fb_user_name']!='') {

$sql="SELECT * FROM  " . USERS . " WHERE username = '" .$_REQUEST['fb_email']."' AND (status='1')";
$count =sqlnumber($sql);
if($count>0){
$userde=$db->getRow("$sql");
$remember='facebook';
$username=$_REQUEST['fb_email'];
$password=$userde['password'];
userLogin($username,$password,'user',$remember);

}else{	  
 
$email=$_REQUEST['fb_email'];
$password=randamString();
$mobile='';
registration($email,$password,'facebook',$mobile);

}

}


// Google connect script

require_once 'src/Google_Client.php';
require_once 'src/contrib/Google_Oauth2Service.php';

$client = new Google_Client();
$client->setApplicationName("Google UserInfo PHP Starter Application");
// Visit https://code.google.com/apis/console?api=plus to generate your
// oauth2_client_id, oauth2_client_secret, and to register your oauth2_redirect_uri.
// $client->setClientId('insert_your_oauth2_client_id');
// $client->setClientSecret('insert_your_oauth2_client_secret');
// $client->setRedirectUri('insert_your_redirect_uri');
// $client->setDeveloperKey('insert_your_developer_key');
$oauth2 = new Google_Oauth2Service($client);

if (isset($_GET['code'])) {
  $client->authenticate($_GET['code']);
  $_SESSION['token'] = $client->getAccessToken();
  $redirect = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
  header('Location: ' . filter_var($redirect, FILTER_SANITIZE_URL));
  return;
}

if (isset($_SESSION['token'])) {
 $client->setAccessToken($_SESSION['token']);
}

if (isset($_REQUEST['logout'])) {
  unset($_SESSION['token']);
  $client->revokeToken();
}

if ($client->getAccessToken()) {
  $user = $oauth2->userinfo->get();

  // These fields are currently filtered through the PHP sanitize filters.
  // See http://www.php.net/manual/en/filter.filters.sanitize.php
  $email = filter_var($user['email'], FILTER_SANITIZE_EMAIL); 
  $img = filter_var($user['picture'], FILTER_VALIDATE_URL);
  $personMarkup = "$email<div><img src='$img?sz=50'></div>";

  // The access token may have been updated lazily.
  $_SESSION['token'] = $client->getAccessToken();
  

if($email!='') {

$sql="SELECT * FROM  " . USERS . " WHERE username = '" .$email."' AND status='1'";
$count =sqlnumber($sql);
if($count>0){
$userde=$db->getRow("$sql");
$remember='google';
$username=$email;
$password=$userde['password'];
userLogin($username,$password,'user',$remember);
}else{	  
 
$email=$email;
$password=randamString();
$mobile='';
registration($email,$password,'google',$mobile);
}
}


} else {
  $authUrl = $client->createAuthUrl();
}


// Google connect script
?>


<div id="content">
<div class="logintop"></div>
<div class="searchmiddle">
<table id="login" width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><div class="box1">
      <table width="500" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td class="loginhead">LOGIN</td>
        </tr>
        <tr>
          <td><table width="530" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td bgcolor="#FFFFFF">
			  <table  width="450" border="0" cellspacing="0" cellpadding="0" style="margin-top:10px;">
		  <tr>
		  <td>		  	<?php
						if(isset($authUrl)) {
						 ?>
						 <a class='login' href='<?php echo $authUrl; ?>' style="margin-left:50px;"><img src='images/gconnect.png' ></a>
						 <?php 
						} else {
						print "<a class='logout' href='?logout'>Logout</a>";
						}
						?>	
						 
                </td>
				<td><div id="fb-root"></div><div id="user-info" style="visibility:hidden; display:none;"></div>
				<form action="" method="post" name="email_form1">
					<input type="hidden" name="fb_email" id="fb_email">
<input type="hidden" name="fb_user_name" id="fb_user_name">
<input type="hidden" name="fb_user_id" id="fb_user_id">
<!--<input type="text" name="fb_friends_details" id="fb_friends_details"  />-->
</form>
		<script>
//location.hash = 'fb=ramesh';
//199030560229177 compliantbox
//360769990676098 local
window.fbAsyncInit = function() {

  FB.init({ appId: '199030560229177', 
	    status: true, 
	    cookie: false,
	    xfbml: true,
	    oauth: true});

  function updateButton(response) {
  
 
  
    var button = document.getElementById('fb-auth');	

    if (response.authResponse) {
      //user is already logged in and connected
      var userInfo = document.getElementById('user-info');
      FB.api('/me', function(response) {
       // userInfo.innerHTML = '<img src="https://graph.facebook.com/' 
//	  + response.id + '/picture">' + response.name +' EMAIL: '+ response.email+ ' Location: '+ response.location.name;
        button.innerHTML = '';				
      });
  <?php if($_SESSION['user']['username']==''){ ?>
	 // alert('hi');
 		button.click();	
	  <?php } ?>

      button.onclick = function() {
        FB.logout(function(response) {
	      // alert('3');
          //var userInfo = document.getElementById('user-info');
          //userInfo.innerHTML="";
		  
			
			location.href = 'index.php';
			
		  
	
	});
      };
	   
    } else { 
	

		loginflow();
	}
  }


  function loginflow() {
      
	  	
      var button = document.getElementById('fb-auth');
	  
	 
      button.innerHTML = '<img src="images/fconnect.png"  id="fb_image_replace">';
	 
	  	  //alert(button.innerHTML);

	  button.onclick =function() {
        FB.login(function(response) {
	
	  if (response.authResponse) {
            FB.api('/me', function(response) {
	      var userInfo = document.getElementById('user-info');		 	 
	      userInfo.innerHTML = 
                '<img src="https://graph.facebook.com/' 
	        + response.id + '/picture" style="margin-right:5px"/>' 
	        + response.name +' EMAIL: '+ response.email;
			document.getElementById('fb_email').value = response.email;
			document.getElementById('fb_user_name').value = response.name;
			document.getElementById('fb_user_id').value = response.id;
			//showFriendsList();
			//onFriendsListLoaded(response);
			document.email_form1.submit();
	    });	   
          } else {
            //user cancelled login or did not grant authorization
          }
        }, {scope:'email'});  	
      }  

  }
  
<?php /*?>   function onFriendsListLoaded(response)
	{  
	
	  var  fb_donate_id ="";
	  
		if(response.data) {
            $.each(response.data,function(index,friend) {
			fb_donate_id += friend.id+",";
            
			//alert(fb_donate_id);
			
            });
			
	
			
        } 
	
		document.getElementById('fb_friends_details').value = fb_donate_id;	
		
		if(document.getElementById('fb_friends_details').value!=''){
		//alert(document.getElementById('fb_friends_details').value);
		// document.email_form1.submit();
		 }
	    //
	}

  	
	function showFriendsList()
	{
		FB.api('/me/friends', onFriendsListLoaded);  
	}<?php */?>
	
  // run once with current status and whenever the status changes

  FB.getLoginStatus(updateButton);
  FB.Event.subscribe('auth.statusChange', updateButton);	
  //alert(document.getElementById('fb-auth').innerHTML);
};
	
(function() {
  var e = document.createElement('script'); e.async = true;
  e.src = document.location.protocol 
    + '//connect.facebook.net/en_US/all.js';
  document.getElementById('fb-root').appendChild(e);
}());


  
</script>

<button id="fb-auth" style="background:none; border:none; text-align:left; cursor:pointer; "></button>		

             </td>
		  </tr>
		  </table> <form action="" id="signup_form" class="form-horizontal" method="post" onSubmit="return validatesignup()">
			  <table width="450" border="0" align="center" cellpadding="0" cellspacing="10">
			  <?php if($error=='1'){ ?>
                  <tr>
                    <td style="color: #FF0000;">Invalid  Email or password.</td>
                  </tr>
				  <?php }else if($_REQUEST['act']=='act'){ ?>
				  <tr>
                    <td style="color: #FF0000;">Your account is in inactive state. Please check your email to activate your account</td>
                  </tr>
				  <?php }else if($error=='2'){ ?>
				   <tr>
                    <td style="color: #FF0000;">Enter Email and password.</td>
                  </tr>
				  <?php } ?>
                  <tr>
                    <td>Email (or) Mobile</td>
                  </tr>
                  <tr>
                    <td>
                        <label>
                        <input type="text"  name="username" id="username" size="70" value="<?php echo $_REQUEST['username']; ?>" <?php if($error=='1'){ ?>class="error"<?php } ?>  > 
                        </label>                    </td>
                  </tr>
                  <tr>
                    <td>Password</td>
                  </tr>
                  <tr>
                    <td><input type="password" name="password"  id="password" size="70" value="<?php echo $_REQUEST['password']; ?>" <?php if($error=='1'){ ?>class="error"<?php } ?>></td>
                  </tr>
                  <tr>
                    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                          <td width="4%">
                              <label>
                              <input type="checkbox" name="remember" id="remember" <?php if($_COOKIE['remember']=='on'){?> checked="checked"<?php } ?>>
                              </label>                          </td>
                          <td width="65%">Remember me</td>
                          <td width="31%"><a  href="forgot.php" style="color:#666666;">Forgot password?</a></td>
                        </tr>
                    </table></td>
                  </tr>
                  <tr>
                    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                          <td width="70%">&nbsp;</td>
                          <td width="30%">&nbsp;</td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td><input type="image"  src="images/login_button.png" name="button" id="button" value="Submit"></td>
                        </tr>
                    </table></td>
                  </tr>
              </table>
			  </form>
			  </td>
            </tr>
          </table></td>
        </tr>
      </table>
    </div></td>
    <td><div class="box2">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td><div style="padding:66px 10px 10px" align="center"><a href="signup.php" ><img src="images/dont_have_btn.png" alt="" width="244" height="86"></a></div></td>
        </tr>
        <tr>
          <td class="loginrightlink"><a href="#">Big event coming up?</a><br>
            Give us a call, we'd love to help. 1-888-541-9753</td>
        </tr>
      </table>
    </div></td>
  </tr>
</table>




</div>
<div class="searchbottom"></div>

</div>

</body>
</html>
