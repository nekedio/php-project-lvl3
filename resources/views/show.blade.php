<!DOCTYPE html>
<html lang="en">
	<head>
		<!-- Required meta tags -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<!-- Bootstrap CSS -->
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">

		<title>Анализатор страниц</title>
	</head>

	<body>

		
		<header>
			
			<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
				<div class="container-fluid" role="alert">
					<a class="navbar-brand" href="{{ route('index') }}">
						<svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-binoculars" viewBox="0 0 16 16">
							<path d="M3 2.5A1.5 1.5 0 0 1 4.5 1h1A1.5 1.5 0 0 1 7 2.5V5h2V2.5A1.5 1.5 0 0 1 10.5 1h1A1.5 1.5 0 0 1 13 2.5v2.382a.5.5 0 0 0 .276.447l.895.447A1.5 1.5 0 0 1 15 7.118V14.5a1.5 1.5 0 0 1-1.5 1.5h-3A1.5 1.5 0 0 1 9 14.5v-3a.5.5 0 0 1 .146-.354l.854-.853V9.5a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5v.793l.854.853A.5.5 0 0 1 7 11.5v3A1.5 1.5 0 0 1 5.5 16h-3A1.5 1.5 0 0 1 1 14.5V7.118a1.5 1.5 0 0 1 .83-1.342l.894-.447A.5.5 0 0 0 3 4.882V2.5zM4.5 2a.5.5 0 0 0-.5.5V3h2v-.5a.5.5 0 0 0-.5-.5h-1zM6 4H4v.882a1.5 1.5 0 0 1-.83 1.342l-.894.447A.5.5 0 0 0 2 7.118V13h4v-1.293l-.854-.853A.5.5 0 0 1 5 10.5v-1A1.5 1.5 0 0 1 6.5 8h3A1.5 1.5 0 0 1 11 9.5v1a.5.5 0 0 1-.146.354l-.854.853V13h4V7.118a.5.5 0 0 0-.276-.447l-.895-.447A1.5 1.5 0 0 1 12 4.882V4h-2v1.5a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5V4zm4-1h2v-.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5V3zm4 11h-4v.5a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5V14zm-8 0H2v.5a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5V14z"/>
						</svg>
					</a>
					<a class="navbar-brand" href="{{ route('index') }}">Анализатор страниц</a>
					<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
						<span class="navbar-toggler-icon"></span>
					</button>
					<div class="collapse navbar-collapse" id="navbarNav">
						<ul class="navbar-nav">
							<li class="nav-item">
								<a class="nav-link" aria-current="page" href="{{ route('index') }}">Главная</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="{{ route('urls.index') }}">Сайты</a>
							</li>
						</ul>
					</div>
				</div>
			</nav>
		</header>

		<main>			
			<div>
				@include('flash::message')
			</div>
			<div class="container-fluid py-5">
				<h1>Сайт: {{$url->name}}</h1>
				<table class="table table-primary table-bordered border-primary">
					<tbody>
						<tr>
							<th scope="col">ID</th>
							<td scope="col">{{$url->id}}</td>
						</tr>
						<tr>
							<th scope="col">Имя</th>
							<td scope="col">{{$url->name}}</td>
						</tr>
						<tr>
							<th scope="col">Дата создания</th>
							<td scope="col">{{$url->created_at}}</td>
						</tr>
						<tr>
							<th scope="col">Дата обновления</th>
							<td scope="col">{{$url->updated_at}}</td>
						</tr>
					</tbody>
				</table>
			</div>
			@php
			$jsonUrl = json_encode($url, TRUE);
			@endphp
			<div class="container-fluid py-5">
				<h1>Проверка</h1>
				<form action="{{ route('urls.checks.index', $url->id) }}" method='post'>
					@csrf
					<button type="submit" class="btn btn-primary mb-2">Запустить проверку!</button>
				</form>
					<table class="table table-primary table-bordered border-primary align-middle">
					<thead class="align-middle text-center">
						<tr>
							<th scope="col">ID</th>
							<th scope="col">Код ответа</th>
							<th scope="col">h1</th>
							<th scope="col">keywords</th>
							<th scope="col">description</th>
							<th scope="col">Дата создания</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($url_checks ?? [] as $check)
						<tr>
							<th scope="row">{{$check->id}}</th>
							<td>{{$check->status_code}}</td>
							<td>{{$check->h1}}</td>
							<td>{{$check->keywords}}</td>
							<td>{{$check->description}}</td>
							<td>{{$check->created_at}}</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</main>

		<!-- Optional JavaScript -->
		<!-- jQuery first, then Popper.js, then Bootstrap JS -->
		<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.min.js" integrity="sha384-Atwg2Pkwv9vp0ygtn1JAojH0nYbwNJLPhwyoVbhoPwBhjQPR5VtM2+xf0Uwh9KtT" crossorigin="anonymous"></script>
	</body>
</html>
