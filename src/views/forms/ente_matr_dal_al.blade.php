@extends('adm_theme::layouts.app')
@section('page_heading','cerca')
@section('section')
@include('backend::includes.flash')
@include('backend::includes.components')
<pre>{{ $caption }}</pre>
<?php
$routename=Request::route()->getName();
?>
{{-- $routename --}}
{{ Form::open(['route' => $routename]) }}
{{ method_field('POST') }}
{!! csrf_field() !!}
{{-- Form::bsText('ente',$row->ente) --}}
{{ Form::bsText('ente',$row->ente) }}
{{ Form::bsText('matr',$row->matr) }}
{{ Form::bsDate('dal') }}
{{ Form::bsDate('al') }}
{{ Form::bsSubmit('vai') }}
{!! Form::close() !!}
@endsection