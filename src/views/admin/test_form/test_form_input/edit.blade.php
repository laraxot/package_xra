@extends('adm_theme::layouts.app')
@section('page_heading','test input form')
@section('section')
@include('backend::includes.flash')
@include('backend::includes.components')
	@include($view.'.nav')
	{!! Form::$id_testforminput('test') !!}

@endsection
