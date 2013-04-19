<?
if(isset($_GET["signout"])){
	setcookie("projectal_let_me_in", "", time()-3600);
	?>
    You have just been logged out, and will get the "under Construction" screen if you go to the ProjectAl home page.
    
    <p>[<a href="let_me_in.php">Sign back In</a>]</p>
	
	<?
} 



else {
	setcookie("projectal_let_me_in", true, time()+3600);  /* expire in 1 hour */	
	?>
   
    You should now be able to access the ProjectAl development site. Your access will be retained for one hour.<BR>
    <br />
    Go To <a href="/"><strong>ProjectAl Home Page</strong></a></p>

    
    <p>[<a href="?signout">Sign Out</a>]</p>
    <?
}

?>
<div style="position:absolute;bottom:10px;right:10px;color:#999;">Access Granted = <?php echo $_COOKIE["projectal_let_me_in"]; ?></div>
    