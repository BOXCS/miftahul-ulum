import Alpine from "alpinejs";
import persist from "@alpinejs/persist";
import Chart from "chart.js/auto";
import axios from "axios";
import Echo from "laravel-echo";
import Pusher from "pusher-js";

console.log('ENV CHECK:', import.meta.env.VITE_PUSHER_APP_KEY);
window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: "reverb",
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT ?? 80,
    wssPort: import.meta.env.VITE_REVERB_PORT ?? 443,
    forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? "https") === "https",
    enabledTransports: ["ws", "wss"],
});

// ─── Alpine.js Setup ──────────────────────────────────────────
Alpine.plugin(persist);
window.Alpine = Alpine;

// ─── Axios Setup ─────────────────────────────────────────────
axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";
const token = document.querySelector('meta[name="csrf-token"]');
if (token) {
    axios.defaults.headers.common["X-CSRF-TOKEN"] =
        token.getAttribute("content");
}
window.axios = axios;

// ─── Chart.js Global Defaults ────────────────────────────────
Chart.defaults.font.family = "'Plus Jakarta Sans', sans-serif";
Chart.defaults.font.size = 12;
Chart.defaults.color = "#64748b";
Chart.defaults.plugins.legend.labels.usePointStyle = true;
Chart.defaults.plugins.legend.labels.pointStyleWidth = 8;
Chart.defaults.plugins.legend.labels.padding = 16;
Chart.defaults.plugins.tooltip.backgroundColor = "#1e293b";
Chart.defaults.plugins.tooltip.titleColor = "#f1f5f9";
Chart.defaults.plugins.tooltip.bodyColor = "#cbd5e1";
Chart.defaults.plugins.tooltip.padding = 10;
Chart.defaults.plugins.tooltip.cornerRadius = 8;
Chart.defaults.plugins.tooltip.displayColors = true;
Chart.defaults.plugins.tooltip.boxPadding = 4;
window.Chart = Chart;

// ─── Alpine Components ────────────────────────────────────────

// Sidebar Component
Alpine.data("sidebar", () => ({
    collapsed: Alpine.$persist(false).as("sidebar_collapsed"),
    mobileOpen: false,

    toggle() {
        if (window.innerWidth < 768) {
            this.mobileOpen = !this.mobileOpen;
        } else {
            this.collapsed = !this.collapsed;
        }
    },

    closeMobile() {
        this.mobileOpen = false;
    },

    init() {
        window.addEventListener("resize", () => {
            if (window.innerWidth >= 768) {
                this.mobileOpen = false;
            }
        });
    },
}));

// Toast Notification Component
Alpine.data("toast", () => ({
    toasts: [],
    nextId: 0,

    show(message, type = "success", duration = 3500) {
        const id = this.nextId++;
        this.toasts.push({ id, message, type });
        setTimeout(() => this.remove(id), duration);
    },

    remove(id) {
        this.toasts = this.toasts.filter((t) => t.id !== id);
    },

    iconFor(type) {
        const icons = {
            success: `<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>`,
            danger: `<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>`,
            warning: `<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>`,
            info: `<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>`,
        };
        return icons[type] || icons.info;
    },

    colorFor(type) {
        const colors = {
            success: "text-green-600 bg-green-50 border-green-200",
            danger: "text-red-600 bg-red-50 border-red-200",
            warning: "text-amber-600 bg-amber-50 border-amber-200",
            info: "text-blue-600 bg-blue-50 border-blue-200",
        };
        return colors[type] || colors.info;
    },
}));

// Modal Component
Alpine.data("modal", (initialOpen = false) => ({
    open: initialOpen,

    show() {
        this.open = true;
    },
    hide() {
        this.open = false;
    },
    toggle() {
        this.open = !this.open;
    },

    init() {
        this.$watch("open", (val) => {
            document.body.style.overflow = val ? "hidden" : "";
        });
        document.addEventListener("keydown", (e) => {
            if (e.key === "Escape" && this.open) this.hide();
        });
    },
}));

// Dropdown Component
Alpine.data("dropdown", () => ({
    open: false,

    toggle() {
        this.open = !this.open;
    },
    close() {
        this.open = false;
    },

    init() {
        document.addEventListener("click", (e) => {
            if (!this.$el.contains(e.target)) this.open = false;
        });
    },
}));

// Search / Filter Component
Alpine.data("searchFilter", (initialData = []) => ({
    query: "",
    data: initialData,
    filtered: initialData,

    filter() {
        const q = this.query.toLowerCase().trim();
        if (!q) {
            this.filtered = this.data;
            return;
        }
        this.filtered = this.data.filter((item) =>
            Object.values(item).some((v) =>
                String(v).toLowerCase().includes(q),
            ),
        );
    },

    reset() {
        this.query = "";
        this.filtered = this.data;
    },
}));

