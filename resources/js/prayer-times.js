document.addEventListener("DOMContentLoaded", async function () {
    const prayerTimesContainer = document.getElementById("prayer-times");

    const apiUrl = "https://api.aladhan.com/v1/timingsByCity?city=Jember&country=Indonesia&method=2";

    try {
        const response = await fetch(apiUrl);
        const data = await response.json();
        const timings = data.data.timings;
        const prayerNames = ["Fajr", "Dhuhr", "Asr", "Maghrib", "Isha"];
        const prayerLabels = ["Subuh", "Zuhur", "Ashar", "Maghrib", "Isya"];

        let html = "";
        const now = new Date();
        let upcomingPrayerIndex = -1;

        // Menentukan jadwal shalat yang akan datang
        for (let i = 0; i < prayerNames.length; i++) {
            let [hour, minute] = timings[prayerNames[i]].split(":").map(Number);
            let prayerTime = new Date();
            prayerTime.setHours(hour, minute, 0, 0);

            if (prayerTime > now) {
                upcomingPrayerIndex = i;
                break;
            }
        }

        // Generate HTML untuk setiap jadwal shalat
        prayerNames.forEach((prayer, index) => {
            let time = timings[prayer]; 
            let isUpcoming = index === upcomingPrayerIndex;

            // Gaya inline untuk jadwal yang akan datang
            let cardStyle = isUpcoming ? "background-color: rgb(29, 122, 129); color: white; font-size: 1.2rem; padding: 1.5rem; transform: scale(1.1); transition: all 0.3s ease-in-out;" : "";

            html += `
                <div class="card border shadow-sm p-3" style="${cardStyle}">
                    <p class="fw-light mb-1">Waktu adzan untuk daerah Jember</p>
                    <div class="d-flex flex-row align-items-center justify-content-between">
                        <p class="mb-0 ${isUpcoming ? 'fw-bold' : 'text-muted'}">${prayerLabels[index]}</p>
                        <span class="px-3 py-2 rounded-pill text-black fw-bold align-items-center" style="background-color: #8DBABE;">
                            ${time}
                        </span>
                    </div>
                </div>
            `;
        });

        prayerTimesContainer.innerHTML = html;
    } catch (error) {
        console.error("Error fetching prayer times:", error);
    }
});
