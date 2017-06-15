@extends('layouts.app')

@section('content')
	<div class="container">
		<div class="row">
			<div class="cool-md-12">
				<ul class="breadcrumb">
					<li><a href="{{ url('/home') }}">Dashboard</a></li>
						<li class="active">penulis</li>
					</ul>
					<div class="panel panel-default">
						<div class="panel-heading">
							<h2 class="active">Penulis</h2>
						</div>

						<div class="panel-body">
						{!! $html->table(['class'=>'table-striped']) !!}
						</div>
					</div>
				</div>
			</div>
		</div>
		@endsection 
			@section('scripts')
				{!! $html->scripts() !!}
				@endsection