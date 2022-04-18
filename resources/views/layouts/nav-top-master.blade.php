<div class="row border-bottom">
  <nav class="navbar navbar-static-top white-bg" role="navigation" style="margin-bottom: 0">
    <div class="navbar-header">
      <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>
    </div>
    <ul class="nav navbar-top-links navbar-right">
      <li>
        <span class="m-r-sm text-muted welcome-message">Welcome to AJI<b>MIS</b> - AJI Manufacturing Integration System</span>
      </li>
      <li class="dropdown">
        <a class="dropdown-toggle count-info" data-toggle="dropdown" href="#">
          <i class="fa fa-bell"></i>  
          <!-- <span class="label label-primary">8</span> -->
        </a>
        <ul class="dropdown-menu dropdown-alerts">
          <li>
            
          </li>
        </ul>
      </li>

      @auth
      <li>
        <a href="{{ route('logout.perform') }}">
          <i class="fa fa-sign-out"></i> Log out
        </a>
      </li>
      @endauth
      @guest
      <li>
        <a href="{{ route('login.perform') }}">
          <i class="fa fa-sign-in"></i> Log in
        </a>
      </li>
      @endguest
    </ul>

  </nav>
</div>