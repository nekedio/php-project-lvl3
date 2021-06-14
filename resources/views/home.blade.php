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
					<a class="navbar-brand" href="/">
						<svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-binoculars" viewBox="0 0 16 16">
							<path d="M3 2.5A1.5 1.5 0 0 1 4.5 1h1A1.5 1.5 0 0 1 7 2.5V5h2V2.5A1.5 1.5 0 0 1 10.5 1h1A1.5 1.5 0 0 1 13 2.5v2.382a.5.5 0 0 0 .276.447l.895.447A1.5 1.5 0 0 1 15 7.118V14.5a1.5 1.5 0 0 1-1.5 1.5h-3A1.5 1.5 0 0 1 9 14.5v-3a.5.5 0 0 1 .146-.354l.854-.853V9.5a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5v.793l.854.853A.5.5 0 0 1 7 11.5v3A1.5 1.5 0 0 1 5.5 16h-3A1.5 1.5 0 0 1 1 14.5V7.118a1.5 1.5 0 0 1 .83-1.342l.894-.447A.5.5 0 0 0 3 4.882V2.5zM4.5 2a.5.5 0 0 0-.5.5V3h2v-.5a.5.5 0 0 0-.5-.5h-1zM6 4H4v.882a1.5 1.5 0 0 1-.83 1.342l-.894.447A.5.5 0 0 0 2 7.118V13h4v-1.293l-.854-.853A.5.5 0 0 1 5 10.5v-1A1.5 1.5 0 0 1 6.5 8h3A1.5 1.5 0 0 1 11 9.5v1a.5.5 0 0 1-.146.354l-.854.853V13h4V7.118a.5.5 0 0 0-.276-.447l-.895-.447A1.5 1.5 0 0 1 12 4.882V4h-2v1.5a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5V4zm4-1h2v-.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5V3zm4 11h-4v.5a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5V14zm-8 0H2v.5a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5V14z"/>
						</svg>
					</a>
					<a class="navbar-brand" href="/">Анализатор страниц</a>
					<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
						<span class="navbar-toggler-icon"></span>
					</button>
					<div class="collapse navbar-collapse" id="navbarNav">
						<ul class="navbar-nav">
							<li class="nav-item">
								<a class="nav-link active" aria-current="page" href="/">Главная</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="/urls">Сайты</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="#">Pricing</a>
							</li>
							<li class="nav-item">
								<a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Disabled</a>
							</li>
						</ul>
					</div>
				</div>
			</nav>
		</header>

		<main>
	
			@if ($errors->any())
			<div class="alert alert-danger">
				@foreach ($errors->all() as $error)
				{{ $error }}
				@endforeach
			</div>
			@endif

		
			<div class="container-fluid bg-dark text-white py-5">
				<div class="row">
					<div class="col-sm-2 col-md-2 col-lg-2 col-xl-4"></div>
					<div class="col-sm-8 col-md-8 col-lg-8 col-xl-4">
						<div class="flex">
							<h1 class="display-4">Анализатор страниц</h1>
							<p>Анализ сайта на SEO пригодность</p>
							<form action="/add" method='post'>
								@csrf
								<div class="d-grid">
									<input name="url[name]" type="text" class="mb-3 form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="https://www.your.website.com">
									<button type="submit" class="btn btn-warning">Отправить сайт на анализ!</button>
								</div>
							</form>
						</div>
					</div>
					<div class="col-sm-2 col-md-2 col-lg-8 col-xl-4"></div>
				</div>
			</div>
			<section>
				<h1>&lt;decription&gt;</h1>
				<p>Содержимое метатега description является <a href="https://yandex.ru/support/webmaster/search-results/site-description.html#changing-header-description">одним из источников</a>, на основе которых формируются описания страниц сайта в результатах поиска Яндекса. В сниппет попадает наиболее информативный и релевантный поисковому запросу текст. Кроме этого, описание может формироваться на основе содержимого страницы, поэтому пользователи могут увидеть разный контент — в зависимости от поискового запроса.</p>
				<p>Назначение метаописания — дать пользователю более полную информацию о странице и побудить перейти на нее. Поэтому в description следует добавить полезный и привлекательный текст. Длина описания ограниченна шириной экрана устройства, которое использует пользователь.</p>
				<p>Обычно метатег размещается внутри элемента head:
				<pre><code>&lt;head&gt;
&lt;meta name="description" content="..."/&gt;
&lt;/head&gt;
				</code></pre></p>
				<p>При составлении description следуйте рекомендациям:

				<ul><li>Добавляйте метатег на все страницы сайта.</ul>
				<ul><li>Создавайте уникальное описание для каждой страницы. Шаблонные или похожие описания, вероятнее всего, не будут показываться в результатах поиска, так как они не несут существенной информации.</ul>
				<ul><li>Убедитесь, что description отражает содержимое страницы, содержит правильно выстроенные предложения, без злоупотребления ключевыми словами, фразами, заглавными буквами, рекламными лозунгами и пр.</ul>
				<ul><li>В первую очередь добавляйте описания для более важных страниц сайта, например страниц каталога.</ul>
				<ul><li>Включайте в описание полезную для пользователя информацию, например, цену товара, его цвет, город доставки и т. д.</ul>
				<ul><li>Проверьте, что описание соответствует языку страницы.</ul>
				<ul><li>Убедитесь, что description отличается от содержимого элемента title.</ul>
			</section>
		</main>







		<!-- Optional JavaScript -->
		<!-- jQuery first, then Popper.js, then Bootstrap JS -->
		<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.min.js" integrity="sha384-Atwg2Pkwv9vp0ygtn1JAojH0nYbwNJLPhwyoVbhoPwBhjQPR5VtM2+xf0Uwh9KtT" crossorigin="anonymous"></script>



	
	
	</body>
</html>
