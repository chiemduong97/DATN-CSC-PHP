<?php 
    session_start();
    session_unset();
    session_destroy();

    echo '<center>Đang chuyển tới trang đăng nhập...</center>';
    header('Refresh: 2; url= login.php');
    
?>