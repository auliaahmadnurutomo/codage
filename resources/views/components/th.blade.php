<th 
	id="{{ isset($static) ? ($static ? '' : 'orderType') : 'orderType' }}" 
	class="{{ isset($static) ? ($static ? '' : (isset($first) ? 'bg-light asc' : 'desc')) : (isset($first) ? 'bg-light asc' : 'desc') }} font-weight-bold" 
	data-order="{{ isset($first) ? 'desc' : 'asc' }}" 
	data-column="{{$col}}"
	style="min-width: {{$mw}};">
		{{$title}}
</th>
