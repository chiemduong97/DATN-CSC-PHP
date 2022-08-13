<?php 
    include('header.php'); 
?>
<!DOCTYPE html>
<html>
<body>
    
    
    <!-- MENU SECTION END-->
    <div class="content-wrapper">
        <div class="container">
            <div class="row pad-botm">
                <div class="col-md-12">
                    <h4 class="header-line">ADMIN DASHBOARD</h4>

                </div>

            </div>

            <div class="row">

                <div class="col-md-3 col-sm-3 col-xs-6">
                    <div class="alert alert-info back-widget-set text-center">
                        <i class="fa fa-bars fa-5x"></i>
                        <h3>{{countCategory}}&nbsp; Category</i></h3>
                    </div>
                </div>
                <div class="col-md-3 col-sm-3 col-xs-6">
                    <div class="alert alert-success back-widget-set text-center">
                        <i class="fa fa-hacker-news fa-5x"></i>
                        <h3>{{countNews}}&nbsp; News</h3>
                    </div>
                </div>
                <div class="col-md-3 col-sm-3 col-xs-6">
                    <div class="alert alert-warning back-widget-set text-center">
                        <i class="fa fa-user fa-5x"></i>
                        <h3>{{countUser}}&nbsp; User</h3>
                    </div>
                </div>
                <div class="col-md-3 col-sm-3 col-xs-6">
                    <div class="alert alert-danger back-widget-set text-center">
                        <i class="fa fa-eye fa-5x"></i>
                        <h3>{{countView.[0].sum}} View </h3>
                    </div>
                </div>

            </div>
            

        </div>
    </div>
    <!-- CONTENT-WRAPPER SECTION END-->
    <section class="footer-section">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    &copy; 2014 Yourdomain.com |<a href="http://www.binarytheme.com/" target="_blank"> Designed by : binarytheme.com</a>
                </div>

            </div>
        </div>
    </section>
   
</body>
</html>