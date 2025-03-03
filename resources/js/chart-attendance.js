document.addEventListener("DOMContentLoaded", function () {
    var ctx = document.getElementById('chartAttendance').getContext('2d');
    var chart;
    var attendanceData = {}; // Variabel untuk menyimpan data dari server

    // Fungsi untuk mengambil data dari server
    async function fetchAttendanceData(filter) {
        try {
            const response = await fetch(`/api/attendance?filter=${filter}`);
            const data = await response.json();
            attendanceData[filter] = data; // Simpan data ke variabel
            initChart(filter); // Inisialisasi grafik setelah data diambil
        } catch (error) {
            console.error('Error fetching attendance data:', error);
        }
    }

    // Inisialisasi grafik dengan data dari server
    function initChart(filter) {
        if (chart) {
            chart.destroy(); // Hapus grafik sebelumnya jika ada
        }
        chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Subuh', 'Dzuhur', 'Ashar', 'Maghrib', 'Isya'],
                datasets: [{
                    label: 'Persentase Kehadiran',
                    data: attendanceData[filter], // Ambil data dari variabel
                    backgroundColor: 'rgb(29 122 129)',
                    borderRadius: 25,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                barPercentage: 0.6,
                categoryPercentage: 0.8,
            }
        });
    }

    // Inisialisasi grafik pertama kali
    fetchAttendanceData('today');

    // Event listener untuk dropdown filter
    document.querySelectorAll('.dropdown-item').forEach(item => {
        item.addEventListener('click', function (e) {
            e.preventDefault();
            var filter = this.getAttribute('data-filter');
            document.getElementById('filterDropdown').textContent = this.textContent;
            fetchAttendanceData(filter); // Ambil data baru dan perbarui grafik
        });
    });
});