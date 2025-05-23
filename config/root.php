/* Graph */
$router->add('GET', '/graph/line',        fn() => (new GraphController)->View());
$router->add('GET', '/graph/income-data', fn() => (new GraphController)->income());
$router->add('GET', '/graph/expend-data', fn() => (new GraphController)->expenditure());
