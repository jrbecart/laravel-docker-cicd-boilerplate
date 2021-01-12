<li class="nav-item">
  @if (!App::isLocale('fr')) <i class="nav-icon la la-check-circle"></i>English @else <a class="nav-link" href="{{ url(config('backpack.base.home_link')) }}/lang/en"><i class="nav-icon la la-circle"></i>English </a> @endif 
</li>
<li>&nbsp;</li>
<li class="nav-item">
  @if (App::isLocale('fr')) <i class="nav-icon la la-check-circle"></i>Français @else <a class="nav-link" href="{{ url(config('backpack.base.home_link')) }}/lang/fr"><i class="nav-icon la la-circle"></i>Français </a> @endif 
</li> 
<li class="nav-item dropdown pr-4">
  <a class="nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
    <img class="img-avatar" src="{{ backpack_avatar_url(backpack_auth()->user()) }}" alt="{{ backpack_auth()->user()->name }}">
  </a>
  <div class="dropdown-menu dropdown-menu-right mr-4 pb-1 pt-1">
    <!--<div class="dropdown-divider"></div>-->
    <a class="dropdown-item" href="{{ url('/logout') }}"><i class="la la-lock"></i> {{ trans('backpack::base.logout') }}</a>
  </div>
</li>
