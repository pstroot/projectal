<? 
$totalItemsInCart = 0;
if(isset($_SESSION["cart"])){
	foreach($_SESSION["cart"]->contents as $cartItem){
		$totalItemsInCart += $cartItem["qty"];
	}
}

?>
 <nav id="utility-links"> 
    <ul>
     <?php
	 if(isset($_SESSION['COWOA'])){
		 ?>
		  <li id="welcomeLinks">
            <ul>
            <? if ($totalItemsInCart > 0){ ?>
            <li id="account"><a href="index.php?main_page=shopping_cart">My Cart</a> (<?= $totalItemsInCart; ?>)</li>
            <? } ?>
            <li id="logout"><a href="index.php?main_page=logoff">End Session</a></li>
            </ul>
        </li>
        <?
		 
	 } else if ($_SESSION['customer_id'] ) { // if user is checking out without an account, then do not show "my account" links?>
     
        <li id="welcomeLinks">Hi <span id="customer_first_name"><?=@$_SESSION["customer_first_name"];?></span>
            <ul>
            <li id="account"><a href="index.php?main_page=account">My Account</a></li>
            <? if ($totalItemsInCart > 0){ ?>
            <li id="account"><a href="index.php?main_page=shopping_cart">My Cart</a> (<?= $totalItemsInCart; ?>)</li>
            <? } ?>
            <li id="logout"><a href="index.php?main_page=logoff">Logout</a></li>
            </ul>
        </li>
        
        <? } else { ?>  
        
        <li id="joinOrLogin">            
            <ul>
            <li id="join"><a href="index.php?main_page=join" ></a></li>
            <li id="or"><div></div></li>
            <li id="login"><a href="javascript:toggleLogin()" ></a></li>
            </ul>   
        </li>      

		<? } ?>    
                
        <li id="socialNetworkingLinks">
            <a href="http://twitter.com/projectal" class="twitter"></a>
            <a href="http://www.facebook.com/Projectal" class="facebook"></a>
        </li>
    </ul>
</nav>     
        
        
<aside id="loginForm-short">
    <img src="<?php echo DIR_WS_TEMPLATE?>images/loginForm_arrow.gif" class="arrow" alt=" " />

        <form name="login-form" action="index.php?main_page=login&amp;action=process" method="POST" style="display:block;">
        Username or Email
        <br>
        <?php echo zen_draw_hidden_field('securityToken', $_SESSION['securityToken']); ?>
        <input type="text" name="email_address" size = "41" maxlength= "96" id="login-email-address" /><BR> 
        Password
        <BR>
        <input type="password" name="password" size = "41" maxlength = "40" id="login-password" /><BR> 
        <input type="submit" class="submitBtn" value="Sign in" name="submit">
        <input type="checkbox" name="remember_me" class="checkbox" value="1">
        Remember Me
        </form>

        <a href="index.php?main_page=password_forgotten">Forgot password?</a><BR />
        <a href="index.php?main_page=join">Sign up for an account.</a>
  
</aside>