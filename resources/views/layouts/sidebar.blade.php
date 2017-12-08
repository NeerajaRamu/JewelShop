<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="keywords" content="Pooled Responsive web template, Bootstrap Web Templates, Flat Web Templates, Android Compatible web template,
          Smartphone Compatible web template, free webdesigns for Nokia, Samsung, LG, SonyEricsson, Motorola web design" />
    <script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
    <!-- Bootstrap Core CSS -->

    <!--<link href="http://localhost:8003/assets/css/bootstrap.min.css" rel='stylesheet' type='text/css' />-->
    <script src="../../assets/js/jquery-2.1.4.min.js"></script>

    <!-- Custom CSS -->
    <link href="../../assets/css/style.css" rel='stylesheet' type='text/css' />
    <link rel="stylesheet" href="../assets/css/morris.css" type="text/css"/>
    <!-- Graph CSS -->
    <link href="../../assets/css/font-awesome.css" rel="stylesheet">
    <!-- jQuery -->
    <script src="//code.jquery.com/jquery-1.10.2.js"></script>
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
    <script src="../../assets/js/jquery-2.1.4.min.js"></script>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    <!-- //jQuery -->
    <link href='//fonts.googleapis.com/css?family=Roboto:700,500,300,100italic,100,400' rel='stylesheet' type='text/css'/>
    <link href='//fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
    <!-- lined-icons -->
    <link rel="stylesheet" href="../assets/css/icon-font.min.css" type='text/css' />
    <!-- //lined-icons -->
</head>
<body>

    <!--/sidebar-menu-->
    <div class="sidebar-menu">
        <header class="logo1">
            <a href="#" class="sidebar-icon"> <span class="fa fa-bars"></span> </a>
        </header>
        <div style="border-top:1px ridge rgba(255, 255, 255, 0.15)"></div>
        <div class="menu">
            <ul id="menu" >
                <li><a href="{{ route('home') }}"><i class="fa fa-tachometer"></i> <span>Dashboard</span><div class="clearfix"></div></a></li>


                <li id="menu-academico" ><a href="{{ route('profile') }}"><i class="fa fa-envelope nav_icon"></i><span>My Profile</span><div class="clearfix"></div></a></li>

                <li id="menu-academico" ><a href="#"><i class="fa fa-picture-o" aria-hidden="true"></i><span>Users</span> <span class="fa fa-angle-right" style="float: right"></span><div class="clearfix"></div></a>
                    <ul id="menu-academico-sub" >
                        <li id="menu-academico-avaliacoes" ><a href="{{ route('users') }}">View Users</a></li>
                        @if (\Auth::User()->name == "Supervisor")
                        <li id="menu-academico-avaliacoes" ><a href="{{ route('users.create') }}">Create User</a></li>
                        @endif
                    </ul>
                </li>

                <li id="menu-academico" ><a href="#"><i class="fa fa-bar-chart" aria-hidden="true"></i><span>Sales</span> <span class="fa fa-angle-right" style="float: right"></span><div class="clearfix"></div></a>
                    <ul id="menu-academico-sub" >
                        <li id="menu-academico-avaliacoes" ><a href="{{ route('create-sale') }}">Create Sale</a></li>
                        <li id="menu-academico-avaliacoes" ><a href="{{ route('my-sales') }}">My Sales</a></li>
                        <li id="menu-academico-avaliacoes" ><a href="{{ route('shop-sales') }}">Shop Sales</a></li>
                    </ul>
                </li>
                <li><a href="{{ route('timesheet') }}"><i class="fa fa-tachometer"></i> <span>Timesheet</span><div class="clearfix"></div></a></li>
                <li><a href="{{ route('clock-out') }}"><i class="fa fa-tachometer"></i> <span>Clock Out</span><div class="clearfix"></div></a></li>
                <li id="menu-academico" ><a href="#"><i class="fa fa-bar-chart" aria-hidden="true"></i><span>Sales Trends</span> <span class="fa fa-angle-right" style="float: right"></span><div class="clearfix"></div></a>
                    <ul id="menu-academico-sub" >
                        <li id="menu-academico-avaliacoes" ><a href="{{ route('region-shop-sales') }}">Graph</a></li>
                        <li id="menu-academico-avaliacoes" ><a href="{{ route('chartjs') }}">Bar Chart</a></li>
                    </ul>
                </li>
<!--                <li><a href="{{ route('all-sales') }}"><i class="fa fa-tachometer"></i> <span>All Sales</span><div class="clearfix"></div></a></li>-->
            </ul>
        </div>
    </div>
</body>