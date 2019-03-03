<!doctype html>
<!--[if lt IE 7]>
<html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>
<html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>
<html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js" lang=""> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>MSSE661</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="css/bootstrap.min.css">
    <!--        <link rel="stylesheet" href="css/bootstrap-theme.min.css">-->


    <!--For Plugins external css-->
    <link rel="stylesheet" href="css/plugins.css"/>
    <link rel="stylesheet" href="css/lora-web-font.css"/>
    <link rel="stylesheet" href="css/opensans-web-font.css"/>
    <link rel="stylesheet" href="css/magnific-popup.css">

    <link rel="stylesheet" href="css/login.css" />
    <link rel="stylesheet" href="css/tag.css" />
    <link rel="stylesheet" href="css/content-upload.css" />
    <link rel="stylesheet" href="css/user.css" />

    <!--Theme custom css -->
    <link rel="stylesheet" href="css/style.css"/>

    <!--Theme Responsive css-->
    <link rel="stylesheet" href="css/responsive.css"/>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/respond.js/1.4.2/respond.min.js"></script>

</head>
<body data-spy="scroll" data-target="#main_navbar">
<!--[if lt IE 8]>
<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade
    your browser</a> to improve your experience.</p>
<![endif]-->
<div class='preloader'>
    <div class='loaded'>&nbsp;</div>
</div>
<nav class="navbar navbar-default navbar-fixed-top" id="main_navbar">
    <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                    data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="index.php"><img src="images/logo.png" alt="logo"/></a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav navbar-right">
                <li><a href="#home">Home</a></li>
                <li><a href="#portfolio">Content</a></li>
                <li><a href="#testimonial">Testimonials</a></li>
                <li><a href="#contact">Contact</a></li>
                <li><a href="#" id="user-profile" class="hide">Profile</a></li>
                <li><a href="#" id="user-login" class="hide">Login / Register</a></li>
                <li><a href="#" id="user-logout" class="hide">Logout</a></li>
            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>

<!--Home page style-->
<header id="home" class="home">
    <div class="overlay">
        <div class="container">
            <div class="row">
                <div class="home-wrapper">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="home-content text-center">
                            <h1>PIANo</h1>
                            <h4>Publication Information Archive Nanosite</h4>
                            <a href="#" id="publish" class="btn btn-primary">Publish Now</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>


<section id="portfolio" class="portfolio lightbg sections">
    <div class="container">
        <div class="heading text-center">
            <h1>Content</h1>
            <div class="separator"></div>
        </div>
        <div class="row">
            <div class="main_portfolio whitebackground">
                <div class="portfolio_content text-center">
                    <div class="portfolio_menu">
                        <?php print $tag_content; ?>
                    </div>
                    <div class="portfolio_content_details">
                        <?php print $data; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section><!-- End of portfolio-one Section -->

<!-- Sections -->
<section id="content-full" class="content-full sections">
    <div id="content-container">
    </div> <!-- /container -->
</section>


<!--Footer-->
<footer id="footer" class="footer">
    <div class="container">

        <div class="scroll-top">

            <div class="scrollup">
                <i class="fa fa-angle-up"></i>
            </div>

        </div>
        <div class="row">
            <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="copyright">
                    <p>&copy; 2016 All Rights Reserved.</p>
                </div>
            </div>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="social text-right">
                    <a target="_blank" href="#"><i class="fa fa-facebook"></i></a>
                    <a target="_blank" href="#"><i class="fa fa-twitter"></i></a>
                    <a target="_blank" href="#"><i class="fa fa-instagram"></i></a>
                    <a target="_blank" href="#"><i class="fa fa-linkedin"></i></a>
                </div>
            </div>
        </div>
    </div>
</footer>

<div id="dynamic">
    <div id="register-login-form-wrapper"></div>
    <div id="content-upload-form-show-hide"></div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.isotope/3.0.6/isotope.pkgd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/jquery.magnific-popup.min.js"></script>
<script src="js/user.js"></script>
<script src="js/tags.js"></script>
<script src="js/content.js"></script>
<script src="js/main.js"></script>
<script src="js/jquery.fileupload.js"></script>
</body>
</html>
