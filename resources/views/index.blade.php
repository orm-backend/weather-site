<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
<meta charset="utf-8">
<meta name="viewport"
	content="width=device-width, initial-scale=1, shrink-to-fit=no">
<!-- CSRF Token -->
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>{{ config('app.name') }}</title>
<!-- Scripts -->
<script src="{{ asset('js/app.js') }}" defer></script>
<script src="{{ asset('js/index.js') }}" defer></script>
<!-- Styles -->
<link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
	<nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
		<div class="container">
			<a class="navbar-brand" href="{{ url('/') }}">{{ config('app.name')
				}}</a>
		</div>
	</nav>
	<main class="py-4">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-md-8 col-md-offset-2">
					<div id="weather" class="panel-body">
						<weather-form></weather-form>
					</div>
					<table class="table table-bordered table-striped">
						<thead>
							<tr>
								<th class="text-center">#</th>
								<th class="text-center">Дата</th>
								<th class="text-center">Температура ℃</th>
							</tr>
						</thead>
						<tbody>
							@if ($errors)
							<tr>
								<td colspan="3">
									<div class="alert alert-danger">
										@foreach ($errors as $error)
										<p class="mb-0">{{ $error }}</p>
										@endforeach
									</div>
								</td>
							</tr>
							@else
							@foreach ($histories as $history)
							<tr>
								<td>{{ $history->getId() }}</td>
								<td>{{ $history->getdateAt()->formatLocalized('%d %B %Y') }}</td>
								<td>{{ $history->getTemp() }}</td>
							</tr>
							@endforeach
							@endif
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</main>
</body>
</html>
