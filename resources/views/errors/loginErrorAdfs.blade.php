@extends('errors.layout')

@php
  $error_number = 403;
@endphp

@section('title')
  Erreur de cookie d'authentification - Authentication cookie error.
@endsection

@section('description')
  @php
    $default_error_message = "There is a problem with the authentication, please clear your browser cache and try again";
    $default_error_message_fr = "Il y a un problème avec l'authentification, veuillez vider le cache de votre navigateur et réessayer";
  @endphp
  {!! $default_error_message_fr !!}<br/><br/>
  {!! $default_error_message !!}
@endsection