<div class="wrapper">

    <nav id="sidebar">
        <!-- Sidebar Header -->
        <div class="sidebar-header">
            <h3>Dashboard</div>
        </div>

        <!-- Sidebar Links -->
        <ul class="list-unstyled components">
            <li class="active"><a href="#">Home</a></li>
            <li><a href="#">About</a></li>

            <li><!-- Link with dropdown items -->
                <a href="#homeSubmenu" data-toggle="collapse" aria-expanded="false">Pages</a>
                <ul class="collapse list-unstyled" id="homeSubmenu">
                    <li><a href="#">Page</a></li>
                    <li><a href="#">Page</a></li>
                    <li><a href="#">Page</a></li>
                </ul>

            <li><a href="{{ route('home') }}">
                        <i class="fa fa-area-chart"></i>
                        <span class="nav-label">Dashboard</span>
                    </a></li>
            <li><a href="#">Contact</a></li>
        </ul>
    </nav>

</div>