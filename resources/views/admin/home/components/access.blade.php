<script>
    const ctx = document.getElementById('visitors-chart');
    if (ctx) {
        ctx.getContext('2d');
        const myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ({!! json_encode($chart->labels) !!}),
                datasets: [{
                    label: 'Acessos por horário',
                    data: {!! json_encode($chart->dataset) !!},
                    borderWidth: 1,
                    borderColor: '#024BA9',
                    backgroundColor: 'transparent'
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                legend: {
                    labels: {
                        boxWidth: 0,
                    }
                },
            },
        });

        let getData = function() {

            $.ajax({
                url: "{{ route('admin.home.chart') }}",
                type: "GET",
                success: function(data) {
                    myChart.data.labels = data.chart.labels;
                    myChart.data.datasets[0].data = data.chart.dataset;
                    myChart.update();
                    $("#onlineusers").text(data.onlineUsers);
                    $("#accessdaily").text(data.access);
                    $("#percentvalue").text(data.percent);
                    const percentclass = $("#percentclass");
                    const percenticon = $("#percenticon");
                    percentclass.removeClass('text-success');
                    percentclass.removeClass('text-danger');
                    percenticon.removeClass('fa-arrow-up');
                    percenticon.removeClass('fa-arrow-down');
                    if (parseInt(data.percent) > 0) {
                        percentclass.addClass('text-success');
                        percenticon.addClass('fa-arrow-up');
                    } else {
                        percentclass.addClass('text-danger');
                        percenticon.addClass('fa-arrow-down');
                    }
                }
            });
        };
        setInterval(getData, 10000);
    }
</script>
