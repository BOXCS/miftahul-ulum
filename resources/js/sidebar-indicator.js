// resources/js/app.js atau di dalam <script> di Blade
document.addEventListener('DOMContentLoaded', function() {
    const links = document.querySelectorAll('.nav-link');
    const indicator = document.getElementById('indicator');

    links.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();

            // Hapus kelas active dari semua link
            links.forEach(l => l.classList.remove('active'));

            // Tambahkan kelas active ke link yang diklik
            this.classList.add('active');

            // Pindahkan indikator ke posisi link yang diklik
            const linkRect = this.getBoundingClientRect();
            const navRect = this.parentElement.getBoundingClientRect();
            const topOffset = linkRect.top - navRect.top;

            indicator.style.top = `${topOffset}px`;

            // Navigasi ke halaman yang dituju
            window.location.href = this.href;
        });
    });

    // Set posisi awal indikator ke link aktif pertama
    const activeLink = document.querySelector('.nav-link.active');
    if (activeLink) {
        const linkRect = activeLink.getBoundingClientRect();
        const navRect = activeLink.parentElement.getBoundingClientRect();
        const topOffset = linkRect.top - navRect.top;

        indicator.style.top = `${topOffset}px`;
    }
});