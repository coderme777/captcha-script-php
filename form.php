<?php
require_once 'captcha.php';
$ca = Captcha::check($_POST['captcha']);
if (isset($_POST['check_now'])) {
	if ($ca) echo 'Код введен верно!';
	else {
		echo 'Введите правильный проверочный код.';
	}	
}

    if (isset($_POST['check'])) {
        if (Captcha::check($_POST['captcha'])) echo 'Проверочный код введён верно!';
        else echo 'Проверочный код введён неверно!';
    }
?>

<form name='no_robot' action='form.php' method='post'>
	<p>
		<input type='text' name='captcha' placeholder='введите код'>
	</p>
	<p>
		<img src='cap.php' alt='' />
	</p>
	<p>
		<input type='submit' name='check_now' value='Проверить'>
	</p>
</form>
