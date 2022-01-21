<?php

    session_start();

        

    if (array_key_exists("logout", $_GET)) {
        
        unset($_SESSION);
        setcookie("id", "", time() - 60*60);
        $_COOKIE["id"] = "";  
        
    } 
	else if
	 
        ((array_key_exists("id", $_SESSION) AND $_SESSION['id'])
		OR (array_key_exists("id", $_COOKIE) AND $_COOKIE['id'])) {
        
        header("Location: new sd2.php");
        
    }

    if (array_key_exists("email",$_POST) OR array_key_exists('password',$_POST))
       { 
        
        $link = mysqli_connect("localhost","root","","unma");
        
        if (mysqli_connect_error())
			{
            
            die ("Database Connection Error");
            
        }
        
        
        
        if (!$_POST['email']) {
            
            echo "An email address is required<br>";
            
        } 
        
        else if (!$_POST['password']) {
            
            echo "A password is required<br>";
            
        } 
        
         else 
		 {
            
            if ($_POST['signUp'] == '1') 
			{
            
                $query = "SELECT id FROM `users` WHERE email = '"
				.mysqli_real_escape_string($link, $_POST['email'])."' LIMIT 1";

                $result = mysqli_query($link, $query);

                if (mysqli_num_rows($result) > 0) 
				{

                    echo "That email address is taken.";

                } else 
				{

                    $query = "INSERT INTO `users` (`email`, `password`) VALUES ('".mysqli_real_escape_string($link, $_POST['email'])."', '".mysqli_real_escape_string($link, $_POST['password'])."')";

                    if (!mysqli_query($link, $query)) 
					{

                        echo "<p>try again later!!</p>";

                    } 
					else 
					{

                        $query = "UPDATE `users` SET password = '".md5(md5(mysqli_insert_id($link)).$_POST['password'])."' WHERE id = ".mysqli_insert_id($link)." LIMIT 1";

                        mysqli_query($link, $query);

                        $_SESSION['id'] = mysqli_insert_id($link);

                        if ($_POST['stayLoggedIn'] == '1')
							{

                            setcookie("id", mysqli_insert_id($link), time() + 60*60*24*365);

                        } 

                        header("Location: new sd2.php");

                    }

                } 
                
            } 
			else 
			{
                    
                    $query = "SELECT * FROM `users` WHERE email = '".mysqli_real_escape_string($link, $_POST['email'])."'";
                
                    $result = mysqli_query($link, $query);
                
                    $row = mysqli_fetch_array($result);
                
                    if (isset($row))
						{
                        
                        $hashedPassword = md5(md5($row['id']).$_POST['password']);
                        
                        if ($hashedPassword == $row['password']) 
						{
                            
                            $_SESSION['id'] = $row['id'];
                            
                            if ($_POST['stayLoggedIn'] == '1')
								{

                                setcookie("id", $row['id'], time() + 60*60*24*365);

                            } 

                            header("Location: new sd2.php");
                                
                        }
						else 
						{
                            
                            echo "That email/password combination could not be found.";
                            
                        }
                        
                    } 
					else 
					{
                        
                        echo "That email/password combination could not be found.";
                        
                    }
                    
                }
            
        }
        
        
    }


?>



<form method="post">

    <input type="email" name="email" placeholder="Your Email">
    
    <input type="password" name="password" placeholder="Password">
    
    <input type="checkbox" name="stayLoggedIn" value=1>
    
    <input type="hidden" name="signUp" value="1">
        
    <input type="submit" name="submit" value="Sign Up!">

</form>

<form method="post">

    <input type="email" name="email" placeholder="Your Email">
    
    <input type="password" name="password" placeholder="Password">
    
    <input type="checkbox" name="stayLoggedIn" value=1>
    
    <input type="hidden" name="signUp" value="0">
        
    <input type="submit" name="submit" value="Log In!">

</form>