<?php
include('includes/header.php');
if($_SESSION['user']['username']!=''){
header("Location:index.php");
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

if($_REQUEST['button_x']!='' &&  $_REQUEST['button_y']!=''){ 
$email=$_REQUEST['email'];
$password=$_REQUEST['password'];
$mobile=$_REQUEST['mobile'];
registration($email,$password,'user',$mobile);

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
?>
<script src="js/phone.js" type="text/javascript"></script>
<script >
function phonevaid(subjectString){
	var regexObj = /^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/;
	if (regexObj.test(subjectString)) {
		var formattedPhoneNumber = subjectString.replace(regexObj, "($1) $2-$3");
	} else {
		return false
	}
}
function validatesignup(){
   
     var pattern =new RegExp(/^([\w\.\-]+)@([\w\-]+)((\.(\w){2,3})+)$/i);
	 if(document.getElementById("email").value == ''){
	   //alert("Please Select Eventtype");
	   document.getElementById("email").setAttribute("class", "error");	 
	   document.getElementById("email").focus();
	   document.getElementById('email_error').style.display="block";	
	   document.getElementById("email_error").innerHTML='<span style="color:red;">Please Enter Email.</span>';
		
	   return false;	  
	   }else if(!pattern.test(document.getElementById('email').value)){
	   document.getElementById("email").setAttribute("class", "error");	 
	   document.getElementById("email").focus();
	   document.getElementById('email_error').style.display="block";	
	   document.getElementById("email_error").innerHTML='<span style="color:red;">invalid   Email.</span>';
	   
	   return false;
	   }
	   
	else if(document.getElementById("password").value == ''){
	   document.getElementById("email").setAttribute("class", "");	
	   document.getElementById("password").setAttribute("class", "error");	 
	   document.getElementById("password").focus();	
	   document.getElementById("email_error").innerHTML='';
	   document.getElementById('password_error').style.display="block";	
	   document.getElementById("password_error").innerHTML='<span style="color:red;">Please Enter password.</span>';
	   	
		return false;
	}
	else if(document.getElementById("mobile").value == ''){
	   document.getElementById("password_error").innerHTML='';
	   document.getElementById("mobile").setAttribute("class", "error");	 
	   document.getElementById("mobile").focus();	
	   document.getElementById('mobile_error').style.display="block";	
	   document.getElementById("mobile_error").innerHTML='<span style="color:red;">Please Enter mobile number</span>';
	   
	   return false;
	}
	else if(phonevaid(document.getElementById("mobile").value) == false && document.getElementById("mobile").value !=''){
	   document.getElementById("password_error").innerHTML='';
	   document.getElementById("mobile").setAttribute("class", "error");	 
	   document.getElementById("mobile").focus();
	   document.getElementById('mobile_error').style.display="block";	
	   document.getElementById("mobile_error").innerHTML='<span style="color:red;">Please Enter mobile number</span>';
		return false;
	}
	else {
	
	return true;
	}
}
</script>

<div id="content">
<div class="logintop"><div id="fb-root"></div><div id="user-info" style="visibility:hidden; display:none;"></div>
</div>
<div class="loginmiddle">
<table id="signup" width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td valign="top"><div >
		<?php if($_REQUEST['msg']!='' &&  count($error)=='0'){?>
     <div style="color:#FF0000; margin-top:30px;  font-size:12px; font-weight:bold; padding:10px;">Thank you for registering. An activation link will be send to your email address provided. Please check your email and activate your account.
</div>               
    <?php }else{ ?>
	
      <table width="500" border="0" cellspacing="0" cellpadding="0" class="box1" style=" padding-top:10px;">
        <tr>
          <td class="signuphead">SIGN UP NOW, It's free</td>
        </tr>
		
        <tr>
          <td>		  
		  <table width="530" border="0" cellspacing="0" cellpadding="0">
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
				<td>
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

               <button id="fb-auth" style="background:none; border:none; text-align:left; cursor:pointer;"></button></td>
		  </tr>
		  </table>
			  <form action="" id="signup_form" name="email_form1" class="form-horizontal" method="post" onSubmit="return validatesignup()">
				<table width="450" border="0" align="center" cellpadding="0" cellspacing="10">
				<?php if($_REQUEST['msg']!='' &&  count($error)=='0'){?>
                  <tr>
                    <td colspan="2" style="color:#FF0000">Thank you for registering .Please Check your email account and activate your account</td>
                  </tr>
				  <?php } ?>
                 
                  <tr>
                    <td width="117">Email</td>
                    <td width="303" id="email_error">&nbsp;</td>
                  </tr>
                  <tr>
                    <td colspan="2">
					<input type="hidden" name="fb_email" id="fb_email">
<input type="hidden" name="fb_user_name" id="fb_user_name">
<input type="hidden" name="fb_user_id" id="fb_user_id">
<!--<input type="text" name="fb_friends_details" id="fb_friends_details"  />-->

                        <label><?php if($error['email']=='1'){ echo "<span class='error' >Email already exists</span>"; } ?>
						<input type="text" name="email" id="email" size="70"  <?php if($error['email']=='1'){ ?>class="error"<?php }else{ echo "calss=''"; } ?> value="<?php echo $_REQUEST['email']; ?>">
                        </label>                    </td>
                  </tr>
                  <tr>
                    <td>Password</td>
                    <td id="password_error">&nbsp;</td>
                  </tr>
                  <tr>
                    <td colspan="2"> <input type="password"  name="password" id="password" size="70" <?php if($error['password']=='1'){ ?>class="error"<?php }else{ echo "calss=''"; } ?>>	</td>
                  </tr>
				   <tr>
                    <td>Mobile Number</td>
                    <td id="mobile_error">&nbsp;</td>
				   </tr>
                  <tr>
                    <td colspan="2">
					<?php if($error['mobile']=='1'){ echo "<span class='error' >Mobile number exists</span>"; } ?>
					<input type="text"  name="mobile" id="mobile" size="70" <?php if($error['mobile']=='1'){ ?>class="error"<?php }else{ echo "calss=''"; } ?> onKeyUp="javascript:backspacerUP(this,event);" onKeyDown="javascript:backspacerDOWN(this,event);" value="<?php echo $_REQUEST['mobile']; ?>">	</td>
                  </tr>
				  
                  <tr>
                    <td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                          <td width="4%">
                              <label></label>                         </td>
                          <td width="65%">&nbsp;</td>
                          <td width="31%"><?php /*?><a  href="forgot.php">Forgot password?</a><?php */?></td>
                        </tr>
                    </table></td>
                  </tr>
                  <tr>
                    <td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                          <td width="70%">&nbsp;</td>
                          <td width="30%">&nbsp;</td>
                        </tr>
                        <tr>
                          <td>										</td>
                        <td><input type="image" src="images/sign_btn.png" name="button" id="button" value="Submit"></td>
                        </tr>
                        <tr>
                          <td colspan="2">&nbsp;</td>
                          </tr>
                        <tr>
                          <td colspan="2">By clicking &quot;Sign up&quot;, I confirm that I agree with the EventWeGo <a href="#">terms of service</a> and <a href="#">privacy policy</a>.<br>
                            <br></td>
                          </tr>
                     <?php /*?>   <tr>
                          <td colspan="2">Attending an event? Click here to access your order.</td>
                        </tr><?php */?>
                        <tr>
                          <td colspan="2">&nbsp;</td>
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
	  <?php } ?>
    </div></td>
    <td><div class="box2">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td>
             

<div style="padding:66px 10px 10px" align="center"><a href="login.php"><img src="images/already_reg.png" width="244" height="86"></a></div></td>
        </tr>
        <tr>
          <td class="signuprightlink"><a href="#">Want to know more?<br>
            Find out how EventWeGo can<br>
            help you sell out your event!</a><br>
            <br>
            Give us a call, we'd love to help. 1-888-541-9753</td>
        </tr>
      </table>
    </div></td>
  </tr>
</table>




</div>
<div class="loginbottom"></div>

</div>





</body>
</html>
