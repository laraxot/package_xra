@extends('adm_theme::layouts.app')
@section('page_heading','cerca')
@section('section')
@include('backend::includes.flash')
@include('backend::includes.components')
<pre>{{ $title }}</pre>
<?php
$routename=Request::route()->getName();
?>
{{-- $routename --}}
{{ Form::open(['route' => $routename]) }}
{{ method_field('POST') }}
{!! csrf_field() !!}
{{-- Form::bsText('ente',$row->ente) --}}
{{ Form::bsText('anno',$row->anno) }}
{{ Form::bsSubmit('vai') }}
{!! Form::close() !!}
@endsection