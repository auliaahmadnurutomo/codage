<div id="alert-status" class="alert alert-danger mb-0" role="alert">
	{{$message}}
	<button onclick="winReload()" class="btn btn-sm btn-danger float-right" aria-label="Close">Reload</button>
</div>

<script type="text/javascript">
	function winReload(){
		window.location.reload()
	}
</script>