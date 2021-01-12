@extends('errors.layout')

@php
  $error_number = 234;
@endphp

@section('title')
  La clé est incorrect - Key is not valid.
@endsection

@section('description')
  @php
    $default_error_message = "Sorry you can't accept ou reject this request. You can login to check this request.";
    $default_error_message_fr = "Désolé, vous ne pouvez pas accepter ou refuser cette demande. Vous pouvez vous connecter pour vérifier cette demande.";
  @endphp
  {!! $default_error_message_fr !!}
  <br/>
  {!! $default_error_message !!}
  <br/>
  <a href="{{ url('/') }}">{{ url('/') }}</a>
@endsection