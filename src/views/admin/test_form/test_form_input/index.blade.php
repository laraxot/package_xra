@extends('adm_theme::layouts.app')
@section('page_heading','test input form')
@section('section')
@include('backend::includes.flash')
@include('backend::includes.components')

<table class="table">
	@foreach($rows as $k=>$row)
	<tr>
		<td>{{ $k+1 }}</td>
		<td>{{ $row->name }}</td>
		<td><a href="{{ route('xra.testform.testforminput.edit',$row->name) }}" class="btn btn-primary"><i class="fa fa-pencil"></i></a></td>
	</tr>
	@endforeach
</table>

@endsection