<form name="_xclick" action="https://www.paypal.com/cgi-bin/webscr" 
    method="post">
    <input type="hidden" name="cmd" value="_xclick">
    <input type="hidden" name="business" value="silkroadnonprofit@gmail.com">
    <input type="hidden" name="currency_code" value="USD">
	<?php print('<input type="hidden" name="item_name" value="Donations for '.$row['Name'].'">'); ?>
	<?php print('<input type="hidden" name="item_number" value="'.$cid.'">'); ?>
	<?php 
	if(isset($_SESSION['logged']) && !empty($_SESSION['logged']))
		print('<input type="hidden" name="custom" value="'.$_SESSION['logged'].'">');
	else
		print('<input type="hidden" name="custom" value="-1">');

		?>
	
    <input type="hidden" name="return" value="http://info230.cs.cornell.edu/users/dorayakis/www/Jirex/Success.php">
    <input type="hidden" name="notify_url" value="http://info230.cs.cornell.edu/users/dorayakis/www/Jirex/ipn.php">
    <input type="image" src="http://www.paypal.com/en_US/i/btn/btn_donate_LG.gif" 
        border="0" name="submit" alt="Make payments with PayPal - it's fast, free and secure!">
</form>