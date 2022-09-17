<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tune Test</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
</head>
<body>

<nav class="navbar navbar-expand-lg bg-primary">
    <div class="container-fluid">

        <a class="navbar-brand text-white" href="#">User Dashboard</a>
        <div class="collapse navbar-collapse" id="navbarTogglerDemo03">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">

            </ul>
            <form class="d-flex" role="search">
                <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                <button class="btn btn-primary" type="submit">Search</button>
            </form>
        </div>
    </div>
</nav>

<div class="container">
    <div class="form-group mt-2">
        <form method="GET" class="d-flex" role="search">
            <button type="submit" name="sort_by" value="name" class="btn btn-primary">Sort By Name</button>&nbsp;&nbsp;
            <button type="submit" name="sort_by" value="revenue" class="btn btn-secondary">Sort By Revenue</button>&nbsp;&nbsp;
            <button type="submit" name="sort_by" value="conversion" class="btn btn-primary">Sort By Conversion</button>&nbsp;&nbsp;
            <button type="submit" name="sort_by" value="impression" class="btn btn-secondary">Sort By Impressions</button>&nbsp;&nbsp;
        </form>
    </div>
    <div class="row mt-2">
        @foreach($users as $user)
            <div class="col-md-6 col-xl-4 mt-2">
                <div class="card m-b-30">
                    <div class="card-body row m-0 pb-0">
                        <div class="col-3">
                            @if ($user['avatar'] != '')
{{--                                <a href=""><img src="{{ $user['avatar'] }}" alt="T" class="img-fluid rounded-circle w-60"></a>--}}
                                <a href=""><img src="https://ui-avatars.com/api/?name={{ $user['name'] }}" alt="T" class="img-fluid rounded-circle w-60"></a>
                            @else
                                <a href=""><img src="https://ui-avatars.com/api/?name={{ $user['name'] }}" alt="T" class="img-fluid rounded-circle w-60"></a>
                            @endif
                        </div>
                        <div class="col-9 card-title align-self-center mb-0">
                            <h5>{{ $user['name'] }}</h5>
                            <p class="m-0">{{ $user['occupation'] }}</p>
                        </div>
                    </div>

                    <div class="card-body row">
                        <div class="col-9">
                            <canvas id="chart-{{ $user['id'] }}" style="width:200%;max-width:900px"></canvas>
                        </div>
                        <div class="col-3 d-flex align-items-end flex-column">
                            <div style="font-weight:bold;">{{ $user['impression'] }}</div>
                            <div>Impression</div>
                            <div style="font-weight:bold;">{{ $user['conversion'] }}</div>
                            <div>Conversion</div>
                            <div style="font-weight:bold;">{{ '$'.$user['revenue'] }}</div>
                            <div>Revenue</div>
                        </div>
                    </div>

                    <div class="card-body row pt-0">
                        <div>Conversions 4/12 - 4/30</div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="row mt-5">
        <nav aria-label="Page navigation example">
            <ul class="pagination justify-content-center">
                {{ $users->appends(request()->query())->links() }}
            </ul>
        </nav>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
<script>
    const users = '<?= rawurlencode(json_encode($users)); ?>';
    const users_data = JSON.parse(decodeURIComponent(users)).data;

    for (const key of Object.keys(users_data)) {
        const graph_data = users_data[key].graph_data;
        const labels = Object.keys(graph_data).map(function (k) {
            return k
        }).join(",");
        const data = Object.keys(graph_data).map(function (k) {
            return graph_data[k]
        }).join(",");
        console.log(labels);
        console.log(data);
        new Chart('chart-' + users_data[key].id, {
            type: "line",
            data: {
                labels: labels,
                datasets: [{
                    fill: false,
                    lineTension: 0,
                    backgroundColor: "rgba(0,0,255,1.0)",
                    borderColor: "rgba(0,0,255,0.1)",
                    data: data
                }]
            },
            options: {
                legend: {display: false},
                scales: {
                    yAxes: [{ticks: {min: 0, max: 15}}],
                }
            }
        });
    }
</script>
</body>
</html>
