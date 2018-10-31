@extends('adm_theme::layouts.app')
@section('page_heading','package xra')
@section('content')
@include('backend::includes.flash')
@include('backend::includes.components')

{!! Form::bsOpen($row,'store') !!}

{{ Form::bsText('fb_messenger_id') }} 
{{ Form::bsTextarea('msg') }} 


{{ Form::bsSubmit('invia') }}
{!! Form::close() !!}

@endsection
