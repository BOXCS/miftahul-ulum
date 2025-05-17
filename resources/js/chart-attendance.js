document.addEventListener("DOMContentLoaded", function () {
    var ctx = document.getElementById("chartAttendance").getContext("2d");
    var chart;
    var attendanceData = {}; // Variabel untuk menyimpan data dari server

    // Fungsi untuk mengambil data dari server
    async function fetchAttendanceData(filter) {
        try {
            const response = await fetch(`/api/attendance?filter=${filter}`);
            const data = await response.json();
            // Simpan dua data: present dan absent
            attendanceData[filter] = {
                present: data.present,
                absent: data.absent,
            };
            initChart(filter);
        } catch (error) {
            console.error("Error fetching attendance data:", error);
        }
    }

    // Inisialisasi grafik dengan data dari server
    function initChart(filter) {
        if (chart) {
            chart.destroy(); // Reset grafik lama
        }
    
        const data = attendanceData[filter];
    
        chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Subuh', 'Dzuhur', 'Ashar', 'Maghrib', 'Isya'],
                datasets: [
                    {
                        label: 'Persentase Kehadiran',
                        data: data.present,
                        backgroundColor: 'rgb(29 122 129)',
                        borderRadius: 25,
                        borderSkipped: false,
                    },
                    {
                        label: 'Persentase Ketidakhadiran',
                        data: data.absent,
                        backgroundColor: 'rgb(220 53 69)', // Warna merah
                        borderRadius: 25,
                        borderSkipped: false,
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100 // karena persentase
                    }
                },
                barPercentage: 0.6,
                categoryPercentage: 0.8,
            }
        });
    }
    

    // Inisialisasi grafik pertama kali
    fetchAttendanceData("today");

    // Event listener untuk dropdown filter
    document.querySelectorAll(".dropdown-item").forEach((item) => {
        item.addEventListener("click", function (e) {
            e.preventDefault();
            var filter = this.getAttribute("data-filter");
            document.getElementById("filterDropdown").textContent =
                this.textContent;
            fetchAttendanceData(filter); // Ambil data baru dan perbarui grafik
        });
    });
});
