<!-- This file is used to store sidebar items, starting with Backpack\Base 0.9.0 -->
<div class="user-panel">
  <a class="pull-left image" href="#">
    <img src="{{ backpack_avatar_url(backpack_auth()->user()) }}" class="img-circle" alt="User Image">
  </a>
  <div class="pull-left info">
    <p><a href="#">{{ backpack_auth()->user()->name }}</a></p>
    <small>
    {{ __("Roles") }}:
    {{ implode(", ",backpack_user()->getRoleNames()->all()) }}<br/>
    {{ __("Faculty") }}: 
    {{ implode(", ",backpack_user()->faculties()->pluck('name' . ( App::isLocale('fr') ? "_fr" : "" ))->toArray() ) ?? __("not found") }}
  </small>
  </div>
</div>  

<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="la la-dashboard nav-icon"></i> {{ trans('backpack::base.dashboard') }}</a></li>

<li class="nav-item nav-dropdown">
	<a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon la la-cogs"></i> Configuration</a>
	<ul class="nav-dropdown-items">
    @if (backpack_user()->hasAnyRole(['admin']) || backpack_user()->can('list Faculty'))
    <li class='nav-item'><a class='nav-link' href='{{ backpack_url('faculty') }}'><i class='nav-icon la la-building'></i> Faculties</a></li>
    @endif
	</ul>
</li> 
@if (backpack_user()->hasAnyRole(['admin']) || backpack_user()->can('list User'))
<li class="nav-item nav-dropdown">
	<a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon la la-group"></i> Authentication</a>
	<ul class="nav-dropdown-items">
	  <li class="nav-item"><a class="nav-link" href="{{ backpack_url('user') }}"><i class="nav-icon la la-user"></i> <span>Users</span></a></li>
	  @if (backpack_user()->hasAnyRole(['admin']) || backpack_user()->can('list Role'))
    <li class="nav-item"><a class="nav-link" href="{{ backpack_url('role') }}"><i class="nav-icon la la-group"></i> <span>Roles</span></a></li>
	  @endif
    @if (backpack_user()->hasAnyRole(['admin']) || backpack_user()->can('list Permission'))
    <li class="nav-item"><a class="nav-link" href="{{ backpack_url('permission') }}"><i class="nav-icon la la-key"></i> <span>Permissions</span></a></li>
    @endif
  </ul>
</li> 
@endif