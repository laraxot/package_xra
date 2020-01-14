<ul class="nav nav-tabs">
@foreach($rows as $row)
	<li {!! $row->name==$id_testforminput?'class="active"':'' !!}><a href="{{ route('xra.testform.testforminput.edit',$row->name) }}">{{ $row->name }}</a></li>
@endforeach
</ul>