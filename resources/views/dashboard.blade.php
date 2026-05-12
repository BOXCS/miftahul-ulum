@extends('layouts.app')

@section('title', 'Dashboard')
@section('breadcrumb', 'Dashboard')

@push('styles')
    <link
        href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=JetBrains+Mono:wght@400;500;600&display=swap"
        rel="stylesheet">
    <style>
        /* ═══════════════════════════════════════════════════
       ROOT & BASE
    ═══════════════════════════════════════════════════ */
        :root {
            --emerald-50: #ecfdf5;
            --emerald-100: #d1fae5;
            --emerald-200: #a7f3d0;
            --emerald-300: #6ee7b7;
            --emerald-400: #34d399;
            --emerald-500: #10b981;
            --emerald-600: #059669;
            --emerald-700: #047857;
            --emerald-800: #065f46;
            --emerald-900: #064e3b;

            --teal-400: #2dd4bf;
            --teal-500: #14b8a6;
            --teal-600: #0d9488;
            --teal-700: #0f766e;
            --teal-800: #115e59;
            --teal-900: #134e4a;

            --slate-50: #f8fafc;
            --slate-100: #f1f5f9;
            --slate-200: #e2e8f0;
            --slate-300: #cbd5e1;
            --slate-400: #94a3b8;
            --slate-500: #64748b;
            --slate-600: #475569;
            --slate-700: #334155;
            --slate-800: #1e293b;
            --slate-900: #0f172a;

            --bg-base: #f0f4f8;
            --bg-card: #ffffff;
            --bg-card2: #fafbfc;

            --r-sm: 10px;
            --r-md: 14px;
            --r-lg: 18px;
            --r-xl: 24px;
            --r-2xl: 32px;

            --sh-xs: 0 1px 2px rgba(15, 23, 42, .05);
            --sh-sm: 0 2px 8px rgba(15, 23, 42, .06), 0 1px 3px rgba(15, 23, 42, .04);
            --sh-md: 0 6px 20px rgba(15, 23, 42, .08), 0 2px 6px rgba(15, 23, 42, .04);
            --sh-lg: 0 14px 40px rgba(15, 23, 42, .10), 0 4px 12px rgba(15, 23, 42, .05);
            --sh-xl: 0 24px 64px rgba(15, 23, 42, .14), 0 8px 20px rgba(15, 23, 42, .06);
            --sh-teal: 0 8px 28px rgba(5, 150, 105, .25);
            --sh-teal-sm: 0 4px 14px rgba(5, 150, 105, .18);

            --font-main: 'Outfit', sans-serif;
            --font-mono: 'JetBrains Mono', monospace;
        }

        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: var(--font-main);
            background: var(--bg-base);
            color: var(--slate-800);
            -webkit-font-smoothing: antialiased;
        }

        /* ═══════════════════════════════════════════════════
       LAYOUT WRAPPER
    ═══════════════════════════════════════════════════ */
        .db {
            padding-top: 8px;
            padding-right: 16px;
            padding-bottom: 16px;
            padding-left: 20px;
            max-width: 100%;

        }

        /* ═══════════════════════════════════════════════════
       PAGE HEADER
    ═══════════════════════════════════════════════════ */
        .db-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 16px;
            margin-bottom: 16px;
        }

        .db-header-title {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .db-header-title h1 {
            font-size: 2rem;
            font-weight: 800;
            color: var(--slate-900);
            letter-spacing: -0.04em;
            line-height: 1;
        }

        .db-header-title p {
            font-size: 0.875rem;
            color: var(--slate-500);
            font-weight: 400;
        }

        .db-header-title p strong {
            color: var(--emerald-600);
            font-weight: 700;
        }

        .db-header-actions {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        /* Live badge */
        .badge-live {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 7px 14px;
            border-radius: 999px;
            background: var(--emerald-50);
            border: 1.5px solid var(--emerald-200);
            font-size: 0.7rem;
            font-weight: 700;
            color: var(--emerald-700);
            letter-spacing: 0.06em;
            text-transform: uppercase;
        }

        .badge-live-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: var(--emerald-500);
            flex-shrink: 0;
            animation: dotPulse 2s ease infinite;
        }

        @keyframes dotPulse {

            0%,
            100% {
                box-shadow: 0 0 0 0 rgba(16, 185, 129, .6);
            }

            50% {
                box-shadow: 0 0 0 6px rgba(16, 185, 129, 0);
            }
        }

        /* CTA button */
        .btn-primary {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 9px 20px;
            border-radius: var(--r-md);
            background: linear-gradient(135deg, var(--emerald-600), var(--teal-700));
            color: #fff;
            font-size: 0.825rem;
            font-weight: 700;
            font-family: var(--font-main);
            text-decoration: none;
            text-decoration: none;
            border: none;
            cursor: pointer;
            box-shadow: var(--sh-teal-sm);
            transition: all .25s ease;
            letter-spacing: -0.01em;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, var(--emerald-700), var(--teal-800));
            box-shadow: var(--sh-teal);
            transform: translateY(-2px);
            color: #fff;
        }

        /* ═══════════════════════════════════════════════════
       SHOLAT CARD — COMPLETE REDESIGN
    ═══════════════════════════════════════════════════ */
        .sholat-card {
            position: relative;
            border-radius: var(--r-xl);
            overflow: hidden;
            margin-bottom: 28px;
            box-shadow: var(--sh-teal);
        }

        .sholat-card-inner {
            background: linear-gradient(145deg, #064e3b 0%, #065f46 35%, #047857 70%, #0f766e 100%);
            position: relative;
        }

        /* Decorative mesh bg */
        .sholat-card-inner::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(ellipse 70% 80% at 80% -20%, rgba(52, 211, 153, .18) 0%, transparent 60%),
                radial-gradient(ellipse 40% 50% at -10% 110%, rgba(16, 185, 129, .12) 0%, transparent 55%),
                url("data:image/svg+xml,%3Csvg width='40' height='40' viewBox='0 0 40 40' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M0 0h2v2H0zm4 0h2v2H4zm4 0h2v2H8zm4 0h2v2h-4zm4 0h2v2h-4zm4 0h2v2h-4zm4 0h2v2h-4zm4 0h2v2h-4zM2 2h2v2H2zm4 0h2v2H6zm4 0h2v2h-4zm4 0h2v2h-4zm4 0h2v2h-4zm4 0h2v2h-4zm4 0h2v2h-4z'/%3E%3C/g%3E%3C/svg%3E");
            pointer-events: none;
        }

        .sholat-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding: 20px 28px 16px;
            position: relative;
        }

        .sholat-head-left {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .sholat-head-icon {
            width: 42px;
            height: 42px;
            background: rgba(255, 255, 255, .12);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, .2);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: rgba(255, 255, 255, .9);
            flex-shrink: 0;
        }

        .sholat-head-text h2 {
            font-size: 0.9rem;
            font-weight: 700;
            color: #fff;
            letter-spacing: -0.01em;
            margin-bottom: 2px;
        }

        .sholat-head-text p {
            font-size: 0.72rem;
            color: rgba(255, 255, 255, .55);
            font-weight: 500;
        }

        .sholat-date-badge {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 2px;
        }

        .sholat-date-primary {
            font-size: 0.78rem;
            font-weight: 700;
            color: rgba(255, 255, 255, .85);
            font-family: var(--font-mono);
        }

        .sholat-date-hijri {
            font-size: 0.67rem;
            color: rgba(255, 255, 255, .45);
            font-weight: 500;
        }

        /* Divider */
        .sholat-divider {
            height: 1px;
            background: rgba(255, 255, 255, .1);
            margin: 0 28px;
            position: relative;
        }

        .sholat-times {
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            position: relative;
            padding: 0 8px 8px;
        }

        @media(max-width:900px) {
            .sholat-times {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media(max-width:480px) {
            .sholat-times {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        .sholat-time-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            padding: 20px 10px;
            position: relative;
            border-radius: var(--r-md);
            margin: 8px 4px 0;
            cursor: default;
            transition: background .2s;
        }

        .sholat-time-item:hover {
            background: rgba(255, 255, 255, .07);
        }

        .sholat-time-item.now {
            background: rgba(255, 255, 255, .12);
            border: 1px solid rgba(255, 255, 255, .18);
        }

        .sholat-time-item.now .sholat-name {
            color: var(--emerald-300);
        }

        .sholat-now-tag {
            position: absolute;
            top: 8px;
            right: 8px;
            background: var(--emerald-400);
            color: var(--emerald-900);
            font-size: 0.58rem;
            font-weight: 800;
            letter-spacing: 0.05em;
            padding: 2px 7px;
            border-radius: 999px;
            text-transform: uppercase;
        }

        /* Icon per sholat */
        .sholat-icon-wrap {
            width: 38px;
            height: 38px;
            background: rgba(255, 255, 255, .1);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
        }

        .sholat-name {
            font-size: 0.65rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            color: rgba(255, 255, 255, .5);
        }

        .sholat-val {
            font-size: 1.35rem;
            font-weight: 800;
            color: #fff;
            font-family: var(--font-mono);
            letter-spacing: -0.02em;
            line-height: 1;
        }

        /* countdown strip */
        .sholat-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 12px 28px 18px;
            position: relative;
        }

        .sholat-next {
            font-size: 0.75rem;
            color: rgba(255, 255, 255, .5);
            font-weight: 500;
        }

        .sholat-next strong {
            color: var(--emerald-300);
            font-weight: 700;
        }

        .sholat-countdown {
            font-size: 0.8rem;
            font-family: var(--font-mono);
            font-weight: 600;
            color: rgba(255, 255, 255, .75);
            background: rgba(255, 255, 255, .1);
            padding: 5px 12px;
            border-radius: 999px;
            border: 1px solid rgba(255, 255, 255, .15);
        }

        /* ═══════════════════════════════════════════════════
       STAT CARDS GRID
    ═══════════════════════════════════════════════════ */
        .stats-row {
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            gap: 16px;
            margin-bottom: 28px;
        }

        @media(max-width:1300px) {
            .stats-row {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media(max-width:640px) {
            .stats-row {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        .scard {
            background: var(--bg-card);
            border-radius: var(--r-lg);
            padding: 22px 20px 18px;
            border: 1px solid var(--slate-200);
            box-shadow: var(--sh-sm);
            display: flex;
            flex-direction: column;
            gap: 16px;
            position: relative;
            overflow: hidden;
            transition: transform .22s, box-shadow .22s;
            cursor: default;
        }

        .scard:hover {
            transform: translateY(-3px);
            box-shadow: var(--sh-md);
        }

        /* subtle top glow line */
        .scard::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            border-radius: var(--r-lg) var(--r-lg) 0 0;
        }

        .scard-emerald::before {
            background: linear-gradient(90deg, #059669, #34d399);
        }

        .scard-blue::before {
            background: linear-gradient(90deg, #2563eb, #60a5fa);
        }

        .scard-amber::before {
            background: linear-gradient(90deg, #d97706, #fbbf24);
        }

        .scard-red::before {
            background: linear-gradient(90deg, #dc2626, #f87171);
        }

        .scard-violet::before {
            background: linear-gradient(90deg, #7c3aed, #a78bfa);
        }

        .scard-rose::before {
            background: linear-gradient(90deg, #e11d48, #fb7185);
        }

        /* big decorative number behind */
        .scard::after {
            content: '';
            position: absolute;
            right: -20px;
            bottom: -20px;
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(0, 0, 0, .04) 0%, transparent 70%);
            pointer-events: none;
        }

        .scard-icon {
            width: 46px;
            height: 46px;
            border-radius: 13px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .scard-emerald .scard-icon {
            background: var(--emerald-50);
            color: var(--emerald-600);
        }

        .scard-blue .scard-icon {
            background: #eff6ff;
            color: #2563eb;
        }

        .scard-amber .scard-icon {
            background: #fffbeb;
            color: #d97706;
        }

        .scard-red .scard-icon {
            background: #fef2f2;
            color: #dc2626;
        }

        .scard-violet .scard-icon {
            background: #f5f3ff;
            color: #7c3aed;
        }

        .scard-rose .scard-icon {
            background: #fff1f2;
            color: #e11d48;
        }

        .scard-body {
            position: relative;
            z-index: 1;
        }

        .scard-label {
            font-size: 0.68rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.09em;
            color: var(--slate-400);
            margin-bottom: 6px;
        }

        .scard-value {
            font-size: 2.4rem;
            font-weight: 800;
            color: var(--slate-900);
            font-family: var(--font-mono);
            letter-spacing: -0.04em;
            line-height: 1;
        }

        .scard-sub {
            display: flex;
            align-items: center;
            gap: 6px;
            margin-top: 6px;
            flex-wrap: wrap;
        }

        .scard-trend {
            display: inline-flex;
            align-items: center;
            gap: 3px;
            font-size: 0.68rem;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 999px;
        }

        .trend-up {
            background: #dcfce7;
            color: #15803d;
        }

        .trend-down {
            background: #fee2e2;
            color: #dc2626;
        }

        .trend-flat {
            background: var(--slate-100);
            color: var(--slate-500);
        }

        .scard-sub-text {
            font-size: 0.68rem;
            color: var(--slate-400);
            font-weight: 500;
        }

        /* ═══════════════════════════════════════════════════
       MAIN 2-COL GRID
    ═══════════════════════════════════════════════════ */
        .main-grid {
            display: grid;
            grid-template-columns: 1fr 390px;
            gap: 16px;
        }

        @media(max-width:1100px) {
            .main-grid {
                grid-template-columns: 1fr;
            }
        }

        /* ═══════════════════════════════════════════════════
       PANEL BASE
    ═══════════════════════════════════════════════════ */
        .panel {
            background: var(--bg-card);
            border-radius: var(--r-lg);
            border: 1px solid var(--slate-200);
            box-shadow: var(--sh-sm);
            overflow: hidden;
        }

        .panel-head {
            padding: 18px 22px;
            border-bottom: 1px solid var(--slate-100);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        .panel-title {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.875rem;
            font-weight: 700;
            color: var(--slate-800);
            letter-spacing: -0.01em;
        }

        .panel-title-ico {
            width: 30px;
            height: 30px;
            border-radius: 9px;
            background: var(--emerald-50);
            color: var(--emerald-600);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .panel-link {
            font-size: 0.72rem;
            font-weight: 700;
            color: var(--emerald-600);
            text-decoration: none;
            padding: 5px 13px;
            border-radius: 999px;
            background: var(--emerald-50);
            border: 1px solid var(--emerald-100);
            transition: all .2s;
            cursor: pointer;
            font-family: var(--font-main);
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .panel-link:hover {
            background: var(--emerald-600);
            color: #fff;
            border-color: transparent;
            box-shadow: var(--sh-teal-sm);
            transform: translateY(-1px);
        }

        /* ═══════════════════════════════════════════════════
       CHART PANEL
    ═══════════════════════════════════════════════════ */
        .chart-controls {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .chart-select-wrap {
            position: relative;
        }

        .chart-period-select {
            appearance: none;
            -webkit-appearance: none;
            background: var(--slate-100);
            border: 1px solid var(--slate-200);
            border-radius: 999px;
            font-family: var(--font-main);
            font-size: 0.72rem;
            font-weight: 600;
            color: var(--slate-600);
            padding: 6px 28px 6px 12px;
            cursor: pointer;
            outline: none;
            transition: all .2s;
        }

        .chart-period-select:focus {
            border-color: var(--emerald-400);
            background: #fff;
        }

        .chart-select-arrow {
            position: absolute;
            right: 9px;
            top: 50%;
            transform: translateY(-50%);
            pointer-events: none;
            color: var(--slate-400);
        }

        .chart-type-tabs {
            display: flex;
            gap: 3px;
            background: var(--slate-100);
            border-radius: 999px;
            padding: 3px;
        }

        .chart-tab-btn {
            font-size: 0.68rem;
            font-weight: 700;
            padding: 5px 11px;
            border-radius: 999px;
            border: none;
            background: transparent;
            color: var(--slate-400);
            cursor: pointer;
            transition: all .2s;
            font-family: var(--font-main);
            letter-spacing: 0.01em;
        }

        .chart-tab-btn.active {
            background: #fff;
            color: var(--emerald-700);
            box-shadow: 0 1px 5px rgba(15, 23, 42, .1);
        }

        .chart-wrap {
            padding: 20px 22px 8px;
        }

        /* donut strip */
        .donut-strip {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            border-top: 1px solid var(--slate-100);
        }

        .donut-cell {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            padding: 18px 12px 20px;
            position: relative;
        }

        .donut-cell+.donut-cell {
            border-left: 1px solid var(--slate-100);
        }

        .donut-cell-label {
            font-size: 0.65rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: var(--slate-400);
        }

        .donut-cell-val {
            font-size: 0.875rem;
            font-weight: 800;
            color: var(--slate-800);
            font-family: var(--font-mono);
        }

        .donut-pct {
            font-size: 0.7rem;
            font-weight: 600;
            color: var(--slate-400);
            font-family: var(--font-mono);
        }

        /* ═══════════════════════════════════════════════════
       ACTIVITY
    ═══════════════════════════════════════════════════ */
        .activity-body {
            max-height: 340px;
            overflow-y: auto;
        }

        .activity-body::-webkit-scrollbar {
            width: 3px;
        }

        .activity-body::-webkit-scrollbar-thumb {
            background: var(--slate-200);
            border-radius: 2px;
        }

        .act-item {
            display: flex;
            align-items: flex-start;
            gap: 14px;
            padding: 14px 22px;
            transition: background .15s;
            position: relative;
        }

        .act-item:hover {
            background: var(--slate-50);
        }

        .act-timeline {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding-top: 3px;
            flex-shrink: 0;
        }

        .act-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            flex-shrink: 0;
            border: 2px solid #fff;
            box-shadow: 0 0 0 2px;
        }

        .act-dot.green {
            background: var(--emerald-500);
            box-shadow: 0 0 0 2px var(--emerald-100);
        }

        .act-dot.red {
            background: #ef4444;
            box-shadow: 0 0 0 2px #fee2e2;
        }

        .act-dot.blue {
            background: #3b82f6;
            box-shadow: 0 0 0 2px #dbeafe;
        }

        .act-dot.amber {
            background: #f59e0b;
            box-shadow: 0 0 0 2px #fef3c7;
        }

        .act-dot.purple {
            background: #8b5cf6;
            box-shadow: 0 0 0 2px #ede9fe;
        }

        .act-line {
            width: 2px;
            flex: 1;
            min-height: 22px;
            background: var(--slate-100);
            margin-top: 5px;
        }

        .act-item:last-child .act-line {
            display: none;
        }

        .act-text {
            flex: 1;
            min-width: 0;
        }

        .act-main {
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--slate-700);
            line-height: 1.5;
        }

        .act-sub {
            font-size: 0.7rem;
            color: var(--slate-400);
            margin-top: 1px;
            font-weight: 500;
        }

        .act-time {
            font-size: 0.67rem;
            font-family: var(--font-mono);
            color: var(--slate-400);
            white-space: nowrap;
            padding-top: 2px;
            flex-shrink: 0;
        }

        /* ═══════════════════════════════════════════════════
       RIGHT COLUMN
    ═══════════════════════════════════════════════════ */
        .right-col {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        /* Quick Actions */
        .qa-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            padding: 16px;
        }

        .qa-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
            padding: 18px 10px;
            border-radius: var(--r-md);
            background: var(--slate-50);
            border: 1.5px solid var(--slate-200);
            text-decoration: none;
            cursor: pointer;
            transition: all .22s;
            font-family: var(--font-main);
        }

        .qa-item:hover {
            transform: translateY(-2px);
            box-shadow: var(--sh-md);
        }

        .qa-item.qa-emerald:hover {
            background: var(--emerald-50);
            border-color: var(--emerald-300);
        }

        .qa-item.qa-blue:hover {
            background: #eff6ff;
            border-color: #93c5fd;
        }

        .qa-item.qa-amber:hover {
            background: #fffbeb;
            border-color: #fcd34d;
        }

        .qa-item.qa-violet:hover {
            background: #f5f3ff;
            border-color: #c4b5fd;
        }

        .qa-ico {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .qa-emerald .qa-ico {
            background: var(--emerald-50);
            color: var(--emerald-600);
        }

        .qa-blue .qa-ico {
            background: #eff6ff;
            color: #2563eb;
        }

        .qa-amber .qa-ico {
            background: #fffbeb;
            color: #d97706;
        }

        .qa-violet .qa-ico {
            background: #f5f3ff;
            color: #7c3aed;
        }

        .qa-label {
            font-size: 0.73rem;
            font-weight: 700;
            color: var(--slate-700);
            text-align: center;
            line-height: 1.35;
        }

        /* Notifications */
        .notif-badge {
            background: var(--emerald-600);
            color: #fff;
            font-size: 0.6rem;
            font-weight: 800;
            padding: 2px 7px;
            border-radius: 999px;
            margin-left: 4px;
            letter-spacing: 0.02em;
        }

        .notif-body {
            padding: 4px 0;
        }

        .notif-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 13px 20px;
            cursor: pointer;
            transition: background .15s;
            position: relative;
        }

        .notif-item:hover {
            background: var(--slate-50);
        }

        .notif-item.unread::after {
            content: '';
            position: absolute;
            left: 8px;
            top: 50%;
            transform: translateY(-50%);
            width: 5px;
            height: 5px;
            border-radius: 50%;
            background: var(--emerald-500);
        }

        .notif-ava {
            width: 38px;
            height: 38px;
            border-radius: 11px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.05rem;
            flex-shrink: 0;
        }

        .notif-text {
            flex: 1;
            min-width: 0;
        }

        .notif-title {
            font-size: 0.78rem;
            font-weight: 700;
            color: var(--slate-800);
            line-height: 1.4;
            margin-bottom: 2px;
        }

        .notif-desc {
            font-size: 0.7rem;
            color: var(--slate-500);
            line-height: 1.45;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .notif-time {
            font-size: 0.64rem;
            font-family: var(--font-mono);
            color: var(--slate-400);
            white-space: nowrap;
            padding-top: 2px;
            flex-shrink: 0;
        }

        /* ═══════════════════════════════════════════════════
       MODAL / POP-UP
    ═══════════════════════════════════════════════════ */
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, .55);
            backdrop-filter: blur(6px);
            z-index: 9000;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            opacity: 0;
            pointer-events: none;
            transition: opacity .3s ease;
        }

        .modal-overlay.open {
            opacity: 1;
            pointer-events: all;
        }

        .modal-box {
            background: #fff;
            border-radius: var(--r-xl);
            box-shadow: var(--sh-xl);
            width: 100%;
            max-width: 620px;
            max-height: 85vh;
            display: flex;
            flex-direction: column;
            transform: translateY(20px) scale(.97);
            transition: transform .3s ease;
            overflow: hidden;
        }

        .modal-overlay.open .modal-box {
            transform: translateY(0) scale(1);
        }

        .modal-box.wide {
            max-width: 720px;
        }

        .modal-hdr {
            padding: 22px 26px 18px;
            border-bottom: 1px solid var(--slate-100);
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-shrink: 0;
        }

        .modal-hdr-title {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1rem;
            font-weight: 800;
            color: var(--slate-900);
            letter-spacing: -0.02em;
        }

        .modal-hdr-ico {
            width: 34px;
            height: 34px;
            border-radius: 10px;
            background: var(--emerald-50);
            color: var(--emerald-600);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-close {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: var(--slate-100);
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--slate-500);
            transition: all .2s;
            font-size: 1.1rem;
            font-family: var(--font-main);
        }

        .modal-close:hover {
            background: var(--slate-200);
            color: var(--slate-800);
        }

        .modal-body {
            overflow-y: auto;
            flex: 1;
        }

        .modal-body::-webkit-scrollbar {
            width: 4px;
        }

        .modal-body::-webkit-scrollbar-thumb {
            background: var(--slate-200);
            border-radius: 2px;
        }

        .modal-ftr {
            padding: 14px 26px;
            border-top: 1px solid var(--slate-100);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-shrink: 0;
            background: var(--slate-50);
        }

        .modal-ftr-txt {
            font-size: 0.75rem;
            color: var(--slate-400);
            font-weight: 500;
        }

        /* Modal activity items */
        .modal-act-item {
            display: flex;
            align-items: flex-start;
            gap: 14px;
            padding: 14px 26px;
            border-bottom: 1px solid var(--slate-50);
            transition: background .15s;
        }

        .modal-act-item:hover {
            background: var(--slate-50);
        }

        .modal-act-item:last-child {
            border-bottom: none;
        }

        .modal-act-type {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            flex-shrink: 0;
        }

        .modal-act-body {
            flex: 1;
            min-width: 0;
        }

        .modal-act-name {
            font-size: 0.82rem;
            font-weight: 700;
            color: var(--slate-800);
            margin-bottom: 3px;
            line-height: 1.4;
        }

        .modal-act-desc {
            font-size: 0.72rem;
            color: var(--slate-500);
            font-weight: 500;
        }

        .modal-act-meta {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 4px;
            flex-shrink: 0;
        }

        .modal-act-time {
            font-size: 0.67rem;
            font-family: var(--font-mono);
            color: var(--slate-400);
            white-space: nowrap;
        }

        .modal-act-tag {
            font-size: 0.6rem;
            font-weight: 700;
            padding: 2px 7px;
            border-radius: 999px;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        /* Notif modal items */
        .modal-notif-item {
            display: flex;
            align-items: flex-start;
            gap: 14px;
            padding: 16px 26px;
            border-bottom: 1px solid var(--slate-50);
            transition: background .15s;
            cursor: pointer;
            position: relative;
        }

        .modal-notif-item:hover {
            background: var(--slate-50);
        }

        .modal-notif-item:last-child {
            border-bottom: none;
        }

        .modal-notif-item.unread::before {
            content: '';
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            width: 5px;
            height: 5px;
            border-radius: 50%;
            background: var(--emerald-500);
        }

        /* ═══════════════════════════════════════════════════
       ANIMATIONS
    ═══════════════════════════════════════════════════ */
        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(18px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .anim {
            animation: fadeUp .55s ease both;
        }

        .d0 {
            animation-delay: .00s;
        }

        .d1 {
            animation-delay: .07s;
        }

        .d2 {
            animation-delay: .14s;
        }

        .d3 {
            animation-delay: .21s;
        }

        .d4 {
            animation-delay: .28s;
        }

        .d5 {
            animation-delay: .35s;
        }

        .d6 {
            animation-delay: .42s;
        }

        /* left col gap */
        .left-col {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        /* Empty state */
        .empty-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 40px 20px;
            color: var(--slate-400);
            font-size: 0.8rem;
            font-weight: 500;
        }

        .empty-ico {
            width: 52px;
            height: 52px;
            background: var(--slate-100);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
        }

        /* Ganti yang lama */
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, .45);
            /* lebih transparan dari .55 */
            backdrop-filter: blur(4px);
            /* blur lebih ringan */
            -webkit-backdrop-filter: blur(4px);
            z-index: 9000;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            opacity: 0;
            pointer-events: none;
            transition: opacity .25s ease;
        }

        .act-bg-green {
            background: #dcfce7;
        }

        .act-bg-red {
            background: #fee2e2;
        }

        .act-bg-blue {
            background: #dbeafe;
        }

        .act-bg-purple {
            background: #ede9fe;
        }

        .act-bg-amber {
            background: #fef3c7;
        }
    </style>
@endpush

@section('content')
    <div class="db">
        <div id="dashboardStats" data-total="{{ $stats['total_santri'] ?? 160 }}"
            data-hadir="{{ $stats['hadir_hari_ini'] ?? 148 }}" data-izin="{{ $stats['izin_hari_ini'] ?? 7 }}"
            data-alpha="{{ $stats['alpha_hari_ini'] ?? 5 }}">
        </div>
        {{-- ══════════════ PAGE HEADER ══════════════ --}}
        <div class="db-header anim d0">
            <div class="db-header-title">
                <h1>Dashboard</h1>
                <p>Selamat datang kembali, <strong>Admin!</strong> &mdash;
                    {{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM Y') }}</p>
            </div>
            <div class="db-header-actions">
                <span class="badge-live">
                    <span class="badge-live-dot"></span>
                    Sistem Aktif
                </span>
                <a href="{{ route('attendance.index') }}" class="btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="9 11 12 14 22 4" />
                        <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11" />
                    </svg>
                    Input Absensi
                </a>
            </div>
        </div>

        {{-- ══════════════ JADWAL SHOLAT ══════════════ --}}

            @if ($jadwalSholat)
                <div class="sholat-card anim d1">
                    <div class="sholat-card-inner">
                        <div class="sholat-head">
                            <div class="sholat-head-left">
                                <div class="sholat-head-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path
                                            d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83" />
                                    </svg>
                                </div>
                                <div class="sholat-head-text">
                                    <h2>Jadwal Waktu Sholat</h2>
                                    <p>{{ $jadwalSholat['city'] ?? 'Jember' }}, {{ $jadwalSholat['country'] ?? 'Indonesia' }} &mdash; Kemenag RI</p>
                                </div>
                            </div>
                            <div class="sholat-date-badge">
                                <span class="sholat-date-primary">{{ $jadwalSholat['tanggal'] }}</span>
                                <span class="sholat-date-hijri" id="hijriDate">{{ $jadwalSholat['hijri'] ?? '— Hijriah' }}</span>
                            </div>
                        </div>

                        <div class="sholat-divider"></div>

                        <div class="sholat-times">
                            @php
                                $sholatData = [
                                    [
                                        'name' => 'Subuh',
                                        'time' => $jadwalSholat['subuh'],
                                        'icon' => '🌙',
                                        'id' => 'subuh',
                                    ],
                                    [
                                        'name' => 'Terbit',
                                        'time' => $jadwalSholat['terbit'],
                                        'icon' => '🌅',
                                        'id' => 'terbit',
                                    ],
                                    [
                                        'name' => 'Dzuhur',
                                        'time' => $jadwalSholat['dzuhur'],
                                        'icon' => '☀️',
                                        'id' => 'dzuhur',
                                    ],
                                    [
                                        'name' => 'Ashar',
                                        'time' => $jadwalSholat['ashar'],
                                        'icon' => '🌤️',
                                        'id' => 'ashar',
                                    ],
                                    [
                                        'name' => 'Maghrib',
                                        'time' => $jadwalSholat['maghrib'],
                                        'icon' => '🌆',
                                        'id' => 'maghrib',
                                    ],
                                    ['name' => 'Isya', 'time' => $jadwalSholat['isya'], 'icon' => '🌌', 'id' => 'isya'],
                                ];
                            @endphp
                            @foreach ($sholatData as $s)
                                <div class="sholat-time-item" id="sholat-{{ $s['id'] }}">
                                    <div class="sholat-icon-wrap">{{ $s['icon'] }}</div>
                                    <span class="sholat-name">{{ $s['name'] }}</span>
                                    <span class="sholat-val">{{ $s['time'] }}</span>
                                </div>
                            @endforeach
                        </div>

                        <div class="sholat-footer">
                            <span class="sholat-next">Sholat berikutnya: <strong id="nextSholat">—</strong></span>
                            <span class="sholat-countdown" id="countdownTimer">Menghitung...</span>
                        </div>
                    </div>
                </div>
            @endif

            {{-- ══════════════ STAT CARDS ══════════════ --}}
            <div class="stats-row anim d2">

                <div class="scard scard-emerald" data-num="{{ $stats['total_santri'] }}">
                    <div class="scard-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                            <circle cx="9" cy="7" r="4" />
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                            <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                        </svg>
                    </div>
                    <div class="scard-body">
                        <div class="scard-label">Total Santri</div>
                        <div class="scard-value">{{ $stats['total_santri'] }}</div>
                        <div class="scard-sub">
                            <span class="scard-trend trend-up">↑ 4.2%</span>
                            <span class="scard-sub-text">bulan ini</span>
                        </div>
                    </div>
                </div>

                <div class="scard scard-blue" data-num="{{ $stats['hadir_hari_ini'] }}">
                    <div class="scard-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                            <polyline points="22 4 12 14.01 9 11.01" />
                        </svg>
                    </div>
                    <div class="scard-body">
                        <div class="scard-label">Hadir Hari Ini</div>
                        <div class="scard-value">{{ $stats['hadir_hari_ini'] }}</div>
                        <div class="scard-sub">
                            <span class="scard-trend trend-up">↑ 2.1%</span>
                            <span class="scard-sub-text">vs kemarin</span>
                        </div>
                    </div>
                </div>

                <div class="scard scard-amber" data-num="{{ $stats['izin_hari_ini'] }}">
                    <div class="scard-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                            stroke-linejoin="round">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                            <line x1="16" y1="2" x2="16" y2="6" />
                            <line x1="8" y1="2" x2="8" y2="6" />
                            <line x1="3" y1="10" x2="21" y2="10" />
                        </svg>
                    </div>
                    <div class="scard-body">
                        <div class="scard-label">Izin / Sakit</div>
                        <div class="scard-value">{{ $stats['izin_hari_ini'] }}</div>
                        <div class="scard-sub">
                            <span class="scard-trend trend-down">↓ 1</span>
                            <span class="scard-sub-text">vs kemarin</span>
                        </div>
                    </div>
                </div>

                <div class="scard scard-red" data-num="{{ $stats['alpha_hari_ini'] }}">
                    <div class="scard-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                            stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10" />
                            <line x1="15" y1="9" x2="9" y2="15" />
                            <line x1="9" y1="9" x2="15" y2="15" />
                        </svg>
                    </div>
                    <div class="scard-body">
                        <div class="scard-label">Alpha</div>
                        <div class="scard-value">{{ $stats['alpha_hari_ini'] }}</div>
                        <div class="scard-sub">
                            <span class="scard-trend trend-flat">→ Sama</span>
                            <span class="scard-sub-text">vs kemarin</span>
                        </div>
                    </div>
                </div>

                <div class="scard scard-violet" data-num="{{ $stats['total_ortu'] }}">
                    <div class="scard-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                            <circle cx="12" cy="7" r="4" />
                        </svg>
                    </div>
                    <div class="scard-body">
                        <div class="scard-label">Wali Santri</div>
                        <div class="scard-value">{{ $stats['total_ortu'] }}</div>
                        <div class="scard-sub">
                            <span class="scard-trend trend-up">↑ 3 baru</span>
                            <span class="scard-sub-text">minggu ini</span>
                        </div>
                    </div>
                </div>

                <div class="scard scard-rose" data-num="{{ $stats['pengumuman_aktif'] }}">
                    <div class="scard-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                            stroke-linejoin="round">
                            <polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5" />
                            <path d="M19.07 4.93a10 10 0 0 1 0 14.14" />
                            <path d="M15.54 8.46a5 5 0 0 1 0 7.07" />
                        </svg>
                    </div>
                    <div class="scard-body">
                        <div class="scard-label">Pengumuman</div>
                        <div class="scard-value">{{ $stats['pengumuman_aktif'] }}</div>
                        <div class="scard-sub">
                            <span class="scard-trend trend-flat">Aktif</span>
                            <span class="scard-sub-text">saat ini</span>
                        </div>
                    </div>
                </div>

            </div>

            {{-- ══════════════ MAIN GRID ══════════════ --}}
            <div class="main-grid">

                {{-- LEFT COLUMN --}}
                <div class="left-col">

                    {{-- CHART PANEL --}}
                    <div class="panel anim d3">
                        <div class="panel-head">
                            <h2 class="panel-title">
                                <span class="panel-title-ico">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="22 12 18 12 15 21 9 3 6 12 2 12" />
                                    </svg>
                                </span>
                                Tren Kehadiran
                            </h2>
                            <div class="chart-controls">
                                {{-- Period dropdown --}}
                                <div class="chart-select-wrap">
                                    <select class="chart-period-select" id="chartPeriod">
                                        <option value="7hari">7 Hari Terakhir</option>
                                        <option value="bulanan">Bulanan</option>
                                        <option value="tahunan">Tahunan</option>
                                    </select>
                                    <svg class="chart-select-arrow" xmlns="http://www.w3.org/2000/svg" width="12"
                                        height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="6 9 12 15 18 9" />
                                    </svg>
                                </div>
                                {{-- Type tabs --}}
                                <div class="chart-type-tabs" id="chartTypeTabs">
                                    <button class="chart-tab-btn active" data-type="bar">Batang</button>
                                    <button class="chart-tab-btn" data-type="line">Garis</button>
                                </div>
                            </div>
                        </div>

                        <div class="chart-wrap">
                            <canvas id="mainChart" height="220"></canvas>
                        </div>

                        <div class="donut-strip">
                            <div class="donut-cell">
                                <canvas id="donutHadir" width="72" height="72"></canvas>
                                <span class="donut-cell-label">Hadir</span>
                                <span class="donut-cell-val">{{ $stats['hadir_hari_ini'] }}<span class="donut-pct"> /
                                        {{ $stats['total_santri'] }}</span></span>
                            </div>
                            <div class="donut-cell">
                                <canvas id="donutIzin" width="72" height="72"></canvas>
                                <span class="donut-cell-label">Izin / Sakit</span>
                                <span class="donut-cell-val">{{ $stats['izin_hari_ini'] }}<span class="donut-pct"> /
                                        {{ $stats['total_santri'] }}</span></span>
                            </div>
                            <div class="donut-cell">
                                <canvas id="donutAlpha" width="72" height="72"></canvas>
                                <span class="donut-cell-label">Alpha</span>
                                <span class="donut-cell-val">{{ $stats['alpha_hari_ini'] }}<span class="donut-pct"> /
                                        {{ $stats['total_santri'] }}</span></span>
                            </div>
                        </div>
                    </div>

                    {{-- ACTIVITY PANEL --}}
                    <div class="panel anim d4">
                        <div class="panel-head">
                            <h2 class="panel-title">
                                <span class="panel-title-ico">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="10" />
                                        <polyline points="12 6 12 12 16 14" />
                                    </svg>
                                </span>
                                Aktivitas Terbaru
                            </h2>
                            <button class="panel-link" onclick="openModal('activityModal')">
                                Lihat Semua
                                <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M5 12h14" />
                                    <path d="m12 5 7 7-7 7" />
                                </svg>
                            </button>
                        </div>

                        <div class="activity-body">
                            @forelse($recent_activities as $activity)
                                <div class="act-item">
                                    <div class="act-timeline">
                                        <div class="act-dot {{ $activity['color'] }}"></div>
                                        <div class="act-line"></div>
                                    </div>
                                    <div class="act-text">
                                        <div class="act-main">{{ $activity['text'] }}</div>
                                        <div class="act-sub">Sistem Absensi</div>
                                    </div>
                                    <div class="act-time">{{ $activity['time'] }}</div>
                                </div>
                            @empty
                                <div class="empty-state">
                                    <div class="empty-ico">📋</div>
                                    Belum ada aktivitas terbaru
                                </div>
                            @endforelse
                        </div>
                    </div>

                </div>
                {{-- / LEFT COL --}}

                {{-- RIGHT COLUMN --}}
                <div class="right-col">

                    {{-- QUICK ACTIONS --}}
                    <div class="panel anim d3">
                        <div class="panel-head">
                            <h2 class="panel-title">
                                <span class="panel-title-ico">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2" />
                                    </svg>
                                </span>
                                Aksi Cepat
                            </h2>
                        </div>
                        <div class="qa-grid">
                            <a href="{{ route('students.create') }}" class="qa-item qa-emerald">
                                <div class="qa-ico">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                                        <circle cx="12" cy="7" r="4" />
                                        <line x1="12" y1="13" x2="12" y2="19" />
                                        <line x1="9" y1="16" x2="15" y2="16" />
                                    </svg>
                                </div>
                                <span class="qa-label">Tambah Santri</span>
                            </a>

                            <a href="{{ route('attendance.index') }}" class="qa-item qa-blue">
                                <div class="qa-ico">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                                        <line x1="16" y1="2" x2="16" y2="6" />
                                        <line x1="8" y1="2" x2="8" y2="6" />
                                        <line x1="3" y1="10" x2="21" y2="10" />
                                        <polyline points="9 16 11 18 15 14" />
                                    </svg>
                                </div>
                                <span class="qa-label">Input Absensi</span>
                            </a>

                            <a href="#" class="qa-item qa-amber">
                                <div class="qa-ico">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                                        <polyline points="14 2 14 8 20 8" />
                                        <line x1="16" y1="13" x2="8" y2="13" />
                                        <line x1="16" y1="17" x2="8" y2="17" />
                                    </svg>
                                </div>
                                <span class="qa-label">Laporan Absensi</span>
                            </a>

                            <a href="#" class="qa-item qa-violet">
                                <div class="qa-ico">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5" />
                                        <path d="M19.07 4.93a10 10 0 0 1 0 14.14" />
                                        <path d="M15.54 8.46a5 5 0 0 1 0 7.07" />
                                    </svg>
                                </div>
                                <span class="qa-label">Buat Pengumuman</span>
                            </a>
                        </div>
                    </div>

                    {{-- NOTIFICATIONS --}}
                    <div class="panel anim d4">
                        <div class="panel-head">
                            <h2 class="panel-title">
                                <span class="panel-title-ico">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9" />
                                        <path d="M13.73 21a2 2 0 0 1-3.46 0" />
                                    </svg>
                                </span>
                                Notifikasi
                                <span class="notif-badge">3</span>
                            </h2>
                            <button class="panel-link" onclick="openModal('notifModal')">
                                Lihat Semua
                                <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M5 12h14" />
                                    <path d="m12 5 7 7-7 7" />
                                </svg>
                            </button>
                        </div>

                        <div class="notif-body">
                            {{-- Show only first 5 --}}
                            <div class="notif-item unread">
                                <div class="notif-ava" style="background:#dcfce7">✅</div>
                                <div class="notif-text">
                                    <div class="notif-title">Absensi Subuh Selesai</div>
                                    <div class="notif-desc">Semua kelas telah melaporkan kehadiran Subuh hari ini.</div>
                                </div>
                                <div class="notif-time">05:30</div>
                            </div>

                            <div class="notif-item unread">
                                <div class="notif-ava" style="background:#fff1f2">🔔</div>
                                <div class="notif-text">
                                    <div class="notif-title">Ahmad Fauzi — Izin Sakit</div>
                                    <div class="notif-desc">Wali santri melaporkan izin sakit mulai hari ini.</div>
                                </div>
                                <div class="notif-time">06:12</div>
                            </div>

                            <div class="notif-item unread">
                                <div class="notif-ava" style="background:#eff6ff">📢</div>
                                <div class="notif-text">
                                    <div class="notif-title">Pengumuman Libur Maulid</div>
                                    <div class="notif-desc">Pengumuman baru telah diterbitkan untuk semua wali.</div>
                                </div>
                                <div class="notif-time">07:00</div>
                            </div>

                            <div class="notif-item">
                                <div class="notif-ava" style="background:#fdf4ff">📊</div>
                                <div class="notif-text">
                                    <div class="notif-title">Laporan Mingguan Siap</div>
                                    <div class="notif-desc">Laporan rekap absensi minggu ke-18 siap diunduh.</div>
                                </div>
                                <div class="notif-time">Kemarin</div>
                            </div>

                            <div class="notif-item">
                                <div class="notif-ava" style="background:#fffbeb">⚠️</div>
                                <div class="notif-text">
                                    <div class="notif-title">3 Santri Alpha Berturut-turut</div>
                                    <div class="notif-desc">Perlu tindak lanjut segera oleh wali asrama.</div>
                                </div>
                                <div class="notif-time">Kemarin</div>
                            </div>
                        </div>
                    </div>

                </div>
                {{-- / RIGHT COL --}}
            </div>

    </div>
    {{-- / DB --}}


    {{-- ═══════════════════════════════════════════
     MODAL — AKTIVITAS TERBARU (ALL)
════════════════════════════════════════════ --}}
    <div class="modal-overlay" id="activityModal">
        <div class="modal-box wide">
            <div class="modal-hdr">
                <div class="modal-hdr-title">
                    <div class="modal-hdr-ico">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10" />
                            <polyline points="12 6 12 12 16 14" />
                        </svg>
                    </div>
                    Semua Aktivitas Terbaru
                </div>
                <button class="modal-close" onclick="closeModal('activityModal')">✕</button>
            </div>

            <div class="modal-body">
                {{-- From DB --}}
                @forelse($recent_activities as $activity)
                    <div class="modal-act-item">
                        @php
                            $activityClass = match ($activity['color'] ?? 'amber') {
                                'green' => 'act-bg-green',
                                'red' => 'act-bg-red',
                                'blue' => 'act-bg-blue',
                                'purple' => 'act-bg-purple',
                                default => 'act-bg-amber',
                            };

                            $activityIcon = match ($activity['color'] ?? 'amber') {
                                'green' => '✅',
                                'red' => '❌',
                                'blue' => '📋',
                                'purple' => '👤',
                                default => '⚠️',
                            };
                        @endphp

                        <div class="modal-act-type {{ $activityClass }}">
                            {{ $activityIcon }}
                        </div>
                        <div class="modal-act-body">
                            <div class="modal-act-name">{{ $activity['text'] }}</div>
                            <div class="modal-act-desc">Dilakukan oleh sistem absensi</div>
                        </div>
                        <div class="modal-act-meta">
                            <span class="modal-act-time">{{ $activity['time'] }}</span>
                            <span
                                class="modal-act-tag {{ $activity['color'] == 'green' ? 'trend-up' : ($activity['color'] == 'red' ? 'trend-down' : 'trend-flat') }}">
                                {{ $activity['color'] == 'green' ? 'Hadir' : ($activity['color'] == 'red' ? 'Alpha' : ($activity['color'] == 'blue' ? 'Update' : 'Info')) }}
                            </span>
                        </div>
                    </div>
                @empty
                    {{-- Dummy data fallback --}}
                @endforelse

                {{-- Dummy additional activities --}}
                <div class="modal-act-item">
                    <div class="modal-act-type" style="background:#dcfce7">✅</div>
                    <div class="modal-act-body">
                        <div class="modal-act-name">Muhammad Rizky — Hadir Sholat Subuh</div>
                        <div class="modal-act-desc">Kelas 2B &mdash; Dicatat oleh Ustadz Hamid</div>
                    </div>
                    <div class="modal-act-meta">
                        <span class="modal-act-time">04:58</span>
                        <span class="modal-act-tag trend-up">Hadir</span>
                    </div>
                </div>

                <div class="modal-act-item">
                    <div class="modal-act-type" style="background:#dbeafe">📋</div>
                    <div class="modal-act-body">
                        <div class="modal-act-name">Data santri Nur Halimah diperbarui</div>
                        <div class="modal-act-desc">Nomor wali santri diubah oleh admin</div>
                    </div>
                    <div class="modal-act-meta">
                        <span class="modal-act-time">08:22</span>
                        <span class="modal-act-tag trend-flat">Update</span>
                    </div>
                </div>

                <div class="modal-act-item">
                    <div class="modal-act-type" style="background:#fee2e2">❌</div>
                    <div class="modal-act-body">
                        <div class="modal-act-name">Siti Aminah — Tidak Hadir (Alpha)</div>
                        <div class="modal-act-desc">Kelas 3A &mdash; Alpha hari ke-3 berturut-turut</div>
                    </div>
                    <div class="modal-act-meta">
                        <span class="modal-act-time">Kemarin</span>
                        <span class="modal-act-tag trend-down">Alpha</span>
                    </div>
                </div>

                <div class="modal-act-item">
                    <div class="modal-act-type" style="background:#fffbeb">📢</div>
                    <div class="modal-act-body">
                        <div class="modal-act-name">Pengumuman Libur Maulid diterbitkan</div>
                        <div class="modal-act-desc">Notifikasi dikirim ke 156 wali santri via WhatsApp</div>
                    </div>
                    <div class="modal-act-meta">
                        <span class="modal-act-time">Kemarin</span>
                        <span class="modal-act-tag trend-flat">Info</span>
                    </div>
                </div>

                <div class="modal-act-item">
                    <div class="modal-act-type" style="background:#ede9fe">👤</div>
                    <div class="modal-act-body">
                        <div class="modal-act-name">Santri baru terdaftar — Farhan Al-Farizi</div>
                        <div class="modal-act-desc">Kelas 1A &mdash; Data lengkap telah diinput</div>
                    </div>
                    <div class="modal-act-meta">
                        <span class="modal-act-time">2 hari lalu</span>
                        <span class="modal-act-tag trend-up">Baru</span>
                    </div>
                </div>

                <div class="modal-act-item">
                    <div class="modal-act-type" style="background:#dcfce7">✅</div>
                    <div class="modal-act-body">
                        <div class="modal-act-name">Laporan Mingguan Ke-18 dibuat otomatis</div>
                        <div class="modal-act-desc">PDF tersedia untuk diunduh di menu laporan</div>
                    </div>
                    <div class="modal-act-meta">
                        <span class="modal-act-time">2 hari lalu</span>
                        <span class="modal-act-tag trend-flat">Laporan</span>
                    </div>
                </div>

                <div class="modal-act-item">
                    <div class="modal-act-type" style="background:#fee2e2">⚠️</div>
                    <div class="modal-act-body">
                        <div class="modal-act-name">Peringatan: Abdullah Hakim 3x Alpha minggu ini</div>
                        <div class="modal-act-desc">Wali santri telah dihubungi oleh wali asrama</div>
                    </div>
                    <div class="modal-act-meta">
                        <span class="modal-act-time">3 hari lalu</span>
                        <span class="modal-act-tag trend-down">Peringatan</span>
                    </div>
                </div>

                <div class="modal-act-item">
                    <div class="modal-act-type" style="background:#dbeafe">📋</div>
                    <div class="modal-act-body">
                        <div class="modal-act-name">Rekap absensi Senin — Kelas 1A, 1B, 2A selesai</div>
                        <div class="modal-act-desc">Total 3 kelas, 87 santri tercatat</div>
                    </div>
                    <div class="modal-act-meta">
                        <span class="modal-act-time">3 hari lalu</span>
                        <span class="modal-act-tag trend-flat">Rekap</span>
                    </div>
                </div>
            </div>

            <div class="modal-ftr">
                <span class="modal-ftr-txt">Menampilkan 10+ aktivitas terakhir</span>
                <a href="#" class="btn-primary" style="font-size:.75rem;padding:7px 16px;">
                    Lihat Semua di Halaman Laporan
                </a>
            </div>
        </div>
    </div>


    {{-- ═══════════════════════════════════════════
     MODAL — NOTIFIKASI (ALL)
════════════════════════════════════════════ --}}
    <div class="modal-overlay" id="notifModal">
        <div class="modal-box">
            <div class="modal-hdr">
                <div class="modal-hdr-title">
                    <div class="modal-hdr-ico">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9" />
                            <path d="M13.73 21a2 2 0 0 1-3.46 0" />
                        </svg>
                    </div>
                    Semua Notifikasi
                    <span class="notif-badge">3 Belum dibaca</span>
                </div>
                <button class="modal-close" onclick="closeModal('notifModal')">✕</button>
            </div>

            <div class="modal-body">
                <div class="modal-notif-item unread">
                    <div class="notif-ava" style="background:#dcfce7">✅</div>
                    <div class="notif-text">
                        <div class="notif-title">Absensi Subuh Selesai</div>
                        <div class="notif-desc">Semua kelas telah melaporkan kehadiran Subuh hari ini. Total 148 santri
                            hadir.</div>
                    </div>
                    <div class="notif-time">05:30</div>
                </div>

                <div class="modal-notif-item unread">
                    <div class="notif-ava" style="background:#fff1f2">🔔</div>
                    <div class="notif-text">
                        <div class="notif-title">Ahmad Fauzi — Izin Sakit</div>
                        <div class="notif-desc">Wali santri melaporkan izin sakit mulai hari ini. Dokter: Sakit flu & demam
                            ringan.</div>
                    </div>
                    <div class="notif-time">06:12</div>
                </div>

                <div class="modal-notif-item unread">
                    <div class="notif-ava" style="background:#eff6ff">📢</div>
                    <div class="notif-text">
                        <div class="notif-title">Pengumuman Libur Maulid Nabi</div>
                        <div class="notif-desc">Pengumuman baru diterbitkan: libur 2 hari pada 27-28 September. Notifikasi
                            terkirim ke 156 wali santri.</div>
                    </div>
                    <div class="notif-time">07:00</div>
                </div>

                <div class="modal-notif-item">
                    <div class="notif-ava" style="background:#fdf4ff">📊</div>
                    <div class="notif-text">
                        <div class="notif-title">Laporan Mingguan Siap Diunduh</div>
                        <div class="notif-desc">Laporan rekap absensi minggu ke-18 sudah bisa diunduh dalam format PDF dan
                            Excel.</div>
                    </div>
                    <div class="notif-time">Kemarin</div>
                </div>

                <div class="modal-notif-item">
                    <div class="notif-ava" style="background:#fffbeb">⚠️</div>
                    <div class="notif-text">
                        <div class="notif-title">3 Santri Alpha Berturut-turut</div>
                        <div class="notif-desc">Muhammad Rizky, Siti Aminah, dan Abdullah Hakim perlu tindak lanjut segera
                            oleh wali asrama.</div>
                    </div>
                    <div class="notif-time">Kemarin</div>
                </div>

                <div class="modal-notif-item">
                    <div class="notif-ava" style="background:var(--emerald-50)">👤</div>
                    <div class="notif-text">
                        <div class="notif-title">Santri Baru Terdaftar</div>
                        <div class="notif-desc">Nur Halimah berhasil didaftarkan ke Kelas 1A. Data wali sudah lengkap.
                        </div>
                    </div>
                    <div class="notif-time">2 hari lalu</div>
                </div>

                <div class="modal-notif-item">
                    <div class="notif-ava" style="background:#f0fdf4">📈</div>
                    <div class="notif-text">
                        <div class="notif-title">Tingkat Kehadiran Minggu Ini: 94.5%</div>
                        <div class="notif-desc">Peningkatan 2.3% dibanding minggu lalu. Kelas 2A menjadi kelas terbaik
                            dengan 100% kehadiran.</div>
                    </div>
                    <div class="notif-time">3 hari lalu</div>
                </div>

                <div class="modal-notif-item">
                    <div class="notif-ava" style="background:#fef2f2">🚨</div>
                    <div class="notif-text">
                        <div class="notif-title">Peringatan: Data Absensi Belum Lengkap</div>
                        <div class="notif-desc">Kelas 3B belum melaporkan absensi Isya kemarin malam. Harap segera
                            dilengkapi.</div>
                    </div>
                    <div class="notif-time">3 hari lalu</div>
                </div>
            </div>

            <div class="modal-ftr">
                <span class="modal-ftr-txt">8 notifikasi &mdash; 3 belum dibaca</span>
                <button class="btn-primary" style="font-size:.75rem;padding:7px 16px;">
                    Tandai Semua Dibaca
                </button>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        // ─── COLOR TOKENS ───────────────────────────────
        const C = {
            emerald: '#059669',
            emeraldL: 'rgba(5,150,105,.12)',
            amber: '#f59e0b',
            amberL: 'rgba(245,158,11,.10)',
            red: '#ef4444',
            redL: 'rgba(239,68,68,.09)',
            slate: '#94a3b8',
        };

        Chart.defaults.font.family = "'Outfit', sans-serif";
        Chart.defaults.color = '#94a3b8';

        // ─── CHART DATA dari database (DashboardController::buildChartData) ─────────────
        const DATA = @json($chartData);

        // ─── SHARED CHART OPTIONS ───────────────────────
        function makeOptions(type, period) {
            const isTahunan = period === 'tahunan';
            const base = {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false
                },
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            boxWidth: 10,
                            padding: 18,
                            font: {
                                size: 11,
                                weight: '600'
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        padding: 14,
                        cornerRadius: 12,
                        titleFont: {
                            size: 12,
                            weight: '700'
                        },
                        bodyFont: {
                            size: 11
                        },
                        callbacks: isTahunan ? {
                            label: ctx => ` ${ctx.dataset.label}: ${ctx.raw}%`
                        } : undefined,
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        border: {
                            display: false
                        },
                        ticks: {
                            font: {
                                size: 11,
                                weight: '600'
                            }
                        }
                    },
                    y: {
                        grid: {
                            color: '#f1f5f9',
                            lineWidth: 1
                        },
                        border: {
                            display: false,
                            dash: [4, 4]
                        },
                        ticks: {
                            font: {
                                size: 10
                            },
                            callback: isTahunan ? v => v + '%' : undefined,
                            stepSize: isTahunan ? 10 : undefined,
                        },
                        min: isTahunan ? 0 : undefined,
                        max: isTahunan ? 100 : undefined,
                    }
                }
            };

            if (type === 'line') {
                base.elements = {
                    line: {
                        tension: 0.4
                    }
                };
            }
            return base;
        }

        function makeDatasets(period, type) {
            const d = DATA[period];
            const isBar = type === 'bar';
            const radius = isBar ? 6 : 4;

            return [{
                    label: 'Hadir',
                    data: d.hadir,
                    backgroundColor: isBar ? C.emeraldL : C.emeraldL,
                    borderColor: C.emerald,
                    borderWidth: 2,
                    borderRadius: isBar ? radius : 0,
                    borderSkipped: false,
                    pointBackgroundColor: C.emerald,
                    pointRadius: isBar ? 0 : 4,
                    fill: !isBar,
                },
                {
                    label: 'Izin/Sakit',
                    data: d.izin,
                    backgroundColor: isBar ? C.amberL : C.amberL,
                    borderColor: C.amber,
                    borderWidth: 2,
                    borderRadius: isBar ? radius : 0,
                    borderSkipped: false,
                    pointBackgroundColor: C.amber,
                    pointRadius: isBar ? 0 : 4,
                    fill: !isBar,
                },
                {
                    label: 'Alpha',
                    data: d.alpha,
                    backgroundColor: isBar ? C.redL : C.redL,
                    borderColor: C.red,
                    borderWidth: 2,
                    borderRadius: isBar ? radius : 0,
                    borderSkipped: false,
                    pointBackgroundColor: C.red,
                    pointRadius: isBar ? 0 : 4,
                    fill: !isBar,
                },
            ];
        }

        // ─── INIT CHART ──────────────────────────────────
        let currentPeriod = '7hari';
        let currentType = 'bar';
        let mainChart;

        function buildChart() {
            const ctx = document.getElementById('mainChart').getContext('2d');
            if (mainChart) mainChart.destroy();

            mainChart = new Chart(ctx, {
                type: currentType,
                data: {
                    labels: DATA[currentPeriod].labels,
                    datasets: makeDatasets(currentPeriod, currentType),
                },
                options: makeOptions(currentType, currentPeriod),
            });
        }

        buildChart();

        // period change
        document.getElementById('chartPeriod').addEventListener('change', function() {
            currentPeriod = this.value;
            buildChart();
        });

        // type tabs
        document.querySelectorAll('.chart-tab-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                document.querySelectorAll('.chart-tab-btn').forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                currentType = btn.dataset.type;
                buildChart();
            });
        });

        // ─── DONUTS ─────────────────────────────────────

        function makeDonut(id, val, color) {
            const rest = Math.max(0, total - val);
            new Chart(document.getElementById(id), {
                type: 'doughnut',
                data: {
                    datasets: [{
                        data: [val, rest],
                        backgroundColor: [color, '#f1f5f9'],
                        borderWidth: 0,
                        borderRadius: 5,
                    }]
                },
                options: {
                    cutout: '74%',
                    responsive: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            enabled: false
                        }
                    },
                    animation: {
                        animateRotate: true,
                        duration: 1100,
                        easing: 'easeOutCubic'
                    }
                }
            });
        }
        const el = document.getElementById('dashboardStats');
        const total = parseInt(el.dataset.total) || 0;
        const hadir = parseInt(el.dataset.hadir) || 0;
        const izin = parseInt(el.dataset.izin) || 0;
        const alpha = parseInt(el.dataset.alpha) || 0;
        makeDonut('donutHadir', hadir, C.emerald);
        makeDonut('donutIzin', izin, C.amber);
        makeDonut('donutAlpha', alpha, C.red);

        // ─── SHOLAT COUNTDOWN ───────────────────────────
        (function() {
            const times = {
                subuh: '{{ $jadwalSholat ? $jadwalSholat['subuh'] : '04:30' }}',
                terbit: '{{ $jadwalSholat ? $jadwalSholat['terbit'] : '05:50' }}',
                dzuhur: '{{ $jadwalSholat ? $jadwalSholat['dzuhur'] : '11:55' }}',
                ashar: '{{ $jadwalSholat ? $jadwalSholat['ashar'] : '15:15' }}',
                maghrib: '{{ $jadwalSholat ? $jadwalSholat['maghrib'] : '17:48' }}',
                isya: '{{ $jadwalSholat ? $jadwalSholat['isya'] : '19:00' }}',
            };

            const labels = {
                subuh: 'Subuh',
                terbit: 'Terbit',
                dzuhur: 'Dzuhur',
                ashar: 'Ashar',
                maghrib: 'Maghrib',
                isya: 'Isya'
            };

            function toMins(str) {
                const [h, m] = str.split(':').map(Number);
                return h * 60 + m;
            }

            function tick() {
                const now = new Date();
                const nowMins = now.getHours() * 60 + now.getMinutes();
                const keys = Object.keys(times);
                const minsArr = keys.map(k => toMins(times[k]));

                // Cari waktu sholat aktif (waktu yang sudah lewat, paling terakhir)
                let activeIdx = -1;
                for (let i = minsArr.length - 1; i >= 0; i--) {
                    if (nowMins >= minsArr[i]) {
                        activeIdx = i;
                        break;
                    }
                }

                // Cari waktu sholat berikutnya
                let nextIdx = activeIdx + 1;
                if (nextIdx >= keys.length) nextIdx = -1; // sudah lewat isya

                // Highlight card
                keys.forEach((k, i) => {
                    const el = document.getElementById('sholat-' + k);
                    if (!el) return;
                    const isActive = i === activeIdx;
                    el.classList.toggle('now', isActive);
                    let tag = el.querySelector('.sholat-now-tag');
                    if (isActive && !tag) {
                        tag = document.createElement('span');
                        tag.className = 'sholat-now-tag';
                        tag.textContent = 'Sekarang';
                        el.appendChild(tag);
                    } else if (!isActive && tag) {
                        tag.remove();
                    }
                });

                // Countdown
                const nextEl = document.getElementById('nextSholat');
                const countEl = document.getElementById('countdownTimer');

                if (nextIdx !== -1) {
                    const diffMins = minsArr[nextIdx] - nowMins;
                    const h = Math.floor(diffMins / 60);
                    const m = diffMins % 60;
                    nextEl.textContent = labels[keys[nextIdx]];
                    countEl.textContent = (h > 0 ? h + 'j ' : '') + m + ' menit lagi';
                } else {
                    // Hitung sampai subuh besok
                    const minsToMidnight = 1440 - nowMins;
                    const diffMins = minsToMidnight + minsArr[0];
                    const h = Math.floor(diffMins / 60);
                    const m = diffMins % 60;
                    nextEl.textContent = 'Subuh Besok';
                    countEl.textContent = (h > 0 ? h + 'j ' : '') + m + ' menit lagi';
                }
            }

            tick();
            setInterval(tick, 30000);
        })();

        // ─── MODAL ──────────────────────────────────────
        function openModal(id) {
            document.getElementById(id).classList.add('open');
            document.body.style.overflow = 'hidden';
        }

        function closeModal(id) {
            document.getElementById(id).classList.remove('open');
            document.body.style.overflow = '';
        }

        // close on overlay click
        document.querySelectorAll('.modal-overlay').forEach(el => {
            el.addEventListener('click', function(e) {
                if (e.target === this) closeModal(this.id);
            });
        });

        // close on ESC
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') {
                document.querySelectorAll('.modal-overlay.open').forEach(m => closeModal(m.id));
            }
        });
    </script>
@endpush
