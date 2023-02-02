<div class="mt-10">
	<div class="pull-left font-montserrat">
		Displaying {{$resultData->count()+$page}} of {{ $resultData->total() }} data(s).
	</div>
	<div class="pull-right">
		{{$resultData->appends(Request::except('page'))->render()}}
	</div>
</div>