// Tabs Component
Alpine.data("tabs", (defaultTab = 0) => ({
    active: defaultTab,
    setTab(index) {
        this.active = index;
    },
    isActive(index) {
        return this.active === index;
    },
}));

// Confirm Delete Component
Alpine.data("confirmDelete", () => ({
    open: false,
    targetId: null,
    targetName: "",
    deleteUrl: "",

    prompt(id, name, url) {
        this.targetId = id;
        this.targetName = name;
        this.deleteUrl = url;
        this.open = true;
    },

    cancel() {
        this.open = false;
        this.targetId = null;
        this.targetName = "";
        this.deleteUrl = "";
    },

    async confirm() {
        try {
            await axios.delete(this.deleteUrl);
            window.location.reload();
        } catch (err) {
            console.error("Delete failed:", err);
        }
        this.cancel();
    },
}));

// Chart wrapper component
Alpine.data("chartComponent", (config) => ({
    chart: null,
    init() {
        this.$nextTick(() => {
            const canvas = this.$el.querySelector("canvas") || this.$el;
            if (canvas && canvas.getContext) {
                this.chart = new Chart(canvas, config);
            }
        });
    },
    destroy() {
        if (this.chart) {
            this.chart.destroy();
            this.chart = null;
        }
    },
}));

// Attendance Chart helper
window.createAttendanceChart = function (canvasId, labels, data) {
    const ctx = document.getElementById(canvasId);
    if (!ctx) return null;
    return new Chart(ctx, {
        type: "line",
        data: {
            labels,
            datasets: [
                {
                    label: "Hadir",
                    data: data.hadir,
                    borderColor: "#16a34a",
                    backgroundColor: "rgba(22,163,74,0.08)",
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: "#16a34a",
                    pointRadius: 4,
                    pointHoverRadius: 6,
                },
                {
                    label: "Izin",
                    data: data.izin,
                    borderColor: "#f59e0b",
                    backgroundColor: "rgba(245,158,11,0.08)",
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: "#f59e0b",
                    pointRadius: 4,
                    pointHoverRadius: 6,
                },
                {
                    label: "Sakit",
                    data: data.sakit,
                    borderColor: "#3b82f6",
                    backgroundColor: "rgba(59,130,246,0.08)",
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: "#3b82f6",
                    pointRadius: 4,
                    pointHoverRadius: 6,
                },
                {
                    label: "Alpha",
                    data: data.alpha,
                    borderColor: "#ef4444",
                    backgroundColor: "rgba(239,68,68,0.08)",
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: "#ef4444",
                    pointRadius: 4,
                    pointHoverRadius: 6,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: "index", intersect: false },
            plugins: {
                legend: { position: "top" },
            },
            scales: {
                x: { grid: { color: "#f1f5f9" } },
                y: {
                    beginAtZero: true,
                    grid: { color: "#f1f5f9" },
                    ticks: { stepSize: 1 },
                },
            },
        },
    });
};

window.createDonutChart = function (canvasId, labels, data, colors) {
    const ctx = document.getElementById(canvasId);
    if (!ctx) return null;
    return new Chart(ctx, {
        type: "doughnut",
        data: {
            labels,
            datasets: [
                {
                    data,
                    backgroundColor: colors || [
                        "#16a34a",
                        "#f59e0b",
                        "#3b82f6",
                        "#ef4444",
                    ],
                    borderWidth: 0,
                    hoverOffset: 6,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: "72%",
            plugins: {
                legend: {
                    position: "bottom",
                    labels: { padding: 20 },
                },
            },
        },
    });
};

window.createBarChart = function (canvasId, labels, datasets) {
    const ctx = document.getElementById(canvasId);
    if (!ctx) return null;
    return new Chart(ctx, {
        type: "bar",
        data: { labels, datasets },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: "top" } },
            scales: {
                x: { grid: { display: false } },
                y: {
                    beginAtZero: true,
                    grid: { color: "#f1f5f9" },
                },
            },
        },
    });
};

// ─── Utility Helpers ─────────────────────────────────────────
window.formatDate = function (dateStr, locale = "id-ID") {
    return new Date(dateStr).toLocaleDateString(locale, {
        year: "numeric",
        month: "long",
        day: "numeric",
    });
};

window.formatTime = function (dateStr, locale = "id-ID") {
    return new Date(dateStr).toLocaleTimeString(locale, {
        hour: "2-digit",
        minute: "2-digit",
    });
};

window.debounce = function (fn, delay = 300) {
    let timer;
    return (...args) => {
        clearTimeout(timer);
        timer = setTimeout(() => fn(...args), delay);
    };
};

window.initials = function (name = "") {
    return name
        .split(" ")
        .slice(0, 2)
        .map((w) => w[0])
        .join("")
        .toUpperCase();
};

// ─── Start Alpine ─────────────────────────────────────────────
Alpine.start();
