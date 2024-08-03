<div id="alert-status" class="alert bg-white mb-0 border-0 pb-3 border-0 d-flex justify-content-between align-items-center" role="alert">
	<h6 class="text-primary p-0 m-0 font-weight-bold">{{$message}}</h6>
	
	<button onclick="winReload()" class="btn btn-sm btn-primary">Close</button>
</div>
<script type="text/javascript">
	function winReload(){
		window.location.reload()
	}
</script>