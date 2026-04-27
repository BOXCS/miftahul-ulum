<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pondok Pesantren Miftahul Ulum Jember</title>
    <link href="https://fonts.googleapis.com/css2?family=Scheherazade+New:wght@400;700&family=Playfair+Display:wght@400;600;700;900&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root {
            --green: #064E3B;
            --green-mid: #065f46;
            --green-light: #047857;
            --green-pale: #d1fae5;
            --green-ultra: #ecfdf5;
            --gold: #B8974A;
            --gold-light: #D4AF68;
            --white: #FFFFFF;
            --off-white: #F9FAFB;
            --text: #1a1a1a;
            --text-muted: #6b7280;
            --border: rgba(6, 78, 59, 0.12);
        }

        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--white);
            color: var(--text);
            overflow-x: hidden;
        }

        /* ── CUSTOM SCROLLBAR ── */
        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: var(--green-ultra);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--green);
            border-radius: 3px;
        }

        /* ── ARABIC PATTERN SVG ── */
        .arabesque {
            position: absolute;
            opacity: 0.07;
            pointer-events: none;
        }

        /* ── NAVBAR ── */
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            padding: 20px 0;
            transition: all 0.4s ease;
        }

        .navbar.scrolled {
            background: rgba(6, 78, 59, 0.97);
            backdrop-filter: blur(20px);
            padding: 12px 0;
            box-shadow: 0 4px 40px rgba(0, 0, 0, 0.3);
        }

        .nav-inner {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .nav-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }

        .nav-logo-emblem {
            width: 44px;
            height: 44px;
            background: var(--gold);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            color: var(--white);
            transform: rotate(0deg);
            transition: transform 0.3s;
            flex-shrink: 0;
        }

        .nav-logo:hover .nav-logo-emblem {
            transform: rotate(15deg);
        }

        .nav-logo-text {
            line-height: 1.1;
        }

        .nav-logo-text span:first-child {
            display: block;
            font-family: 'Playfair Display', serif;
            font-size: 1.05rem;
            font-weight: 700;
            color: var(--white);
        }

        .nav-logo-text span:last-child {
            font-size: 0.7rem;
            color: var(--gold-light);
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 8px;
            list-style: none;
        }

        .nav-links a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            padding: 8px 14px;
            border-radius: 8px;
            transition: all 0.2s;
        }

        .nav-links a:hover {
            color: var(--white);
            background: rgba(255, 255, 255, 0.1);
        }

        .nav-cta {
            background: var(--gold) !important;
            color: var(--white) !important;
            padding: 10px 20px !important;
        }

        .nav-cta:hover {
            background: var(--gold-light) !important;
            transform: translateY(-1px);
        }

        .hamburger {
            display: none;
            flex-direction: column;
            gap: 5px;
            cursor: pointer;
            padding: 8px;
            border: none;
            background: none;
        }

        .hamburger span {
            display: block;
            width: 24px;
            height: 2px;
            background: white;
            border-radius: 2px;
            transition: all 0.3s;
        }

        /* ── HERO ── */
        #hero {
            min-height: 100vh;
            background: var(--green);
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
        }

        .hero-bg {
            position: absolute;
            inset: 0;
            background:
                radial-gradient(ellipse 80% 60% at 70% 50%, rgba(4, 120, 87, 0.6) 0%, transparent 60%),
                radial-gradient(ellipse 40% 40% at 20% 80%, rgba(184, 151, 74, 0.2) 0%, transparent 50%);
        }

        .hero-geometric {
            position: absolute;
            right: -5%;
            top: 50%;
            transform: translateY(-50%);
            width: 55vw;
            max-width: 700px;
            opacity: 0.06;
        }

        .hero-content {
            position: relative;
            max-width: 1200px;
            margin: 0 auto;
            padding: 120px 30px 80px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            align-items: center;
            gap: 60px;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(184, 151, 74, 0.15);
            border: 1px solid rgba(184, 151, 74, 0.4);
            color: var(--gold-light);
            padding: 6px 16px;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 500;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: 24px;
            animation: fadeSlideDown 0.8s ease forwards;
        }

        .hero-arabic {
            font-family: 'Scheherazade New', serif;
            font-size: 2.2rem;
            color: var(--gold-light);
            opacity: 0.8;
            line-height: 1.4;
            margin-bottom: 12px;
            animation: fadeSlideDown 0.9s ease forwards;
        }

        .hero-title {
            font-family: 'Playfair Display', serif;
            font-size: clamp(2.4rem, 4.5vw, 3.8rem);
            font-weight: 900;
            color: var(--white);
            line-height: 1.1;
            margin-bottom: 20px;
            animation: fadeSlideDown 1s ease forwards;
        }

        .hero-title em {
            font-style: normal;
            color: var(--gold-light);
            display: block;
        }

        .hero-subtitle {
            font-size: 1.1rem;
            color: rgba(255, 255, 255, 0.7);
            line-height: 1.7;
            margin-bottom: 40px;
            max-width: 460px;
            animation: fadeSlideDown 1.1s ease forwards;
        }

        .hero-actions {
            display: flex;
            gap: 16px;
            flex-wrap: wrap;
            animation: fadeSlideDown 1.2s ease forwards;
        }

        .btn-gold {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: var(--gold);
            color: var(--white);
            padding: 16px 32px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.95rem;
            text-decoration: none;
            transition: all 0.3s;
            border: 2px solid var(--gold);
        }

        .btn-gold:hover {
            background: var(--gold-light);
            border-color: var(--gold-light);
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(184, 151, 74, 0.4);
        }

        .btn-outline-white {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: transparent;
            color: var(--white);
            padding: 16px 32px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.95rem;
            text-decoration: none;
            border: 2px solid rgba(255, 255, 255, 0.4);
            transition: all 0.3s;
        }

        .btn-outline-white:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: white;
            transform: translateY(-2px);
        }

        .hero-stats {
            display: flex;
            gap: 32px;
            margin-top: 50px;
            padding-top: 50px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            animation: fadeSlideDown 1.3s ease forwards;
        }

        .hero-stat {
            text-align: left;
        }

        .hero-stat-num {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            font-weight: 700;
            color: var(--gold-light);
            line-height: 1;
        }

        .hero-stat-label {
            font-size: 0.75rem;
            color: rgba(255, 255, 255, 0.5);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 4px;
        }

        .hero-visual {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .hero-img-frame {
            position: relative;
            width: 100%;
            max-width: 480px;
        }

        .hero-img-main {
            width: 100%;
            aspect-ratio: 4/5;
            object-fit: cover;
            border-radius: 24px;
            display: block;
            filter: brightness(0.85) saturate(1.1);
            animation: fadeScale 1.2s ease forwards;
        }

        .hero-img-card {
            position: absolute;
            background: white;
            border-radius: 16px;
            padding: 16px 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            animation: floatCard 3s ease-in-out infinite;
        }

        .hero-card-1 {
            bottom: -20px;
            left: -30px;
        }

        .hero-card-2 {
            top: 30px;
            right: -30px;
        }

        .hero-img-card .card-num {
            font-family: 'Playfair Display', serif;
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--green);
            line-height: 1;
        }

        .hero-img-card .card-lbl {
            font-size: 0.7rem;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .hero-img-ring {
            position: absolute;
            inset: -20px;
            border: 2px dashed rgba(184, 151, 74, 0.3);
            border-radius: 32px;
            animation: spin 30s linear infinite;
        }

        /* ── ANNOUNCEMENT TICKER ── */
        .ticker-wrap {
            background: var(--gold);
            overflow: hidden;
            padding: 10px 0;
        }

        .ticker-inner {
            display: flex;
            gap: 60px;
            animation: ticker 25s linear infinite;
            white-space: nowrap;
        }

        .ticker-item {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            color: white;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .ticker-item i {
            font-size: 0.75rem;
            opacity: 0.7;
        }

        /* ── SECTION BASE ── */
        .section {
            padding: 100px 30px;
        }

        .section-inner {
            max-width: 1200px;
            margin: 0 auto;
        }

        .section-tag {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: var(--green);
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 16px;
        }

        .section-tag::before {
            content: '';
            width: 24px;
            height: 2px;
            background: var(--gold);
        }

        .section-title-big {
            font-family: 'Playfair Display', serif;
            font-size: clamp(2rem, 3.5vw, 3rem);
            font-weight: 700;
            color: var(--green);
            line-height: 1.2;
            margin-bottom: 16px;
        }

        .section-title-big span {
            color: var(--gold);
        }

        .section-subtitle {
            font-size: 1.05rem;
            color: var(--text-muted);
            line-height: 1.7;
            max-width: 560px;
        }

        /* ── ABOUT ── */
        #about {
            background: var(--white);
        }

        .about-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 80px;
            align-items: center;
        }

        .about-img-wrap {
            position: relative;
        }

        .about-img-main {
            width: 90%;
            aspect-ratio: 4/5;
            object-fit: cover;
            border-radius: 24px;
            display: block;
            box-shadow: 0 30px 80px rgba(6, 78, 59, 0.2);
        }

        .about-img-accent {
            position: absolute;
            bottom: -30px;
            right: 0;
            width: 55%;
            aspect-ratio: 1;
            object-fit: cover;
            border-radius: 20px;
            border: 6px solid var(--white);
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.15);
        }

        .about-ornament {
            position: absolute;
            top: -20px;
            left: -20px;
            width: 120px;
            height: 120px;
            border: 3px solid var(--green-pale);
            border-radius: 20px;
            z-index: -1;
        }

        .about-badge-float {
            position: absolute;
            top: 40px;
            right: -10px;
            background: var(--green);
            color: white;
            padding: 14px 18px;
            border-radius: 16px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(6, 78, 59, 0.4);
        }

        .about-badge-float .num {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            font-weight: 700;
            color: var(--gold-light);
            line-height: 1;
        }

        .about-badge-float .lbl {
            font-size: 0.65rem;
            color: rgba(255, 255, 255, 0.7);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .about-text {
            padding-top: 20px;
        }

        .about-lead {
            font-family: 'Scheherazade New', serif;
            font-size: 1.6rem;
            color: var(--gold);
            margin-bottom: 20px;
            line-height: 1.5;
        }

        .about-body {
            font-size: 1rem;
            line-height: 1.8;
            color: #4b5563;
            margin-bottom: 16px;
        }

        .about-pillars {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            margin-top: 32px;
        }

        .pillar-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 16px;
            background: var(--green-ultra);
            border-radius: 12px;
            border-left: 3px solid var(--green);
            transition: all 0.3s;
        }

        .pillar-item:hover {
            background: var(--green-pale);
            transform: translateX(4px);
        }

        .pillar-icon {
            width: 36px;
            height: 36px;
            background: var(--green);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--gold-light);
            font-size: 0.9rem;
            flex-shrink: 0;
        }

        .pillar-text h6 {
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--green);
            margin-bottom: 2px;
        }

        .pillar-text p {
            font-size: 0.75rem;
            color: var(--text-muted);
            line-height: 1.4;
        }

        /* ── STATS BAR ── */
        .stats-bar {
            background: var(--green);
            padding: 60px 30px;
            position: relative;
            overflow: hidden;
        }

        .stats-bar::before {
            content: '';
            position: absolute;
            inset: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }

        .stats-grid {
            max-width: 1100px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 20px;
            text-align: center;
        }

        .stat-item {
            position: relative;
        }

        .stat-item:not(:last-child)::after {
            content: '';
            position: absolute;
            right: 0;
            top: 20%;
            bottom: 20%;
            width: 1px;
            background: rgba(255, 255, 255, 0.15);
        }

        .stat-num {
            font-family: 'Playfair Display', serif;
            font-size: 2.8rem;
            font-weight: 700;
            color: var(--gold-light);
            line-height: 1;
            display: block;
        }

        .stat-unit {
            font-size: 1.5rem;
            color: var(--gold-light);
        }

        .stat-lbl {
            font-size: 0.8rem;
            color: rgba(255, 255, 255, 0.6);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 8px;
            display: block;
        }

        .stat-icon {
            font-size: 1.4rem;
            color: rgba(184, 151, 74, 0.4);
            margin-bottom: 10px;
            display: block;
        }

        /* ── PROGRAMS ── */
        #program {
            background: var(--off-white);
            position: relative;
            overflow: hidden;
        }

        .programs-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 28px;
            margin-top: 60px;
        }

        .program-card {
            background: var(--white);
            border-radius: 24px;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            border: 1px solid var(--border);
            position: relative;
        }

        .program-card:hover {
            transform: translateY(-12px);
            box-shadow: 0 30px 80px rgba(6, 78, 59, 0.15);
        }

        .program-card-top {
            height: 8px;
            background: linear-gradient(90deg, var(--green), var(--gold));
        }

        .program-card-body {
            padding: 36px 32px;
        }

        .program-number {
            font-family: 'Playfair Display', serif;
            font-size: 3.5rem;
            font-weight: 700;
            color: var(--green-pale);
            line-height: 1;
            margin-bottom: 16px;
        }

        .program-icon-wrap {
            width: 56px;
            height: 56px;
            background: var(--green-ultra);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: var(--green);
            margin-bottom: 20px;
        }

        .program-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--green);
            margin-bottom: 10px;
        }

        .program-arabic {
            font-family: 'Scheherazade New', serif;
            font-size: 1.1rem;
            color: var(--gold);
            margin-bottom: 12px;
        }

        .program-desc {
            font-size: 0.9rem;
            line-height: 1.7;
            color: var(--text-muted);
            margin-bottom: 20px;
        }

        .program-features {
            list-style: none;
            margin-bottom: 24px;
        }

        .program-features li {
            font-size: 0.85rem;
            color: #374151;
            padding: 6px 0;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .program-features li:last-child {
            border-bottom: none;
        }

        .program-features li::before {
            content: '';
            width: 6px;
            height: 6px;
            background: var(--gold);
            border-radius: 50%;
            flex-shrink: 0;
        }

        .program-link {
            color: var(--green);
            font-size: 0.85rem;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: gap 0.2s;
        }

        .program-link:hover {
            gap: 10px;
        }

        /* ── KEUNGGULAN / WHY ── */
        #why {
            background: var(--green);
            position: relative;
            overflow: hidden;
        }

        .why-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 80px;
            align-items: center;
        }

        .why-text .section-tag {
            color: var(--gold-light);
        }

        .why-text .section-tag::before {
            background: var(--gold-light);
        }

        .why-text .section-title-big {
            color: var(--white);
        }

        .why-text .section-title-big span {
            color: var(--gold-light);
        }

        .why-text .section-subtitle {
            color: rgba(255, 255, 255, 0.65);
        }

        .why-list {
            margin-top: 40px;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .why-item {
            display: flex;
            gap: 16px;
            padding: 20px 24px;
            background: rgba(255, 255, 255, 0.06);
            border-radius: 16px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s;
        }

        .why-item:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateX(6px);
        }

        .why-item-icon {
            width: 44px;
            height: 44px;
            background: rgba(184, 151, 74, 0.2);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--gold-light);
            font-size: 1.1rem;
            flex-shrink: 0;
        }

        .why-item-text h5 {
            color: var(--white);
            font-size: 0.95rem;
            font-weight: 600;
            margin-bottom: 4px;
        }

        .why-item-text p {
            color: rgba(255, 255, 255, 0.55);
            font-size: 0.82rem;
            line-height: 1.5;
        }

        .why-visual {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .why-card {
            background: rgba(255, 255, 255, 0.07);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 20px;
            padding: 28px;
            backdrop-filter: blur(10px);
            transition: all 0.3s;
        }

        .why-card:hover {
            background: rgba(255, 255, 255, 0.12);
        }

        .why-card-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 20px;
        }

        .why-card-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.1rem;
            color: var(--white);
            font-weight: 600;
        }

        .why-card-badge {
            background: rgba(184, 151, 74, 0.25);
            color: var(--gold-light);
            padding: 4px 12px;
            border-radius: 50px;
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .why-progress {
            margin-bottom: 10px;
        }

        .why-progress-label {
            display: flex;
            justify-content: space-between;
            margin-bottom: 6px;
        }

        .why-progress-label span:first-child {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.8rem;
        }

        .why-progress-label span:last-child {
            color: var(--gold-light);
            font-size: 0.8rem;
            font-weight: 600;
        }

        .progress-bar-wrap {
            height: 6px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 3px;
            overflow: hidden;
        }

        .progress-bar-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--gold), var(--gold-light));
            border-radius: 3px;
            transition: width 1.5s ease;
        }

        .schedule-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .schedule-item {
            display: flex;
            flex-direction: column;
        }

        .schedule-time {
            font-size: 0.75rem;
            color: var(--gold-light);
            font-weight: 600;
        }

        .schedule-activity {
            font-size: 0.8rem;
            color: rgba(255, 255, 255, 0.65);
        }

        /* ── GALLERY ── */
        #gallery {
            background: var(--white);
        }

        .gallery-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr;
            grid-template-rows: 280px 280px;
            gap: 16px;
            margin-top: 50px;
        }

        .gallery-item {
            border-radius: 20px;
            overflow: hidden;
            position: relative;
            cursor: pointer;
        }

        .gallery-item:first-child {
            grid-row: 1 / 3;
        }

        .gallery-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.6s ease;
            display: block;
        }

        .gallery-item:hover .gallery-img {
            transform: scale(1.08);
        }

        .gallery-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, transparent 40%, rgba(6, 78, 59, 0.85) 100%);
            opacity: 0;
            transition: opacity 0.3s;
            display: flex;
            align-items: flex-end;
            padding: 20px;
        }

        .gallery-item:hover .gallery-overlay {
            opacity: 1;
        }

        .gallery-overlay-text {
            color: white;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .gallery-more {
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--green-ultra);
            border-radius: 20px;
            text-decoration: none;
            flex-direction: column;
            gap: 8px;
            border: 2px dashed var(--green-pale);
            transition: all 0.3s;
        }

        .gallery-more:hover {
            background: var(--green-pale);
        }

        .gallery-more i {
            font-size: 2rem;
            color: var(--green);
        }

        .gallery-more span {
            font-size: 0.85rem;
            color: var(--green);
            font-weight: 500;
        }

        /* ── FASILITAS ── */
        #fasilitas {
            background: var(--green-ultra);
        }

        .fasilitas-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-top: 50px;
        }

        .fasilitas-card {
            background: var(--white);
            border-radius: 20px;
            padding: 28px 24px;
            text-align: center;
            border: 1px solid var(--border);
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
        }

        .fasilitas-card::before {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--green), var(--gold));
            transform: scaleX(0);
            transition: transform 0.3s;
        }

        .fasilitas-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 50px rgba(6, 78, 59, 0.12);
        }

        .fasilitas-card:hover::before {
            transform: scaleX(1);
        }

        .fasilitas-icon {
            width: 60px;
            height: 60px;
            background: var(--green-ultra);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            color: var(--green);
            margin: 0 auto 16px;
        }

        .fasilitas-name {
            font-weight: 600;
            font-size: 0.95rem;
            color: var(--green);
            margin-bottom: 6px;
        }

        .fasilitas-desc {
            font-size: 0.78rem;
            color: var(--text-muted);
            line-height: 1.5;
        }

        /* ── TESTIMONIALS ── */
        #testimoni {
            background: var(--white);
        }

        .testi-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
            margin-top: 50px;
        }

        .testi-card {
            background: var(--off-white);
            border-radius: 20px;
            padding: 32px 28px;
            position: relative;
            border: 1px solid var(--border);
            transition: all 0.3s;
        }

        .testi-card:hover {
            box-shadow: 0 20px 50px rgba(6, 78, 59, 0.1);
            transform: translateY(-4px);
        }

        .testi-quote {
            font-family: 'Playfair Display', serif;
            font-size: 4rem;
            color: var(--green-pale);
            line-height: 0.8;
            margin-bottom: 16px;
            display: block;
        }

        .testi-text {
            font-size: 0.9rem;
            line-height: 1.75;
            color: #374151;
            margin-bottom: 24px;
            font-style: italic;
        }

        .testi-author {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .testi-avatar {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: var(--green);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--gold-light);
            font-family: 'Playfair Display', serif;
            font-size: 1.1rem;
            font-weight: 700;
            flex-shrink: 0;
        }

        .testi-name {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--green);
        }

        .testi-role {
            font-size: 0.75rem;
            color: var(--text-muted);
        }

        .testi-stars {
            color: var(--gold);
            font-size: 0.8rem;
            margin-bottom: 2px;
        }

        /* ── PENDAFTARAN ── */
        #pendaftaran {
            background: var(--green);
            position: relative;
            overflow: hidden;
        }

        #pendaftaran::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 800px;
            height: 800px;
            background: radial-gradient(circle, rgba(184, 151, 74, 0.12) 0%, transparent 60%);
        }

        .daftar-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 80px;
            align-items: start;
        }

        .daftar-text {
            padding-top: 10px;
        }

        .daftar-title {
            font-family: 'Playfair Display', serif;
            font-size: 2.4rem;
            font-weight: 700;
            color: var(--white);
            line-height: 1.2;
            margin-bottom: 20px;
        }

        .daftar-title span {
            color: var(--gold-light);
        }

        .daftar-desc {
            color: rgba(255, 255, 255, 0.7);
            line-height: 1.7;
            margin-bottom: 32px;
            font-size: 1rem;
        }

        .daftar-info-list {
            display: flex;
            flex-direction: column;
            gap: 14px;
            margin-bottom: 36px;
        }

        .daftar-info-row {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .daftar-info-icon {
            width: 36px;
            height: 36px;
            background: rgba(184, 151, 74, 0.2);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--gold-light);
            font-size: 0.9rem;
            flex-shrink: 0;
        }

        .daftar-info-text {
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.9rem;
        }

        .daftar-info-text strong {
            color: white;
        }

        .daftar-btns {
            display: flex;
            gap: 14px;
            flex-wrap: wrap;
        }

        .btn-wa {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: #25D366;
            color: white;
            padding: 15px 28px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.9rem;
            text-decoration: none;
            transition: all 0.3s;
        }

        .btn-wa:hover {
            background: #1ea952;
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(37, 211, 102, 0.4);
        }

        .daftar-form-card {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 24px;
            padding: 40px 36px;
            backdrop-filter: blur(10px);
        }

        .daftar-form-card h3 {
            font-family: 'Playfair Display', serif;
            color: white;
            font-size: 1.4rem;
            margin-bottom: 8px;
        }

        .daftar-form-card p {
            color: rgba(255, 255, 255, 0.55);
            font-size: 0.85rem;
            margin-bottom: 28px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            display: block;
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.82rem;
            margin-bottom: 8px;
            font-weight: 500;
        }

        .form-input {
            width: 100%;
            padding: 14px 16px;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 10px;
            color: white;
            font-size: 0.9rem;
            font-family: 'DM Sans', sans-serif;
            outline: none;
            transition: border-color 0.2s, background 0.2s;
        }

        .form-input::placeholder {
            color: rgba(255, 255, 255, 0.3);
        }

        .form-input:focus {
            border-color: var(--gold);
            background: rgba(255, 255, 255, 0.12);
        }

        .form-input option {
            color: var(--text);
            background: white;
        }

        .btn-submit {
            width: 100%;
            padding: 16px;
            background: var(--gold);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            font-family: 'DM Sans', sans-serif;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 6px;
        }

        .btn-submit:hover {
            background: var(--gold-light);
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(184, 151, 74, 0.4);
        }

        /* ── JADWAL HARIAN ── */
        #jadwal {
            background: var(--off-white);
        }

        .jadwal-timeline {
            position: relative;
            max-width: 920px;
            margin: 50px auto 0;
        }

        .jadwal-timeline::before {
            content: '';
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            top: 0;
            bottom: 0;
            width: 2px;
            background: linear-gradient(180deg, var(--green), var(--gold), var(--green));
        }

        .jadwal-item {
            display: grid;
            grid-template-columns: 1fr 30px 1fr;
            align-items: center;
            margin-bottom: 18px;
            position: relative;
        }

        /* GANJIL: konten kiri | titik | waktu kanan */
        .jadwal-item:nth-child(odd) .jadwal-time {
            grid-column: 3;
            grid-row: 1;
            padding-left: 18px;
            text-align: left;
        }

        .jadwal-item:nth-child(odd) .jadwal-dot {
            grid-column: 2;
            grid-row: 1;
        }

        .jadwal-item:nth-child(odd) .jadwal-content {
            grid-column: 1;
            grid-row: 1;
        }

        /* GENAP: waktu kiri | titik | konten kanan */
        .jadwal-item:nth-child(even) .jadwal-time {
            grid-column: 1;
            grid-row: 1;
            padding-right: 18px;
            text-align: right;
        }

        .jadwal-item:nth-child(even) .jadwal-dot {
            grid-column: 2;
            grid-row: 1;
        }

        .jadwal-item:nth-child(even) .jadwal-content {
            grid-column: 3;
            grid-row: 1;
        }

        .jadwal-time {
            font-size: 0.85rem;
            font-weight: 700;
            color: var(--green);
        }

        .jadwal-dot {
            width: 14px;
            height: 14px;
            background: var(--gold);
            border-radius: 50%;
            border: 3px solid var(--white);
            box-shadow: 0 0 0 3px var(--green-pale);
            position: relative;
            z-index: 1;
            justify-self: center;
        }

        .jadwal-content {
            background: white;
            border-radius: 12px;
            padding: 14px 18px;
            border: 1px solid var(--border);
            transition: all 0.3s;
        }

        .jadwal-content:hover {
            box-shadow: 0 8px 24px rgba(6, 78, 59, 0.1);
            transform: scale(1.02);
        }

        .jadwal-activity {
            font-weight: 600;
            font-size: 0.88rem;
            color: var(--green);
            margin-bottom: 2px;
        }

        .jadwal-note {
            font-size: 0.76rem;
            color: var(--text-muted);
        }

        /* ── KONTAK ── */
        #kontak {
            background: var(--white);
        }

        .kontak-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            align-items: start;
        }

        .kontak-info-list {
            display: flex;
            flex-direction: column;
            gap: 20px;
            margin-top: 30px;
        }

        .kontak-info-item {
            display: flex;
            gap: 16px;
            padding: 20px;
            background: var(--green-ultra);
            border-radius: 16px;
            transition: all 0.3s;
        }

        .kontak-info-item:hover {
            background: var(--green-pale);
            transform: translateX(4px);
        }

        .kontak-info-icon {
            width: 44px;
            height: 44px;
            background: var(--green);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--gold-light);
            font-size: 1rem;
            flex-shrink: 0;
        }

        .kontak-info-label {
            font-size: 0.75rem;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }

        .kontak-info-value {
            font-weight: 500;
            color: var(--text);
            font-size: 0.9rem;
        }

        .kontak-map {
            border-radius: 20px;
            overflow: hidden;
            height: 300px;
            background: var(--green-ultra);
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid var(--border);
            color: var(--text-muted);
            flex-direction: column;
            gap: 12px;
            margin-top: 30px;
        }

        .kontak-map i {
            font-size: 3rem;
            color: var(--green-pale);
        }

        .kontak-form {
            margin-top: 30px;
        }

        .kontak-form .form-group {
            margin-bottom: 16px;
        }

        .kontak-form label {
            display: block;
            font-size: 0.82rem;
            font-weight: 500;
            color: var(--green);
            margin-bottom: 6px;
        }

        .kontak-form input,
        .kontak-form textarea {
            width: 100%;
            padding: 13px 16px;
            border: 1.5px solid var(--border);
            border-radius: 10px;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.9rem;
            color: var(--text);
            outline: none;
            transition: border-color 0.2s;
            background: var(--white);
        }

        .kontak-form input:focus,
        .kontak-form textarea:focus {
            border-color: var(--green);
        }

        .kontak-form textarea {
            resize: none;
            height: 110px;
        }

        .btn-green {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: var(--green);
            color: white;
            padding: 14px 28px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.9rem;
            text-decoration: none;
            border: none;
            cursor: pointer;
            font-family: 'DM Sans', sans-serif;
            transition: all 0.3s;
        }

        .btn-green:hover {
            background: var(--green-mid);
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(6, 78, 59, 0.3);
        }

        /* ── FOOTER ── */
        footer {
            background: #031f18;
            padding: 70px 30px 30px;
            position: relative;
            overflow: hidden;
        }

        .footer-inner {
            max-width: 1200px;
            margin: 0 auto;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1.5fr;
            gap: 50px;
            margin-bottom: 50px;
        }

        .footer-brand-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
            text-decoration: none;
        }

        .footer-logo-box {
            width: 44px;
            height: 44px;
            background: var(--gold);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            color: white;
        }

        .footer-brand-name {
            font-family: 'Playfair Display', serif;
            font-size: 1.1rem;
            font-weight: 700;
            color: white;
        }

        .footer-brand-sub {
            font-size: 0.7rem;
            color: rgba(255, 255, 255, 0.4);
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .footer-desc {
            color: rgba(255, 255, 255, 0.45);
            font-size: 0.87rem;
            line-height: 1.7;
            margin-bottom: 24px;
        }

        .footer-socials {
            display: flex;
            gap: 12px;
        }

        .social-btn {
            width: 38px;
            height: 38px;
            background: rgba(255, 255, 255, 0.07);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: rgba(255, 255, 255, 0.6);
            text-decoration: none;
            font-size: 0.9rem;
            transition: all 0.3s;
        }

        .social-btn:hover {
            background: var(--gold);
            color: white;
            transform: translateY(-3px);
        }

        .footer-col-title {
            color: white;
            font-weight: 600;
            font-size: 0.9rem;
            margin-bottom: 20px;
        }

        .footer-links {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .footer-links a {
            color: rgba(255, 255, 255, 0.45);
            font-size: 0.85rem;
            text-decoration: none;
            transition: color 0.2s;
        }

        .footer-links a:hover {
            color: var(--gold-light);
        }

        .footer-contact-list {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .footer-contact-list li {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            color: rgba(255, 255, 255, 0.45);
            font-size: 0.83rem;
            line-height: 1.5;
        }

        .footer-contact-list li i {
            color: var(--gold);
            margin-top: 2px;
            flex-shrink: 0;
        }

        .footer-divider {
            border: none;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            margin-bottom: 30px;
        }

        .footer-bottom {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 16px;
        }

        .footer-bottom p {
            color: rgba(255, 255, 255, 0.3);
            font-size: 0.8rem;
        }

        .footer-bottom span {
            color: rgba(255, 255, 255, 0.3);
            font-size: 0.8rem;
        }

        .footer-bottom span i {
            color: #e34545;
        }

        /* ── FLOATING WA ── */
        .floating-wa {
            position: fixed;
            bottom: 28px;
            right: 28px;
            width: 58px;
            height: 58px;
            background: #25D366;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.6rem;
            text-decoration: none;
            box-shadow: 0 8px 24px rgba(37, 211, 102, 0.5);
            z-index: 999;
            animation: floatCard 2s ease-in-out infinite;
            transition: transform 0.2s;
        }

        .floating-wa:hover {
            transform: scale(1.1);
        }

        /* ── BACK TO TOP ── */
        #backTop {
            position: fixed;
            bottom: 28px;
            right: 96px;
            width: 46px;
            height: 46px;
            background: var(--green);
            border-radius: 50%;
            display: none;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1rem;
            text-decoration: none;
            box-shadow: 0 8px 20px rgba(6, 78, 59, 0.4);
            z-index: 999;
            transition: all 0.3s;
        }

        #backTop:hover {
            background: var(--green-mid);
            transform: translateY(-4px);
        }

        #backTop.show {
            display: flex;
        }

        /* ── ANIMATIONS ── */
        @keyframes fadeSlideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeScale {
            from {
                opacity: 0;
                transform: scale(0.95);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        @keyframes floatCard {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-8px);
            }
        }

        @keyframes spin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        @keyframes ticker {
            from {
                transform: translateX(0);
            }

            to {
                transform: translateX(-50%);
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes countUp {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        /* ── REVEAL ON SCROLL ── */
        .reveal {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 0.7s ease, transform 0.7s ease;
        }

        .reveal.visible {
            opacity: 1;
            transform: translateY(0);
        }

        .reveal-left {
            opacity: 0;
            transform: translateX(-30px);
            transition: opacity 0.7s ease, transform 0.7s ease;
        }

        .reveal-left.visible {
            opacity: 1;
            transform: translateX(0);
        }

        .reveal-right {
            opacity: 0;
            transform: translateX(30px);
            transition: opacity 0.7s ease, transform 0.7s ease;
        }

        .reveal-right.visible {
            opacity: 1;
            transform: translateX(0);
        }

        .delay-1 {
            transition-delay: 0.1s;
        }

        .delay-2 {
            transition-delay: 0.2s;
        }

        .delay-3 {
            transition-delay: 0.3s;
        }

        .delay-4 {
            transition-delay: 0.4s;
        }

        .delay-5 {
            transition-delay: 0.5s;
        }

        /* ── ISLAMIC ORNAMENT SVG ── */
        .geo-pattern {
            position: absolute;
            opacity: 0.04;
            pointer-events: none;
        }

        /* ── DIVIDER ── */
        .ornament-divider {
            text-align: center;
            color: var(--gold);
            font-size: 1.4rem;
            margin: 0 auto;
            opacity: 0.6;
            letter-spacing: 12px;
            display: block;
        }

        /* ── MOBILE ── */
        @media (max-width: 768px) {
            .nav-links {
                display: none;
            }

            .hamburger {
                display: flex;
            }

            .nav-links.open {
                display: flex;
                flex-direction: column;
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                background: var(--green);
                padding: 16px;
                gap: 4px;
            }

            .hero-content {
                grid-template-columns: 1fr;
                padding-top: 100px;
            }

            .hero-visual {
                display: none;
            }

            .hero-stats {
                gap: 20px;
            }

            .about-grid,
            .why-grid,
            .daftar-grid,
            .kontak-grid {
                grid-template-columns: 1fr;
            }

            .about-img-accent {
                display: none;
            }

            .stats-grid {
                grid-template-columns: repeat(3, 1fr);
            }

            .stat-item:nth-child(4),
            .stat-item:nth-child(5) {
                grid-column: auto;
            }

            .programs-grid {
                grid-template-columns: 1fr;
            }

            .gallery-grid {
                grid-template-columns: 1fr 1fr;
                grid-template-rows: auto;
            }

            .gallery-item:first-child {
                grid-row: auto;
                grid-column: 1 / -1;
            }

            .fasilitas-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .testi-grid {
                grid-template-columns: 1fr;
            }

            .footer-grid {
                grid-template-columns: 1fr;
                gap: 32px;
            }

            .section {
                padding: 70px 20px;
            }

            .footer-bottom {
                flex-direction: column;
                text-align: center;
            }
        }

        /* ── LIGHTBOX GALLERY ── */
        .lightbox-overlay {
            position: fixed;
            inset: 0;
            z-index: 9999;
            background: rgba(3, 31, 24, 0.82);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.35s ease;
        }

        .lightbox-overlay.open {
            opacity: 1;
            pointer-events: all;
        }

        .lightbox-box {
            position: relative;
            width: 90vw;
            max-width: 960px;
            background: rgba(6, 78, 59, 0.25);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 28px;
            padding: 20px;
            box-shadow: 0 40px 100px rgba(0, 0, 0, 0.5);
            transform: scale(0.94);
            transition: transform 0.35s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .lightbox-overlay.open .lightbox-box {
            transform: scale(1);
        }

        /* Tombol tutup */
        .lightbox-close {
            position: absolute;
            top: -16px;
            right: -16px;
            width: 44px;
            height: 44px;
            background: var(--gold);
            border: none;
            border-radius: 50%;
            color: white;
            font-size: 1.1rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10;
            transition: all 0.2s;
            box-shadow: 0 4px 16px rgba(184, 151, 74, 0.5);
        }

        .lightbox-close:hover {
            background: var(--gold-light);
            transform: rotate(90deg) scale(1.1);
        }

        /* Slider wrapper */
        .lightbox-slider-wrap {
            overflow: hidden;
            border-radius: 18px;
            position: relative;
        }

        .lightbox-track {
            display: flex;
            transition: transform 0.45s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }

        .lightbox-slide {
            min-width: 100%;
            position: relative;
        }

        .lightbox-slide img {
            width: 100%;
            height: 60vh;
            object-fit: cover;
            border-radius: 18px;
            display: block;
        }

        .lightbox-caption {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(transparent, rgba(3, 31, 24, 0.85));
            color: white;
            padding: 32px 24px 20px;
            border-radius: 0 0 18px 18px;
            font-size: 0.9rem;
            font-weight: 500;
        }

        /* Tombol prev/next */
        .lightbox-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 46px;
            height: 46px;
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            color: white;
            font-size: 1rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
            z-index: 5;
            backdrop-filter: blur(6px);
        }

        .lightbox-btn:hover {
            background: var(--gold);
            border-color: var(--gold);
            transform: translateY(-50%) scale(1.1);
        }

        .lightbox-prev {
            left: 12px;
        }

        .lightbox-next {
            right: 12px;
        }

        /* Dots */
        .lightbox-dots {
            display: flex;
            justify-content: center;
            gap: 8px;
            margin-top: 16px;
        }

        .lightbox-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            cursor: pointer;
            transition: all 0.3s;
            border: none;
            padding: 0;
        }

        .lightbox-dot.active {
            background: var(--gold);
            width: 24px;
            border-radius: 4px;
        }

        /* Counter */
        .lightbox-counter {
            position: absolute;
            top: 14px;
            left: 14px;
            background: rgba(0, 0, 0, 0.45);
            color: white;
            font-size: 0.78rem;
            font-weight: 600;
            padding: 4px 12px;
            border-radius: 50px;
            backdrop-filter: blur(6px);
            z-index: 5;
        }

        /* Thumbnail strip */
        .lightbox-thumbs {
            display: flex;
            gap: 8px;
            margin-top: 12px;
            overflow-x: auto;
            padding-bottom: 4px;
            scrollbar-width: thin;
            scrollbar-color: var(--gold) transparent;
        }

        .lightbox-thumb {
            min-width: 72px;
            height: 52px;
            border-radius: 10px;
            object-fit: cover;
            cursor: pointer;
            opacity: 0.5;
            transition: all 0.25s;
            border: 2px solid transparent;
            flex-shrink: 0;
        }

        .lightbox-thumb.active,
        .lightbox-thumb:hover {
            opacity: 1;
            border-color: var(--gold);
        }
    </style>
</head>

<body>

    <!-- NAVBAR -->
    <nav class="navbar" id="navbar">
        <div class="nav-inner">
            <a class="nav-logo" href="#">
                <div class="nav-logo-emblem">☽</div>
                <div class="nav-logo-text">
                    <span>Miftahul Ulum</span>
                    <span>Jember Est. 1980</span>
                </div>
            </a>
            <ul class="nav-links" id="navLinks">
                <li><a href="#about">Profil</a></li>
                <li><a href="#program">Program</a></li>
                <li><a href="#why">Keunggulan</a></li>
                <li><a href="#gallery">Galeri</a></li>
                <li><a href="#jadwal">Kegiatan</a></li>
                <li><a href="#testimoni">Testimoni</a></li>
                <li><a href="#pendaftaran" class="nav-cta">Daftar Sekarang</a></li>
            </ul>
            <button class="hamburger" id="hamburger" aria-label="menu">
                <span></span><span></span><span></span>
            </button>
        </div>
    </nav>

    <!-- HERO -->
    <section id="hero">
        <div class="hero-bg"></div>
        <!-- Geometric Islamic Pattern -->
        <svg class="hero-geometric" viewBox="0 0 600 600" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <defs>
                <pattern id="islamicPat" x="0" y="0" width="80" height="80" patternUnits="userSpaceOnUse">
                    <path d="M40 0 L80 40 L40 80 L0 40 Z" fill="none" stroke="white" stroke-width="0.8" />
                    <circle cx="40" cy="40" r="15" fill="none" stroke="white" stroke-width="0.6" />
                    <path d="M40 25 L55 40 L40 55 L25 40 Z" fill="none" stroke="white" stroke-width="0.6" />
                    <circle cx="0" cy="0" r="8" fill="none" stroke="white" stroke-width="0.5" />
                    <circle cx="80" cy="0" r="8" fill="none" stroke="white" stroke-width="0.5" />
                    <circle cx="0" cy="80" r="8" fill="none" stroke="white" stroke-width="0.5" />
                    <circle cx="80" cy="80" r="8" fill="none" stroke="white" stroke-width="0.5" />
                </pattern>
            </defs>
            <rect width="600" height="600" fill="url(#islamicPat)" />
        </svg>

        <div class="hero-content">
            <div class="hero-left">
                <div class="hero-badge">
                    <span>☾</span>
                    <span>Berdiri sejak 1980 · Jember, Jawa Timur</span>
                </div>
                <div class="hero-arabic">مِفْتَاحُ الْعُلُوم</div>
                <h1 class="hero-title">
                    Pondok Pesantren
                    <em>Miftahul Ulum</em>
                </h1>
                <p class="hero-subtitle">Membentuk generasi Qur'ani yang berilmu, berakhlak mulia, dan bermanfaat bagi umat dan bangsa dalam naungan nilai-nilai Islam Ahlussunnah Wal Jama'ah.</p>
                <div class="hero-actions">
                    <a href="#pendaftaran" class="btn-gold">
                        <i class="fas fa-paper-plane"></i> Daftar Sekarang
                    </a>
                    <a href="#program" class="btn-outline-white">
                        <i class="fas fa-play-circle"></i> Lihat Program
                    </a>
                </div>
                <div class="hero-stats">
                    <div class="hero-stat">
                        <div class="hero-stat-num">44<span style="font-size:1.2rem">+</span></div>
                        <div class="hero-stat-label">Tahun Berdiri</div>
                    </div>
                    <div class="hero-stat">
                        <div class="hero-stat-num">500<span style="font-size:1.2rem">+</span></div>
                        <div class="hero-stat-label">Santri Aktif</div>
                    </div>
                    <div class="hero-stat">
                        <div class="hero-stat-num">1000<span style="font-size:1.2rem">+</span></div>
                        <div class="hero-stat-label">Alumni</div>
                    </div>
                </div>
            </div>
            <div class="hero-visual">
                <div class="hero-img-frame">
                    <div class="hero-img-ring"></div>
                    <img src="https://images.unsplash.com/photo-1577896851231-70ef18881754?w=600&auto=format&fit=crop" alt="Pondok Pesantren" class="hero-img-main">
                    <div class="hero-img-card hero-card-1">
                        <div class="card-num">50+</div>
                        <div class="card-lbl">Hafidz Qur'an</div>
                    </div>
                    <div class="hero-img-card hero-card-2">
                        <div class="card-num">30+</div>
                        <div class="card-lbl">Tenaga Pengajar</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- TICKER -->
    <div class="ticker-wrap">
        <div class="ticker-inner" id="ticker">
            <span class="ticker-item"><i class="fas fa-star"></i> Pendaftaran Santri Baru 2025/2026 Telah Dibuka!</span>
            <span class="ticker-item"><i class="fas fa-star"></i> Program Tahfidz Qur'an 30 Juz Tersedia</span>
            <span class="ticker-item"><i class="fas fa-star"></i> Beasiswa Penuh untuk Hafidz Berprestasi</span>
            <span class="ticker-item"><i class="fas fa-star"></i> Test Masuk: 15 Juli 2025 — Daftarkan Sekarang!</span>
            <span class="ticker-item"><i class="fas fa-star"></i> Terakreditasi A oleh Kemenag RI</span>
            <span class="ticker-item"><i class="fas fa-star"></i> Pendaftaran Santri Baru 2025/2026 Telah Dibuka!</span>
            <span class="ticker-item"><i class="fas fa-star"></i> Program Tahfidz Qur'an 30 Juz Tersedia</span>
            <span class="ticker-item"><i class="fas fa-star"></i> Beasiswa Penuh untuk Hafidz Berprestasi</span>
            <span class="ticker-item"><i class="fas fa-star"></i> Test Masuk: 15 Juli 2025 — Daftarkan Sekarang!</span>
            <span class="ticker-item"><i class="fas fa-star"></i> Terakreditasi A oleh Kemenag RI</span>
        </div>
    </div>

    <!-- ABOUT -->
    <section id="about" class="section">
        <div class="section-inner">
            <div class="about-grid">
                <div class="about-img-wrap reveal-left">
                    <div class="about-ornament"></div>
                    <img src="https://images.unsplash.com/photo-1585036156171-384164a8c675?w=700&auto=format&fit=crop" alt="Profil Pesantren" class="about-img-main">
                    <img src="https://images.unsplash.com/photo-1566669437688-88f436a61e4f?w=400&auto=format&fit=crop" alt="Masjid" class="about-img-accent">
                    <div class="about-badge-float">
                        <div class="num">A</div>
                        <div class="lbl">Akreditasi</div>
                    </div>
                </div>
                <div class="about-text reveal-right">
                    <div class="section-tag">Tentang Kami</div>
                    <h2 class="section-title-big">Sejarah &amp; <span>Visi Misi</span> Pondok</h2>
                    <div class="about-lead">بِسْمِ اللّٰهِ الرَّحْمٰنِ الرَّحِيْمِ</div>
                    <p class="about-body">Pondok Pesantren Miftahul Ulum Jember berdiri sejak tahun 1980, didirikan oleh KH. Ahmad Shodiq dengan visi mencetak generasi muslim yang berilmu, berakhlak mulia, dan mampu menjawab tantangan zaman dengan landasan Al-Qur'an dan As-Sunnah.</p>
                    <p class="about-body">Berlokasi di atas lahan seluas 5 hektar, kami menyediakan lingkungan belajar terpadu yang memadukan pendidikan agama klasikal dengan pendidikan formal, didukung fasilitas modern dan tenaga pengajar berpengalaman.</p>
                    <div class="about-pillars">
                        <div class="pillar-item">
                            <div class="pillar-icon"><i class="fas fa-book-quran"></i></div>
                            <div class="pillar-text">
                                <h6>Tafaqquh Fiddin</h6>
                                <p>Pendalaman ilmu agama secara menyeluruh</p>
                            </div>
                        </div>
                        <div class="pillar-item">
                            <div class="pillar-icon"><i class="fas fa-star-and-crescent"></i></div>
                            <div class="pillar-text">
                                <h6>Akhlakul Karimah</h6>
                                <p>Pembentukan karakter mulia Islami</p>
                            </div>
                        </div>
                        <div class="pillar-item">
                            <div class="pillar-icon"><i class="fas fa-graduation-cap"></i></div>
                            <div class="pillar-text">
                                <h6>Pendidikan Formal</h6>
                                <p>Integrasi MTs & MA terakreditasi A</p>
                            </div>
                        </div>
                        <div class="pillar-item">
                            <div class="pillar-icon"><i class="fas fa-hands-holding-heart"></i></div>
                            <div class="pillar-text">
                                <h6>Pengabdian Umat</h6>
                                <p>Siap bermanfaat bagi masyarakat luas</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- STATS BAR -->
    <div class="stats-bar">
        <div class="stats-grid">
            <div class="stat-item reveal">
                <span class="stat-icon"><i class="fas fa-users"></i></span>
                <span class="stat-num" data-target="500">0</span><span class="stat-unit">+</span>
                <span class="stat-lbl">Santri Aktif</span>
            </div>
            <div class="stat-item reveal delay-1">
                <span class="stat-icon"><i class="fas fa-user-graduate"></i></span>
                <span class="stat-num" data-target="1000">0</span><span class="stat-unit">+</span>
                <span class="stat-lbl">Alumni</span>
            </div>
            <div class="stat-item reveal delay-2">
                <span class="stat-icon"><i class="fas fa-book-quran"></i></span>
                <span class="stat-num" data-target="50">0</span><span class="stat-unit">+</span>
                <span class="stat-lbl">Hafidz Qur'an</span>
            </div>
            <div class="stat-item reveal delay-3">
                <span class="stat-icon"><i class="fas fa-chalkboard-teacher"></i></span>
                <span class="stat-num" data-target="30">0</span><span class="stat-unit">+</span>
                <span class="stat-lbl">Pengajar</span>
            </div>
            <div class="stat-item reveal delay-4">
                <span class="stat-icon"><i class="fas fa-award"></i></span>
                <span class="stat-num" data-target="44">0</span><span class="stat-unit">+</span>
                <span class="stat-lbl">Tahun Berdiri</span>
            </div>
        </div>
    </div>

    <!-- PROGRAM -->
    <section id="program" class="section">
        <div class="section-inner">
            <div style="text-align:center; margin-bottom:0">
                <div class="section-tag" style="justify-content:center">Program Pendidikan</div>
                <h2 class="section-title-big" style="text-align:center">Program <span>Unggulan</span> Kami</h2>
                <p class="section-subtitle" style="margin:0 auto; text-align:center">Kami menyediakan program pendidikan komprehensif yang memadukan ilmu agama dan umum untuk membentuk generasi unggul</p>
            </div>
            <div class="programs-grid">

                <div class="program-card reveal delay-1">
                    <div class="program-card-top"></div>
                    <div class="program-card-body">
                        <div class="program-number">01</div>
                        <div class="program-icon-wrap"><i class="fas fa-book-quran"></i></div>
                        <div class="program-title">Tahfidz Al-Qur'an</div>
                        <div class="program-arabic">تَحْفِيظُ الْقُرْآن</div>
                        <p class="program-desc">Program hafalan Al-Qur'an dengan metode mutqin dan musyafahah langsung kepada ustadz hafidz bersanad. Tersedia berbagai target hafalan.</p>
                        <ul class="program-features">
                            <li>Target hafalan 1, 5, 10, 20, dan 30 Juz</li>
                            <li>Dibimbing Hafidz bersanad & berpengalaman</li>
                            <li>Muraja'ah harian, mingguan, dan bulanan</li>
                            <li>Kelas Tajwid dan Ilmu Gharib</li>
                            <li>Sertifikasi hafalan resmi dari Kemenag</li>
                        </ul>
                        <a href="#pendaftaran" class="program-link">Daftar Program <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>

                <div class="program-card reveal delay-2">
                    <div class="program-card-top" style="background:linear-gradient(90deg, var(--gold), #e8c97a)"></div>
                    <div class="program-card-body">
                        <div class="program-number">02</div>
                        <div class="program-icon-wrap" style="background:#fffbeb"><i class="fas fa-mosque" style="color:var(--gold)"></i></div>
                        <div class="program-title">Madrasah Diniyah</div>
                        <div class="program-arabic">الْمَدْرَسَةُ الدِّيْنِيَّة</div>
                        <p class="program-desc">Pendidikan ilmu agama Islam secara mendalam dengan kurikulum kitab kuning dan metode pembelajaran salaf yang terstruktur.</p>
                        <ul class="program-features">
                            <li>Fiqh, Aqidah, Akhlak, dan Tasawuf</li>
                            <li>Bahasa Arab intensif (Nahwu & Sharaf)</li>
                            <li>Hadits, Ulumul Qur'an, dan Tafsir</li>
                            <li>Kitab kuning & metode sorogan/bandongan</li>
                            <li>3 tingkatan: Ula, Wustho, Ulya</li>
                        </ul>
                        <a href="#pendaftaran" class="program-link" style="color:var(--gold)">Daftar Program <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>

                <div class="program-card reveal delay-3">
                    <div class="program-card-top" style="background:linear-gradient(90deg, #0369a1, #0ea5e9)"></div>
                    <div class="program-card-body">
                        <div class="program-number">03</div>
                        <div class="program-icon-wrap" style="background:#eff6ff"><i class="fas fa-school" style="color:#0369a1"></i></div>
                        <div class="program-title">Pendidikan Formal</div>
                        <div class="program-arabic">التَّعْلِيمُ الرَّسْمِي</div>
                        <p class="program-desc">Integrasi pendidikan formal MTs dan MA dalam satu naungan dengan akreditasi A dari Kemenag RI dan Kemendikbud.</p>
                        <ul class="program-features">
                            <li>MTs & MA Terakreditasi A</li>
                            <li>Kurikulum Merdeka Belajar</li>
                            <li>Lab IPA, Komputer, dan Bahasa</li>
                            <li>Extrakurikuler beragam & kompetitif</li>
                            <li>Lulusan siap PTN/PTS terkemuka</li>
                        </ul>
                        <a href="#pendaftaran" class="program-link" style="color:#0369a1">Daftar Program <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>

                <div class="program-card reveal delay-1">
                    <div class="program-card-top" style="background:linear-gradient(90deg, #7c3aed, #a78bfa)"></div>
                    <div class="program-card-body">
                        <div class="program-number">04</div>
                        <div class="program-icon-wrap" style="background:#f5f3ff"><i class="fas fa-home" style="color:#7c3aed"></i></div>
                        <div class="program-title">Keasramaan</div>
                        <div class="program-arabic">الْإِقَامَةُ فِي السَّكَن</div>
                        <p class="program-desc">Pembinaan santri 24 jam melalui program keasramaan yang terstruktur untuk membentuk kemandirian dan kedisiplinan.</p>
                        <ul class="program-features">
                            <li>Asrama putra & putri terpisah</li>
                            <li>Kamar nyaman dengan kapasitas ideal</li>
                            <li>Pembimbing asrama berpengalaman</li>
                            <li>Program kemandirian & life skills</li>
                            <li>Kegiatan harian terstruktur & terpadu</li>
                        </ul>
                        <a href="#pendaftaran" class="program-link" style="color:#7c3aed">Daftar Program <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>

                <div class="program-card reveal delay-2">
                    <div class="program-card-top" style="background:linear-gradient(90deg, #dc2626, #f87171)"></div>
                    <div class="program-card-body">
                        <div class="program-number">05</div>
                        <div class="program-icon-wrap" style="background:#fef2f2"><i class="fas fa-language" style="color:#dc2626"></i></div>
                        <div class="program-title">Bahasa Asing Intensif</div>
                        <div class="program-arabic">اللُّغَاتُ الأَجْنَبِيَّة</div>
                        <p class="program-desc">Pelatihan bahasa Arab dan Inggris secara intensif dengan metode komunikatif yang menjadikan santri fasih berbicara.</p>
                        <ul class="program-features">
                            <li>Kelas Bahasa Arab intensif harian</li>
                            <li>English Speaking Programme</li>
                            <li>Muhadoroh mingguan 3 bahasa</li>
                            <li>Arabic & English Camp tahunan</li>
                            <li>Sertifikasi Bahasa Internasional</li>
                        </ul>
                        <a href="#pendaftaran" class="program-link" style="color:#dc2626">Daftar Program <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>

                <div class="program-card reveal delay-3">
                    <div class="program-card-top" style="background:linear-gradient(90deg, #0891b2, #67e8f9)"></div>
                    <div class="program-card-body">
                        <div class="program-number">06</div>
                        <div class="program-icon-wrap" style="background:#ecfeff"><i class="fas fa-handshake-angle" style="color:#0891b2"></i></div>
                        <div class="program-title">Pengembangan Diri</div>
                        <div class="program-arabic">تَنْمِيَةُ الذَّات</div>
                        <p class="program-desc">Program pengembangan soft skill, kepemimpinan, dan kewirausahaan Islami untuk mempersiapkan santri menghadapi dunia nyata.</p>
                        <ul class="program-features">
                            <li>Pelatihan kepemimpinan & organisasi</li>
                            <li>Kewirausahaan syariah & manajemen</li>
                            <li>Seni baca Al-Qur'an & Nasyid</li>
                            <li>Olahraga: futsal, beladiri, renang</li>
                            <li>Program magang & khidmah masyarakat</li>
                        </ul>
                        <a href="#pendaftaran" class="program-link" style="color:#0891b2">Daftar Program <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- WHY US -->
    <section id="why" class="section">
        <svg class="geo-pattern" style="top:0;right:0;width:400px;opacity:0.05" viewBox="0 0 400 400" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <pattern id="starPat" x="0" y="0" width="50" height="50" patternUnits="userSpaceOnUse">
                    <polygon points="25,2 31,18 48,18 35,29 40,46 25,36 10,46 15,29 2,18 19,18" fill="none" stroke="white" stroke-width="0.6" />
                </pattern>
            </defs>
            <rect width="400" height="400" fill="url(#starPat)" />
        </svg>
        <div class="section-inner">
            <div class="why-grid">
                <div class="why-text reveal-left">
                    <div class="section-tag">Keunggulan Kami</div>
                    <h2 class="section-title-big">Mengapa Memilih <span>Miftahul Ulum?</span></h2>
                    <p class="section-subtitle">Kami hadir dengan pendekatan pendidikan holistik yang mengintegrasikan tradisi salaf dengan modernitas</p>
                    <div class="why-list">
                        <div class="why-item">
                            <div class="why-item-icon"><i class="fas fa-certificate"></i></div>
                            <div class="why-item-text">
                                <h5>Terakreditasi A Kemenag RI</h5>
                                <p>Kualitas pendidikan terstandar nasional dengan pengakuan resmi pemerintah</p>
                            </div>
                        </div>
                        <div class="why-item">
                            <div class="why-item-icon"><i class="fas fa-shield-halved"></i></div>
                            <div class="why-item-text">
                                <h5>Lingkungan Aman & Terkontrol</h5>
                                <p>Sistem keamanan 24 jam, CCTV, dan pembina yang berdedikasi mendampingi santri</p>
                            </div>
                        </div>
                        <div class="why-item">
                            <div class="why-item-icon"><i class="fas fa-utensils"></i></div>
                            <div class="why-item-text">
                                <h5>Gizi Seimbang & Halalan Thayyiban</h5>
                                <p>Dapur sendiri dengan menu bergizi, halal, dan sesuai standar kesehatan anak</p>
                            </div>
                        </div>
                        <div class="why-item">
                            <div class="why-item-icon"><i class="fas fa-wifi"></i></div>
                            <div class="why-item-text">
                                <h5>Teknologi & Literasi Digital Islami</h5>
                                <p>Akses teknologi terarah, lab komputer modern, dan filter konten Islami</p>
                            </div>
                        </div>
                        <div class="why-item">
                            <div class="why-item-icon"><i class="fas fa-hand-holding-dollar"></i></div>
                            <div class="why-item-text">
                                <h5>Beasiswa & Bantuan Biaya</h5>
                                <p>Tersedia beasiswa penuh untuk hafidz berprestasi dan santri kurang mampu</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="why-visual reveal-right">
                    <div class="why-card">
                        <div class="why-card-header">
                            <div class="why-card-title">Tingkat Kelulusan</div>
                            <div class="why-card-badge">2024</div>
                        </div>
                        <div class="why-progress">
                            <div class="why-progress-label"><span>Lulus dengan Predikat Baik</span><span>94%</span></div>
                            <div class="progress-bar-wrap">
                                <div class="progress-bar-fill" data-width="94"></div>
                            </div>
                        </div>
                        <div class="why-progress">
                            <div class="why-progress-label"><span>Melanjutkan ke PTN/PTS</span><span>78%</span></div>
                            <div class="progress-bar-wrap">
                                <div class="progress-bar-fill" data-width="78"></div>
                            </div>
                        </div>
                        <div class="why-progress">
                            <div class="why-progress-label"><span>Hafal Minimal 5 Juz</span><span>86%</span></div>
                            <div class="progress-bar-wrap">
                                <div class="progress-bar-fill" data-width="86"></div>
                            </div>
                        </div>
                        <div class="why-progress">
                            <div class="why-progress-label"><span>Prestasi Olimpiade/Kompetisi</span><span>65%</span></div>
                            <div class="progress-bar-wrap">
                                <div class="progress-bar-fill" data-width="65"></div>
                            </div>
                        </div>
                    </div>
                    <div class="why-card">
                        <div class="why-card-header">
                            <div class="why-card-title">Jadwal Harian Santri</div>
                            <div class="why-card-badge">Ringkasan</div>
                        </div>
                        <div class="schedule-grid">
                            <div class="schedule-item"><span class="schedule-time">03:30</span><span class="schedule-activity">Qiyamul Lail & Tahajud</span></div>
                            <div class="schedule-item"><span class="schedule-time">05:00</span><span class="schedule-activity">Subuh & Tahfidz Pagi</span></div>
                            <div class="schedule-item"><span class="schedule-time">07:00</span><span class="schedule-activity">Sekolah Formal</span></div>
                            <div class="schedule-item"><span class="schedule-time">13:00</span><span class="schedule-activity">Sholat Dzuhur & Makan</span></div>
                            <div class="schedule-item"><span class="schedule-time">14:00</span><span class="schedule-activity">Madrasah Diniyah</span></div>
                            <div class="schedule-item"><span class="schedule-time">18:00</span><span class="schedule-activity">Maghrib & Tahfidz Sore</span></div>
                            <div class="schedule-item"><span class="schedule-time">19:30</span><span class="schedule-activity">Isya & Belajar Mandiri</span></div>
                            <div class="schedule-item"><span class="schedule-time">21:30</span><span class="schedule-activity">Istirahat Malam</span></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- GALLERY -->
    <section id="gallery" class="section">
        <div class="section-inner">
            <div style="text-align:center">
                <div class="section-tag" style="justify-content:center">Dokumentasi</div>
                <h2 class="section-title-big" style="text-align:center">Galeri <span>Kegiatan</span></h2>
                <p class="section-subtitle" style="margin:0 auto; text-align:center; margin-bottom:0">Momen berharga kehidupan santri di Miftahul Ulum Jember</p>
            </div>
            <div class="gallery-grid reveal">
                <div class="gallery-item">
                    <img src="https://images.unsplash.com/photo-1585032226651-759b368d7246?w=700&auto=format&fit=crop" alt="Kegiatan Belajar" class="gallery-img">
                    <div class="gallery-overlay"><span class="gallery-overlay-text">Kegiatan Belajar Bersama</span></div>
                </div>
                <div class="gallery-item">
                    <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=400&auto=format&fit=crop" alt="Hafalan Quran" class="gallery-img">
                    <div class="gallery-overlay"><span class="gallery-overlay-text">Setoran Hafalan Qur'an</span></div>
                </div>
                <div class="gallery-item">
                    <img src="https://images.unsplash.com/photo-1519817650390-64a93db51149?w=400&auto=format&fit=crop" alt="Olahraga" class="gallery-img">
                    <div class="gallery-overlay"><span class="gallery-overlay-text">Kegiatan Olahraga</span></div>
                </div>
                <div class="gallery-item">
                    <img src="https://images.unsplash.com/photo-1580582932707-520aed937b7b?w=400&auto=format&fit=crop" alt="Masjid" class="gallery-img">
                    <div class="gallery-overlay"><span class="gallery-overlay-text">Shalat Berjamaah</span></div>
                </div>
                <div class="gallery-item">
                    <img src="https://images.unsplash.com/photo-1529156069898-49953e39b3ac?w=400&auto=format&fit=crop" alt="Wisuda" class="gallery-img">
                    <div class="gallery-overlay"><span class="gallery-overlay-text">Wisuda Santri</span></div>
                </div>
                <button onclick="openLightbox()" class="gallery-more gallery-item" style="cursor:pointer; border:2px dashed var(--green-pale); background:var(--green-ultra);">
                    <i class="fas fa-images"></i>
                    <span>Lihat Semua Galeri</span>
                </button>
            </div>
        </div>
    </section>

    <!-- FASILITAS -->
    <section id="fasilitas" class="section">
        <div class="section-inner">
            <div style="text-align:center">
                <div class="section-tag" style="justify-content:center">Infrastruktur</div>
                <h2 class="section-title-big" style="text-align:center">Fasilitas <span>Lengkap</span> &amp; Modern</h2>
                <p class="section-subtitle" style="margin:0 auto; text-align:center">Dilengkapi sarana prasarana terbaik untuk menunjang proses belajar santri secara optimal</p>
            </div>
            <div class="fasilitas-grid">
                <div class="fasilitas-card reveal delay-1">
                    <div class="fasilitas-icon"><i class="fas fa-mosque"></i></div>
                    <div class="fasilitas-name">Masjid Utama</div>
                    <div class="fasilitas-desc">Masjid besar berkapasitas 800 jamaah dengan arsitektur Islami yang megah</div>
                </div>
                <div class="fasilitas-card reveal delay-2">
                    <div class="fasilitas-icon"><i class="fas fa-bed"></i></div>
                    <div class="fasilitas-name">Asrama Nyaman</div>
                    <div class="fasilitas-desc">Kamar ber-AC dengan kasur, lemari, dan kipas angin. Putra & putri terpisah</div>
                </div>
                <div class="fasilitas-card reveal delay-3">
                    <div class="fasilitas-icon"><i class="fas fa-book-open"></i></div>
                    <div class="fasilitas-name">Perpustakaan</div>
                    <div class="fasilitas-desc">Koleksi 5.000+ buku agama, umum, dan referensi akademik lengkap</div>
                </div>
                <div class="fasilitas-card reveal delay-4">
                    <div class="fasilitas-icon"><i class="fas fa-computer"></i></div>
                    <div class="fasilitas-name">Lab Komputer</div>
                    <div class="fasilitas-desc">50 unit komputer berspecifikasi tinggi dengan koneksi internet terfilter</div>
                </div>
                <div class="fasilitas-card reveal delay-1">
                    <div class="fasilitas-icon"><i class="fas fa-flask"></i></div>
                    <div class="fasilitas-name">Laboratorium IPA</div>
                    <div class="fasilitas-desc">Lab Fisika, Kimia, dan Biologi lengkap dengan alat praktikum modern</div>
                </div>
                <div class="fasilitas-card reveal delay-2">
                    <div class="fasilitas-icon"><i class="fas fa-futbol"></i></div>
                    <div class="fasilitas-name">Lapangan Olahraga</div>
                    <div class="fasilitas-desc">Lapangan futsal, basket, voli, dan area jogging di lingkungan asri</div>
                </div>
                <div class="fasilitas-card reveal delay-3">
                    <div class="fasilitas-icon"><i class="fas fa-hospital"></i></div>
                    <div class="fasilitas-name">Klinik Kesehatan</div>
                    <div class="fasilitas-desc">Poliklinik dengan dokter dan perawat piket 24 jam untuk santri</div>
                </div>
                <div class="fasilitas-card reveal delay-4">
                    <div class="fasilitas-icon"><i class="fas fa-utensils"></i></div>
                    <div class="fasilitas-name">Kantin & Dapur</div>
                    <div class="fasilitas-desc">Dapur sendiri menyajikan menu bergizi 3x sehari + snack sore halal</div>
                </div>
            </div>
        </div>
    </section>

    <!-- KEGIATAN HARIAN -->
    <section id="jadwal" class="section">
        <div class="section-inner">
            <!-- Header terpusat -->
            <div style="text-align:center; max-width:660px; margin:0 auto 10px" class="reveal">
                <div class="section-tag" style="justify-content:center">Rutinitas</div>
                <h2 class="section-title-big">Jadwal <span>Kegiatan Harian</span> Santri</h2>
                <p class="section-subtitle" style="margin:0 auto">
                    Setiap hari santri menjalani rutinitas terstruktur yang membangun kedisiplinan dan karakter sejati
                </p>
            </div>

            <!-- Timeline zigzag -->
            <div class="jadwal-timeline reveal">
                <div class="jadwal-item">
                    <div class="jadwal-time">03:30</div>
                    <div class="jadwal-dot"></div>
                    <div class="jadwal-content">
                        <div class="jadwal-activity">Qiyamul Lail &amp; Tahajud</div>
                        <div class="jadwal-note">Sholat sunnah malam berjamaah</div>
                    </div>
                </div>
                <div class="jadwal-item">
                    <div class="jadwal-time">04:30</div>
                    <div class="jadwal-dot"></div>
                    <div class="jadwal-content">
                        <div class="jadwal-activity">Subuh Berjamaah + Dzikir</div>
                        <div class="jadwal-note">Di Masjid utama pesantren</div>
                    </div>
                </div>
                <div class="jadwal-item">
                    <div class="jadwal-time">05:15</div>
                    <div class="jadwal-dot" style="background:var(--green)"></div>
                    <div class="jadwal-content">
                        <div class="jadwal-activity">Tahfidz Pagi</div>
                        <div class="jadwal-note">Hafalan &amp; muraja'ah Al-Qur'an</div>
                    </div>
                </div>
                <div class="jadwal-item">
                    <div class="jadwal-time">06:30</div>
                    <div class="jadwal-dot"></div>
                    <div class="jadwal-content">
                        <div class="jadwal-activity">Mandi, Sarapan &amp; Persiapan</div>
                        <div class="jadwal-note">Persiapan kegiatan harian</div>
                    </div>
                </div>
                <div class="jadwal-item">
                    <div class="jadwal-time">07:00</div>
                    <div class="jadwal-dot" style="background:var(--green)"></div>
                    <div class="jadwal-content">
                        <div class="jadwal-activity">Kegiatan Belajar Formal</div>
                        <div class="jadwal-note">KBM di MTs/MA pesantren</div>
                    </div>
                </div>
                <div class="jadwal-item">
                    <div class="jadwal-time">13:00</div>
                    <div class="jadwal-dot"></div>
                    <div class="jadwal-content">
                        <div class="jadwal-activity">Dzuhur, Makan Siang &amp; Istirahat</div>
                        <div class="jadwal-note">Istirahat siang 1 jam</div>
                    </div>
                </div>
                <div class="jadwal-item">
                    <div class="jadwal-time">14:00</div>
                    <div class="jadwal-dot" style="background:var(--green)"></div>
                    <div class="jadwal-content">
                        <div class="jadwal-activity">Madrasah Diniyah</div>
                        <div class="jadwal-note">Kitab kuning &amp; ilmu agama</div>
                    </div>
                </div>
                <div class="jadwal-item">
                    <div class="jadwal-time">16:30</div>
                    <div class="jadwal-dot"></div>
                    <div class="jadwal-content">
                        <div class="jadwal-activity">Ashar &amp; Kegiatan Ekstrakurikuler</div>
                        <div class="jadwal-note">Olahraga, seni, dan pengembangan diri</div>
                    </div>
                </div>
                <div class="jadwal-item">
                    <div class="jadwal-time">18:00</div>
                    <div class="jadwal-dot" style="background:var(--green)"></div>
                    <div class="jadwal-content">
                        <div class="jadwal-activity">Maghrib &amp; Tahfidz Sore</div>
                        <div class="jadwal-note">Hafalan sore &amp; muraja'ah</div>
                    </div>
                </div>
                <div class="jadwal-item">
                    <div class="jadwal-time">19:30</div>
                    <div class="jadwal-dot"></div>
                    <div class="jadwal-content">
                        <div class="jadwal-activity">Isya &amp; Belajar Malam</div>
                        <div class="jadwal-note">Belajar mandiri &amp; kelompok</div>
                    </div>
                </div>
                <div class="jadwal-item">
                    <div class="jadwal-time">21:30</div>
                    <div class="jadwal-dot" style="background:#6b7280"></div>
                    <div class="jadwal-content">
                        <div class="jadwal-activity">Istirahat Malam</div>
                        <div class="jadwal-note">Persiapan hari esok yang produktif</div>
                    </div>
                </div>
            </div>

            <!-- Kotak filosofi di bawah -->
            <div style="margin-top:40px; padding:22px 28px; background:var(--green-ultra); border-radius:16px; border-left:4px solid var(--green); max-width:700px; margin-left:auto; margin-right:auto" class="reveal">
                <p style="font-size:0.9rem; color:var(--green); line-height:1.7; margin:0">
                    <strong>💡 Filosofi Kami:</strong> Kedisiplinan bukan beban, melainkan pondasi kesuksesan. Dengan rutinitas yang terjaga, santri belajar manajemen waktu dan tanggung jawab sejak dini — bekal berharga sepanjang hayat.
                </p>
            </div>
        </div>
    </section>

    <!-- TESTIMONIALS -->
    <section id="testimoni" class="section" style="background:var(--off-white)">
        <div class="section-inner">
            <div style="text-align:center">
                <div class="section-tag" style="justify-content:center">Testimoni</div>
                <h2 class="section-title-big" style="text-align:center">Kata <span>Mereka</span> Tentang Kami</h2>
                <p class="section-subtitle" style="margin:0 auto; text-align:center">Pengalaman nyata dari wali santri dan alumni yang telah merasakan manfaat pendidikan di Miftahul Ulum</p>
            </div>
            <div class="testi-grid">
                <div class="testi-card reveal delay-1">
                    <span class="testi-quote">"</span>
                    <p class="testi-text">Anak saya berubah luar biasa setelah mondok di sini. Lebih disiplin, rajin ibadah, dan akhlaknya semakin baik. Dalam setahun sudah hafal 5 juz dengan tajwid yang benar. Sungguh investasi terbaik untuk masa depan anak.</p>
                    <div class="testi-author">
                        <div class="testi-avatar">A</div>
                        <div>
                            <div class="testi-stars">★★★★★</div>
                            <div class="testi-name">Ibu Aminah Rahayu</div>
                            <div class="testi-role">Wali Santri · Malang</div>
                        </div>
                    </div>
                </div>
                <div class="testi-card reveal delay-2">
                    <span class="testi-quote">"</span>
                    <p class="testi-text">Alhamdulillah, saya lulus Miftahul Ulum dengan hafalan 15 juz dan diterima di UIN Surabaya jurusan Hukum Islam. Ilmu yang saya peroleh di sini menjadi fondasi kuat dalam setiap langkah kehidupan saya.</p>
                    <div class="testi-author">
                        <div class="testi-avatar">M</div>
                        <div>
                            <div class="testi-stars">★★★★★</div>
                            <div class="testi-name">Muhammad Faris</div>
                            <div class="testi-role">Alumni 2022 · Mahasiswa UIN Surabaya</div>
                        </div>
                    </div>
                </div>
                <div class="testi-card reveal delay-3">
                    <span class="testi-quote">"</span>
                    <p class="testi-text">Program tahfidz di sini benar-benar luar biasa. Dalam 2 tahun, anak saya sudah hafal 10 juz dengan tajwid sempurna. Ustadz dan ustadzah sangat sabar, perhatian, dan profesional dalam membimbing santri.</p>
                    <div class="testi-author">
                        <div class="testi-avatar">B</div>
                        <div>
                            <div class="testi-stars">★★★★★</div>
                            <div class="testi-name">Bapak Budi Santoso</div>
                            <div class="testi-role">Wali Santri · Jember</div>
                        </div>
                    </div>
                </div>
                <div class="testi-card reveal delay-1">
                    <span class="testi-quote">"</span>
                    <p class="testi-text">Mondok di sini mengajarkan saya arti kemandirian yang sesungguhnya. Selain hafal Qur'an, saya juga bisa berbahasa Arab dan Inggris secara aktif. Sekarang saya melanjutkan studi ke Universitas Al-Azhar Kairo.</p>
                    <div class="testi-author">
                        <div class="testi-avatar">S</div>
                        <div>
                            <div class="testi-stars">★★★★★</div>
                            <div class="testi-name">Siti Fatimah</div>
                            <div class="testi-role">Alumni 2021 · Mahasiswi Al-Azhar Kairo</div>
                        </div>
                    </div>
                </div>
                <div class="testi-card reveal delay-2">
                    <span class="testi-quote">"</span>
                    <p class="testi-text">Fasilitasnya lengkap, lingkungannya kondusif, dan para pengajarnya sangat kompeten. Biaya yang terjangkau untuk kualitas pendidikan setara pesantren besar. Tidak ada tempat yang lebih baik untuk anak kami belajar.</p>
                    <div class="testi-author">
                        <div class="testi-avatar">R</div>
                        <div>
                            <div class="testi-stars">★★★★★</div>
                            <div class="testi-name">Keluarga H. Ridwan</div>
                            <div class="testi-role">Wali Santri · Surabaya</div>
                        </div>
                    </div>
                </div>
                <div class="testi-card reveal delay-3">
                    <span class="testi-quote">"</span>
                    <p class="testi-text">Pesantren ini bukan hanya mengajarkan ilmu agama, tapi membentuk karakter dan kepribadian yang kuat. Anak saya kini menjadi pribadi yang percaya diri, bertanggung jawab, dan memiliki visi hidup yang jelas.</p>
                    <div class="testi-author">
                        <div class="testi-avatar">H</div>
                        <div>
                            <div class="testi-stars">★★★★★</div>
                            <div class="testi-name">Ibu Hj. Maryam</div>
                            <div class="testi-role">Wali Santri · Lumajang</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- PENDAFTARAN -->
    <section id="pendaftaran" class="section">
        <div class="section-inner">
            <div class="daftar-grid">
                <div class="daftar-text reveal-left">
                    <div class="section-tag" style="color:var(--gold-light)"><span style="background:var(--gold-light);width:24px;height:2px;display:inline-block"></span> Penerimaan Santri Baru</div>
                    <h2 class="daftar-title">Daftarkan Putra/Putri Anda <span>Sekarang!</span></h2>
                    <p class="daftar-desc">Tahun Ajaran 2025/2026 telah dibuka. Berikan yang terbaik untuk masa depan buah hati Anda dengan pendidikan terpadu dan berkualitas di Pondok Pesantren Miftahul Ulum Jember.</p>
                    <div class="daftar-info-list">
                        <div class="daftar-info-row">
                            <div class="daftar-info-icon"><i class="fas fa-calendar-alt"></i></div>
                            <div class="daftar-info-text"><strong>Periode Pendaftaran:</strong> 1 Januari — 30 Juni 2025</div>
                        </div>
                        <div class="daftar-info-row">
                            <div class="daftar-info-icon"><i class="fas fa-user-check"></i></div>
                            <div class="daftar-info-text"><strong>Syarat Usia:</strong> 12 — 18 tahun (lulusan SD/MI/SMP)</div>
                        </div>
                        <div class="daftar-info-row">
                            <div class="daftar-info-icon"><i class="fas fa-money-bill-wave"></i></div>
                            <div class="daftar-info-text"><strong>Biaya Pendidikan:</strong> Rp 2.500.000 / semester</div>
                        </div>
                        <div class="daftar-info-row">
                            <div class="daftar-info-icon"><i class="fas fa-file-alt"></i></div>
                            <div class="daftar-info-text"><strong>Dokumen:</strong> FC Akte Kelahiran, KK, Rapor, & Foto</div>
                        </div>
                        <div class="daftar-info-row">
                            <div class="daftar-info-icon"><i class="fas fa-pen-to-square"></i></div>
                            <div class="daftar-info-text"><strong>Test Masuk:</strong> 15 Juli 2025 (Baca Tulis Qur'an & Wawancara)</div>
                        </div>
                        <div class="daftar-info-row">
                            <div class="daftar-info-icon"><i class="fas fa-award"></i></div>
                            <div class="daftar-info-text"><strong>Beasiswa:</strong> Tersedia bagi hafidz berprestasi & dhuafa</div>
                        </div>
                    </div>
                    <div class="daftar-btns">
                        <a href="https://wa.me/6281234567890" class="btn-wa" target="_blank">
                            <i class="fab fa-whatsapp"></i> Daftar via WhatsApp
                        </a>
                        <a href="#kontak" class="btn-outline-white" style="border-color:rgba(255,255,255,0.3)">
                            <i class="fas fa-info-circle"></i> Info Lebih Lanjut
                        </a>
                    </div>
                </div>
                <div class="reveal-right">
                    <div class="daftar-form-card">
                        <h3>Formulir Pendaftaran</h3>
                        <p>Isi formulir berikut dan kami akan menghubungi Anda dalam 1×24 jam</p>
                        <div class="form-group">
                            <label>Nama Lengkap Calon Santri</label>
                            <input type="text" class="form-input" placeholder="Masukkan nama lengkap">
                        </div>
                        <div class="form-group">
                            <label>Nama Orang Tua / Wali</label>
                            <input type="text" class="form-input" placeholder="Nama ayah/ibu/wali">
                        </div>
                        <div class="form-group">
                            <label>Nomor HP / WhatsApp</label>
                            <input type="tel" class="form-input" placeholder="08xxxxxxxxxx">
                        </div>
                        <div class="form-group">
                            <label>Asal Kota / Kabupaten</label>
                            <input type="text" class="form-input" placeholder="Contoh: Jember, Surabaya, dll">
                        </div>
                        <div class="form-group">
                            <label>Program yang Diminati</label>
                            <select class="form-input">
                                <option value="">Pilih program...</option>
                                <option>Tahfidz Qur'an</option>
                                <option>Madrasah Diniyah + Formal</option>
                                <option>Program Terpadu (Semua Program)</option>
                            </select>
                        </div>
                        <button class="btn-submit" onclick="handleSubmit(this)">
                            <i class="fas fa-paper-plane" style="margin-right:8px"></i> Kirim Pendaftaran
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- KONTAK -->
    <section id="kontak" class="section">
        <div class="section-inner">
            <div class="kontak-grid">
                <div class="reveal-left">
                    <div class="section-tag">Hubungi Kami</div>
                    <h2 class="section-title-big">Ada Pertanyaan? <span>Kami Siap Membantu</span></h2>
                    <p class="section-subtitle">Tim kami siap menjawab pertanyaan Anda seputar program, fasilitas, dan proses pendaftaran</p>
                    <div class="kontak-info-list">
                        <div class="kontak-info-item">
                            <div class="kontak-info-icon"><i class="fas fa-map-marker-alt"></i></div>
                            <div>
                                <div class="kontak-info-label">Alamat</div>
                                <div class="kontak-info-value">Jl. Pesantren No. 123, Kec. Sumbersari, Kab. Jember, Jawa Timur 68121</div>
                            </div>
                        </div>
                        <div class="kontak-info-item">
                            <div class="kontak-info-icon"><i class="fas fa-phone-alt"></i></div>
                            <div>
                                <div class="kontak-info-label">Telepon</div>
                                <div class="kontak-info-value">(0331) 1234567 | 081234567890 (WhatsApp)</div>
                            </div>
                        </div>
                        <div class="kontak-info-item">
                            <div class="kontak-info-icon"><i class="fas fa-envelope"></i></div>
                            <div>
                                <div class="kontak-info-label">Email</div>
                                <div class="kontak-info-value">info@miftahululum-jember.sch.id</div>
                            </div>
                        </div>
                        <div class="kontak-info-item">
                            <div class="kontak-info-icon"><i class="fas fa-clock"></i></div>
                            <div>
                                <div class="kontak-info-label">Jam Operasional</div>
                                <div class="kontak-info-value">Senin – Jumat: 08.00 – 16.00 WIB</div>
                            </div>
                        </div>
                    </div>
                    <div class="kontak-map" style="display:block; padding:0; background:transparent; border:none; height:auto; margin-top:30px">
                        <iframe
                            src="https://www.google.com/maps?q=Pondok+Pesantren+Miftahul+Ulum+Glagahwero+Kalisat+Jember&output=embed"
                            width="100%"
                            height="300"
                            style="border:0; border-radius:20px; display:block;"
                            allowfullscreen=""
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                        <div style="text-align:center; margin-top:12px;">
                            <a href="https://maps.google.com/?q=Pondok+Pesantren+Miftahul+Ulum+Glagahwero+Kalisat+Jember"
                                target="_blank" class="btn-green"
                                style="font-size:0.82rem; padding:10px 20px; display:inline-flex; align-items:center; gap:8px; text-decoration:none;">
                                <i class="fas fa-directions"></i> Buka di Google Maps
                            </a>
                        </div>
                    </div>
                </div>
                <div class="reveal-right">
                    <div class="section-tag">Kirim Pesan</div>
                    <h3 style="font-family:'Playfair Display',serif; font-size:1.5rem; color:var(--green); margin-bottom:8px">Kirimkan Pertanyaan Anda</h3>
                    <p style="color:var(--text-muted); font-size:0.88rem; margin-bottom:24px">Kami akan membalas dalam waktu 1×24 jam pada hari kerja</p>
                    <div class="kontak-form">
                        <div class="form-group">
                            <label>Nama Lengkap</label>
                            <input type="text" placeholder="Nama Anda">
                        </div>
                        <div class="form-group">
                            <label>Email / No. HP</label>
                            <input type="text" placeholder="email@contoh.com atau 08xxx">
                        </div>
                        <div class="form-group">
                            <label>Perihal</label>
                            <input type="text" placeholder="Contoh: Pertanyaan seputar pendaftaran">
                        </div>
                        <div class="form-group">
                            <label>Pesan</label>
                            <textarea placeholder="Tulis pertanyaan atau pesan Anda di sini..."></textarea>
                        </div>
                        <button class="btn-green" onclick="handleContact(this)">
                            <i class="fas fa-paper-plane"></i> Kirim Pesan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer>
        <!-- Islamic geometric background -->
        <svg style="position:absolute;top:0;left:0;right:0;width:100%;height:100%;opacity:0.03;pointer-events:none" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice">
            <defs>
                <pattern id="footPat" x="0" y="0" width="40" height="40" patternUnits="userSpaceOnUse">
                    <path d="M20 0 L40 20 L20 40 L0 20 Z" fill="none" stroke="white" stroke-width="0.5" />
                    <circle cx="20" cy="20" r="8" fill="none" stroke="white" stroke-width="0.4" />
                </pattern>
            </defs>
            <rect width="200" height="200" fill="url(#footPat)" />
        </svg>
        <div class="footer-inner" style="position:relative;z-index:1">
            <div class="footer-grid">
                <div>
                    <a href="#" class="footer-brand-logo">
                        <div class="footer-logo-box">☽</div>
                        <div>
                            <div class="footer-brand-name">Miftahul Ulum</div>
                            <div class="footer-brand-sub">Jember, Est. 1980</div>
                        </div>
                    </a>
                    <p class="footer-desc">Pondok Pesantren Miftahul Ulum Jember — Membentuk generasi Qur'ani yang berakhlak mulia, berilmu, dan bermanfaat bagi umat dan bangsa Indonesia.</p>
                    <div class="footer-socials">
                        <a href="#" class="social-btn"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="social-btn"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="social-btn"><i class="fab fa-youtube"></i></a>
                        <a href="#" class="social-btn"><i class="fab fa-tiktok"></i></a>
                        <a href="#" class="social-btn"><i class="fab fa-whatsapp"></i></a>
                    </div>
                </div>
                <div>
                    <div class="footer-col-title">Menu Utama</div>
                    <ul class="footer-links">
                        <li><a href="#about">Profil Pondok</a></li>
                        <li><a href="#program">Program Pendidikan</a></li>
                        <li><a href="#why">Keunggulan</a></li>
                        <li><a href="#gallery">Galeri Kegiatan</a></li>
                        <li><a href="#jadwal">Jadwal Harian</a></li>
                        <li><a href="#testimoni">Testimoni</a></li>
                        <li><a href="#pendaftaran">Pendaftaran</a></li>
                    </ul>
                </div>
                <div>
                    <div class="footer-col-title">Program Kami</div>
                    <ul class="footer-links">
                        <li><a href="#program">Tahfidz Al-Qur'an</a></li>
                        <li><a href="#program">Madrasah Diniyah</a></li>
                        <li><a href="#program">MTs & MA Formal</a></li>
                        <li><a href="#program">Keasramaan</a></li>
                        <li><a href="#program">Bahasa Asing</a></li>
                        <li><a href="#program">Pengembangan Diri</a></li>
                    </ul>
                </div>
                <div>
                    <div class="footer-col-title">Hubungi Kami</div>
                    <ul class="footer-contact-list">
                        <li><i class="fas fa-map-marker-alt"></i> Jl. Pesantren No. 123, Sumbersari, Jember 68121</li>
                        <li><i class="fas fa-phone-alt"></i> (0331) 1234567</li>
                        <li><i class="fab fa-whatsapp"></i> 081234567890</li>
                        <li><i class="fas fa-envelope"></i> info@miftahululum-jember.sch.id</li>
                        <li><i class="fas fa-clock"></i> Senin–Jumat, 08.00–16.00 WIB</li>
                    </ul>
                </div>
            </div>
            <hr class="footer-divider">
            <div class="footer-bottom">
                <p>© 2025 Pondok Pesantren Miftahul Ulum Jember. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- FLOATING WA -->
    <a href="https://wa.me/6281234567890" class="floating-wa" target="_blank" title="Chat WhatsApp">
        <i class="fab fa-whatsapp"></i>
    </a>
    <!-- LIGHTBOX GALLERY -->
    <div class="lightbox-overlay" id="lightboxOverlay">
        <div class="lightbox-box">
            <button class="lightbox-close" id="lightboxClose" aria-label="Tutup">
                <i class="fas fa-times"></i>
            </button>

            <div class="lightbox-slider-wrap">
                <div class="lightbox-counter" id="lightboxCounter">1 / 8</div>
                <div class="lightbox-track" id="lightboxTrack">

                    <div class="lightbox-slide">
                        <img src="https://images.unsplash.com/photo-1585032226651-759b368d7246?w=1200&auto=format&fit=crop" alt="Kegiatan Belajar">
                        <div class="lightbox-caption">📚 Kegiatan Belajar Bersama Santri</div>
                    </div>
                    <div class="lightbox-slide">
                        <img src="https://images.unsplash.com/photo-1577896851231-70ef18881754?w=1200&auto=format&fit=crop" alt="Ngaji">
                        <div class="lightbox-caption">🕌 Suasana Ngaji di Pesantren</div>
                    </div>
                    <div class="lightbox-slide">
                        <img src="https://images.unsplash.com/photo-1580582932707-520aed937b7b?w=1200&auto=format&fit=crop" alt="Sholat Berjamaah">
                        <div class="lightbox-caption">🤲 Sholat Berjamaah di Masjid</div>
                    </div>
                    <div class="lightbox-slide">
                        <img src="https://images.unsplash.com/photo-1519817650390-64a93db51149?w=1200&auto=format&fit=crop" alt="Olahraga">
                        <div class="lightbox-caption">⚽ Kegiatan Olahraga Santri</div>
                    </div>
                    <div class="lightbox-slide">
                        <img src="https://images.unsplash.com/photo-1529156069898-49953e39b3ac?w=1200&auto=format&fit=crop" alt="Wisuda">
                        <div class="lightbox-caption">🎓 Wisuda & Khataman Al-Qur'an</div>
                    </div>
                    <div class="lightbox-slide">
                        <img src="https://images.unsplash.com/photo-1585036156171-384164a8c675?w=1200&auto=format&fit=crop" alt="Kegiatan Pondok">
                        <div class="lightbox-caption">🌿 Lingkungan Pondok Pesantren</div>
                    </div>
                    <div class="lightbox-slide">
                        <img src="https://images.unsplash.com/photo-1566669437688-88f436a61e4f?w=1200&auto=format&fit=crop" alt="Masjid">
                        <div class="lightbox-caption">🕌 Masjid Utama Miftahul Ulum</div>
                    </div>
                    <div class="lightbox-slide">
                        <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=1200&auto=format&fit=crop" alt="Santri">
                        <div class="lightbox-caption">📖 Setoran Hafalan Al-Qur'an</div>
                    </div>

                </div>

                <button class="lightbox-btn lightbox-prev" id="lightboxPrev" aria-label="Sebelumnya">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button class="lightbox-btn lightbox-next" id="lightboxNext" aria-label="Berikutnya">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>

            <!-- Thumbnail strip -->
            <div class="lightbox-thumbs" id="lightboxThumbs"></div>

            <!-- Dots -->
            <div class="lightbox-dots" id="lightboxDots"></div>
        </div>
    </div>
    <!-- BACK TO TOP -->
    <a href="#hero" id="backTop" title="Kembali ke atas">
        <i class="fas fa-arrow-up"></i>
    </a>

    <script>
        // ── NAVBAR
        const navbar = document.getElementById('navbar');
        const navLinks = document.getElementById('navLinks');
        const hamburger = document.getElementById('hamburger');

        window.addEventListener('scroll', () => {
            if (window.scrollY > 80) {
                navbar.classList.add('scrolled');
                document.getElementById('backTop').classList.add('show');
            } else {
                navbar.classList.remove('scrolled');
                document.getElementById('backTop').classList.remove('show');
            }
        });

        hamburger.addEventListener('click', () => {
            navLinks.classList.toggle('open');
        });

        // Close mobile nav on link click
        navLinks.querySelectorAll('a').forEach(a => {
            a.addEventListener('click', () => navLinks.classList.remove('open'));
        });

        // ── SCROLL REVEAL
        const revealElements = document.querySelectorAll('.reveal, .reveal-left, .reveal-right');
        const revealObs = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, {
            threshold: 0.12,
            rootMargin: '0px 0px -60px 0px'
        });

        revealElements.forEach(el => revealObs.observe(el));

        // ── COUNT UP ANIMATION
        const counters = document.querySelectorAll('.stat-num[data-target]');
        const counterObs = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const el = entry.target;
                    const target = parseInt(el.dataset.target);
                    const duration = 1800;
                    const step = target / (duration / 16);
                    let current = 0;
                    const timer = setInterval(() => {
                        current += step;
                        if (current >= target) {
                            el.textContent = target;
                            clearInterval(timer);
                        } else {
                            el.textContent = Math.floor(current);
                        }
                    }, 16);
                    counterObs.unobserve(el);
                }
            });
        }, {
            threshold: 0.5
        });

        counters.forEach(c => counterObs.observe(c));

        // ── PROGRESS BARS
        const progressBars = document.querySelectorAll('.progress-bar-fill[data-width]');
        const progressObs = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const el = entry.target;
                    setTimeout(() => {
                        el.style.width = el.dataset.width + '%';
                    }, 200);
                    progressObs.unobserve(el);
                }
            });
        }, {
            threshold: 0.3
        });

        progressBars.forEach(p => {
            p.style.width = '0%';
            progressObs.observe(p);
        });

        // ── SMOOTH SCROLL
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                const href = this.getAttribute('href');
                if (href === '#') return;
                e.preventDefault();
                const target = document.querySelector(href);
                if (target) {
                    const offset = 80;
                    const top = target.getBoundingClientRect().top + window.pageYOffset - offset;
                    window.scrollTo({
                        top,
                        behavior: 'smooth'
                    });
                }
            });
        });

        // ── ACTIVE NAV
        const sections = document.querySelectorAll('section[id]');
        window.addEventListener('scroll', () => {
            const scrollY = window.pageYOffset;
            sections.forEach(section => {
                const sectionTop = section.offsetTop - 100;
                const sectionId = section.getAttribute('id');
                const link = document.querySelector(`.nav-links a[href="#${sectionId}"]`);
                if (link) {
                    if (scrollY >= sectionTop && scrollY < sectionTop + section.offsetHeight) {
                        document.querySelectorAll('.nav-links a').forEach(l => l.style.color = '');
                        link.style.color = 'var(--gold-light)';
                    }
                }
            });
        });

        // ── TICKER DUPLICATE
        const ticker = document.getElementById('ticker');
        ticker.innerHTML += ticker.innerHTML;

        // ── FORM HANDLERS
        function handleSubmit(btn) {
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin" style="margin-right:8px"></i> Mengirim...';
            setTimeout(() => {
                btn.innerHTML = '<i class="fas fa-check-circle" style="margin-right:8px"></i> Terkirim! Kami akan menghubungi Anda';
                btn.style.background = '#16a34a';
            }, 1800);
        }

        function handleContact(btn) {
            btn.disabled = true;
            const orig = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mengirim...';
            setTimeout(() => {
                btn.innerHTML = '<i class="fas fa-check-circle"></i> Pesan Terkirim!';
                btn.style.background = '#16a34a';
            }, 1500);
        }
        // ── LIGHTBOX GALLERY
        const totalSlides = document.querySelectorAll('.lightbox-slide').length;
        let currentSlide = 0;

        const track = document.getElementById('lightboxTrack');
        const counter = document.getElementById('lightboxCounter');
        const dotsWrap = document.getElementById('lightboxDots');
        const thumbsWrap = document.getElementById('lightboxThumbs');

        // Buat dots & thumbnails otomatis
        document.querySelectorAll('.lightbox-slide').forEach((slide, i) => {
            // Dot
            const dot = document.createElement('button');
            dot.className = 'lightbox-dot' + (i === 0 ? ' active' : '');
            dot.onclick = () => goToSlide(i);
            dotsWrap.appendChild(dot);

            // Thumbnail
            const img = slide.querySelector('img');
            const thumb = document.createElement('img');
            thumb.src = img.src;
            thumb.className = 'lightbox-thumb' + (i === 0 ? ' active' : '');
            thumb.alt = img.alt;
            thumb.onclick = () => goToSlide(i);
            thumbsWrap.appendChild(thumb);
        });

        function goToSlide(n) {
            currentSlide = (n + totalSlides) % totalSlides;
            track.style.transform = `translateX(-${currentSlide * 100}%)`;
            counter.textContent = `${currentSlide + 1} / ${totalSlides}`;

            document.querySelectorAll('.lightbox-dot').forEach((d, i) =>
                d.classList.toggle('active', i === currentSlide));
            document.querySelectorAll('.lightbox-thumb').forEach((t, i) =>
                t.classList.toggle('active', i === currentSlide));

            // Auto-scroll thumbnail yang aktif ke tengah
            const activeThumb = thumbsWrap.children[currentSlide];
            activeThumb.scrollIntoView({
                behavior: 'smooth',
                block: 'nearest',
                inline: 'center'
            });
        }

        function openLightbox(startIndex = 0) {
            goToSlide(startIndex);
            document.getElementById('lightboxOverlay').classList.add('open');
            document.body.style.overflow = 'hidden';
        }

        function closeLightbox() {
            document.getElementById('lightboxOverlay').classList.remove('open');
            document.body.style.overflow = '';
        }

        document.getElementById('lightboxClose').onclick = closeLightbox;
        document.getElementById('lightboxPrev').onclick = () => goToSlide(currentSlide - 1);
        document.getElementById('lightboxNext').onclick = () => goToSlide(currentSlide + 1);

        // Klik backdrop untuk tutup
        document.getElementById('lightboxOverlay').addEventListener('click', function(e) {
            if (e.target === this) closeLightbox();
        });

        // Keyboard: arrow keys & Escape
        document.addEventListener('keydown', function(e) {
            if (!document.getElementById('lightboxOverlay').classList.contains('open')) return;
            if (e.key === 'ArrowLeft') goToSlide(currentSlide - 1);
            if (e.key === 'ArrowRight') goToSlide(currentSlide + 1);
            if (e.key === 'Escape') closeLightbox();
        });

        // Swipe touch support
        let touchStartX = 0;
        track.addEventListener('touchstart', e => touchStartX = e.touches[0].clientX);
        track.addEventListener('touchend', e => {
            const diff = touchStartX - e.changedTouches[0].clientX;
            if (Math.abs(diff) > 50) goToSlide(currentSlide + (diff > 0 ? 1 : -1));
        });

        // Foto galeri utama juga bisa klik untuk buka lightbox
        document.querySelectorAll('.gallery-item:not(button)').forEach((item, i) => {
            item.style.cursor = 'pointer';
            item.addEventListener('click', () => openLightbox(i));
        });
    </script>
</body>

</html>