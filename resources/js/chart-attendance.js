document.addEventListener("DOMContentLoaded", function () {
    var ctx = document.getElementById('chartAttendance').getContext('2d');
    var chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Subuh', 'Dzuhur', 'Ashar', 'Maghrib', 'Isya'],
            datasets: [{
                label: 'Persentase Kehadiran',
                data: [95, 88, 80, 92, 85],
                backgroundColor: 'rgb(29 122 129)',
                borderRadius: 25, // Tambahkan border radius
                borderSkipped: false, // Agar semua sisi memiliki border radius
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
});
