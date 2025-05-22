<script>
    const cheats = document.getElementById('cheats-chart');
    if (cheats) {
        cheats.getContext('2d');
        const cheatsChart = new Chart(cheats, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($cheatsChart['label']) !!},
                datasets: [{
                    label: 'Posts',
                    data: {!! json_encode($cheatsChart['data']) !!},
                    borderWidth: 1,
                    backgroundColor: [
                        'rgba(0, 63, 92, 0.5)',
                        'rgba(47, 75, 124, 0.5)',
                        'rgba(102, 81, 145, 0.5)',
                        'rgba(160, 81, 149, 0.5)',
                        'rgba(212, 80, 135, 0.5)',
                        'rgba(249, 93, 106, 0.5)',
                        'rgba(255, 124, 67, 0.5)',
                        'rgba(255, 166, 0, 0.5)',
                        'rgba(188, 245, 28, 0.5)',
                        'rgba(28, 245, 154, 0.5)',
                        'rgba(28, 167, 245, 0.5)',
                        'rgba(123, 28, 245, 0.5)',
                    ],
                    borderColor: [
                        'rgba(0, 63, 92)',
                        'rgb(47, 75, 124)',
                        'rgb(102, 81, 145)',
                        'rgb(160, 81, 149)',
                        'rgb(212, 80, 135)',
                        'rgb(249, 93, 106)',
                        'rgb(255, 124, 67)',
                        'rgb(255, 166, 0)',
                        'rgb(188, 245, 28)',
                        'rgb(28, 245, 154)',
                        'rgb(28, 167, 245)',
                        'rgb(123, 28, 245)',
                    ],
                }]
            },
            options: {
                responsive: true,
                legend: {
                    position: 'left',
                    labels: {
                        fontColor: "#000",
                        fontSize: 12
                    }
                },
            },
        });
    }
</script>
