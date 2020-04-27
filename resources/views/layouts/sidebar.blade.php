<nav id="sidebar" class="navbar-default navbar-static-side" role="navigation">
  <div class="sidebar-collapse">
  <ul class="nav metismenu" id="side-menu">
    <li class="nav-header" slot="header" style="padding: 10px 25px!impoirtant;">
      <div class="dropdown profile-element">
        <span>
          <img alt="image" class="" id="imgProfile" src="{{ asset('storage/') }}/{{ Session::get('logo') }}"
            style="width: 170px;">
        </span>
        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
          <span class="clear">
            <span class="block m-t-xs">
              <strong class="font-bold">
                {{ Auth::user()->name }}
              </strong>
              <br>
              <strong class="font-bold" id="_agencia">
                {{ Session::get('agencia') }}
              </strong>
              </br>
            </span>
            <span class="text-muted text-xs block">
              @lang('layouts.welcome')
              <b class="caret"></b>
            </span>
          </span>
        </a>
        <ul class="dropdown-menu animated fadeInRight m-t-xs">
          <li>
            <a href="#">
              <a href="{{ route('home') }}">
                <i class="fal fa-home"></i>
                @lang('layouts.home')
              </a>
          </li>
          <li>
            <a href="/agencia/{{ base64_encode(Auth::user()->agencia_id) }}/edit">
              <i class="fal fa-user"></i>
              @lang('layouts.profile')
            </a>
          </li>
        </ul>
      </div>
      <div class="logo-element">4plbox</div>
    </li>
  </ul>

    <sidebar-component>
  </div>
</nav>