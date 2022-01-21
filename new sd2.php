<?php

    session_start();
    //$diaryContent="";

    if (array_key_exists("id", $_COOKIE) && $_COOKIE ['id']) {
        
        $_SESSION['id'] = $_COOKIE['id'];
        
    }

    if (array_key_exists("id", $_SESSION)) {
              
      include("connection.php");
      
      $query = "SELECT diary FROM `users` WHERE id = ".mysqli_real_escape_string($link, $_SESSION['id'])." LIMIT 1";
      $row = mysqli_fetch_array(mysqli_query($link, $query));
      $diaryContent = $row['diary'];
      
    } else {
        
        header("Location: new sd.php");
        
    }

	include("header.php");

?>
<nav class="navbar navbar-light bg-faded navbar-fixed-top" id="top-bar">
  

  <a class="navbar-brand" id="top-element" href="#"> <h2 style="color:white; font-family:Segoe Script">Secret Diary</h2></style></a>
   
   <div class="pull-xs-right">
      <a href ='new sd.php?logout=1'>
        <button class="btn btn-success" type="submit">
 Logout</button></a>
    </div>

</nav>



    <div class="container-fluid" id="containerLoggedInPage">

        <textarea id="diary" class="form-control" rows="21" cols="150"  ><?php echo $diaryContent; ?></textarea>
    </div>
<?php
    
    include("footer.php");
?>