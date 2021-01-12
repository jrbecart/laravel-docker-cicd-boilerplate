@extends('errors.layout')

@php
  $error_number = 123;
@endphp

@section('title')
  --- TEST --- Pas d'accès - No access --- TEST ---.
@endsection

@section('description')
  @php
    $default_error_message = "You don't have access to this page [TEST]. <br> Please <a href='javascript:history.back()'>go back</a> or return to <a href='".url('')."'>the homepage</a>.";
    $default_error_message_fr = "Vous n'avez pas accès à cette page [TEST]. <br> S'il vous plait <a href='javascript:history.back()'>retourner</a> ou allez à <a href='".url('')."'>la page d'acceuil</a>.";
  @endphp
  {!! $default_error_message_fr !!}<br/><br/>
  {!! $default_error_message !!}
@endsection