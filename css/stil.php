<?php
echo '<style>
:root {
    --bg: #f4efe7;
    --bg-soft: #eae1d2;
    --panel: #ffffff;
    --panel-light: #ffffff;
    --panel-border: rgba(170, 132, 78, 0.18);
    --text: #2e261d;
    --text-dark: #1e1b18;
    --muted: #716354;
    --accent: #d18a2f;
    --accent-strong: #b87422;
    --accent-soft: #f2e0c4;
    --danger: #b94b43;
    --success: #3f8f67;
    --info: #3f7aa5;
    --neutral: #5f6d79;
    --shadow: 0 18px 40px rgba(78, 57, 31, 0.12);
    --radius-xl: 28px;
    --radius-lg: 20px;
    --radius-md: 14px;
}

* {
    box-sizing: border-box;
}

html, body {
    margin: 0;
    padding: 0;
    min-height: 100%;
}

body {
    font-family: "Segoe UI", "Trebuchet MS", sans-serif;
    color: var(--text);
    background: linear-gradient(180deg, #f7f1e7 0%, #f1eadf 100%);
}

.app-shell {
    position: relative;
    width: min(1200px, calc(100% - 32px));
    margin: 20px auto;
    padding: 24px;
    border: 1px solid rgba(101, 76, 44, 0.08);
    border-radius: 34px;
    background: rgba(255, 252, 247, 0.96);
    box-shadow: var(--shadow);
}

.app-shell-print {
    width: min(1100px, calc(100% - 24px));
    background: #ffffff;
    color: var(--text-dark);
}

.app-header {
    display: flex;
    justify-content: space-between;
    gap: 24px;
    align-items: flex-start;
    padding: 18px 20px 24px;
    border-radius: var(--radius-xl);
    background: linear-gradient(135deg, #fffaf2, #f5ecde);
    border: 1px solid rgba(170, 132, 78, 0.14);
}

.brand-kicker,
.page-eyebrow {
    margin: 0 0 8px;
    letter-spacing: 0.22em;
    text-transform: uppercase;
    color: var(--accent-strong);
    font-size: 0.74rem;
}

.brand-title {
    display: inline-block;
    margin-bottom: 8px;
    color: #2d241b;
    font-family: "Palatino Linotype", "Book Antiqua", serif;
    font-size: clamp(1.7rem, 2.6vw, 2.5rem);
    font-weight: 700;
    text-decoration: none;
}

.brand-subtitle {
    max-width: 640px;
    color: var(--muted);
    line-height: 1.6;
}

.top-nav {
    display: flex;
    gap: 12px;
    align-items: center;
    flex-wrap: wrap;
    justify-content: flex-end;
}

.top-nav a,
.top-nav-user {
    display: inline-flex;
    align-items: center;
    min-height: 42px;
    padding: 0 16px;
    border-radius: 999px;
    border: 1px solid rgba(170, 132, 78, 0.18);
    color: var(--text);
    text-decoration: none;
    background: rgba(255, 255, 255, 0.8);
}

.top-nav a:hover {
    border-color: rgba(170, 132, 78, 0.34);
    color: #1f1a15;
}

.top-nav-user {
    color: var(--accent-soft);
}

.page-hero {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 24px;
    margin: 24px 0;
    padding: 28px;
    border-radius: var(--radius-xl);
    background: linear-gradient(135deg, #f8efe0, #f3e6d2);
    color: var(--text-dark);
}

.page-hero h1 {
    margin: 0 0 12px;
    font-family: "Palatino Linotype", "Book Antiqua", serif;
    font-size: clamp(1.8rem, 3vw, 2.8rem);
}

.page-hero p {
    margin: 0;
    line-height: 1.7;
}

.hero-accent {
    display: flex;
    flex-direction: column;
    justify-content: center;
    gap: 14px;
}

.hero-accent-line {
    width: 100%;
    height: 6px;
    border-radius: 999px;
    background: linear-gradient(90deg, #1d2933 0%, #d18a2f 100%);
}

.hero-accent-copy {
    padding: 18px;
    border-radius: var(--radius-lg);
    background: rgba(255, 255, 255, 0.72);
    color: var(--text-dark);
    line-height: 1.7;
}

.flash-message {
    margin: 0 0 24px;
    padding: 16px 18px;
    border-radius: var(--radius-md);
    border: 1px solid transparent;
}

.flash-success {
    background: rgba(63, 143, 103, 0.18);
    border-color: rgba(63, 143, 103, 0.45);
}

.flash-error {
    background: rgba(185, 75, 67, 0.18);
    border-color: rgba(185, 75, 67, 0.45);
}

.page-layout {
    display: block;
}

.page-layout-sidebar {
    display: grid;
    grid-template-columns: 280px 1fr;
    gap: 24px;
}

.sidebar-card,
.content-surface,
.panel-card {
    border-radius: var(--radius-xl);
}

.sidebar-card {
    padding: 24px 20px;
    background: #fffaf4;
    border: 1px solid rgba(101, 76, 44, 0.08);
    position: sticky;
    top: 20px;
    height: fit-content;
}

.sidebar-card h2 {
    margin: 0 0 18px;
    font-family: "Palatino Linotype", "Book Antiqua", serif;
}

.sidebar-menu {
    display: grid;
    gap: 10px;
}

.sidebar-menu a {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 14px 16px;
    border-radius: var(--radius-md);
    color: var(--text);
    text-decoration: none;
    background: rgba(255,255,255,0.84);
    border: 1px solid rgba(101, 76, 44, 0.06);
}

.sidebar-menu a:hover,
.sidebar-menu a.is-active {
    border-color: rgba(240, 180, 95, 0.3);
    background: rgba(240, 180, 95, 0.09);
}

.content-surface {
    min-height: 420px;
    min-width: 0;
}

.panel-card {
    padding: 24px;
    background: rgba(245, 239, 227, 0.97);
    color: var(--text-dark);
    border: 1px solid rgba(36, 30, 21, 0.08);
    min-width: 0;
}

.panel-card + .panel-card {
    margin-top: 20px;
}

.panel-card-print {
    background: #ffffff;
    padding: 8px 0 0;
}

.panel-header,
.zaglavlje-stavki,
.rezime-porudzbine,
.form-actions,
.filter-bar,
.stats-grid,
.cards-grid,
.feature-grid,
.detail-grid,
.print-head {
    display: grid;
    gap: 16px;
}

.panel-header,
.zaglavlje-stavki,
.rezime-porudzbine,
.form-actions {
    grid-template-columns: 1fr auto;
    align-items: center;
}

.panel-header h2,
.zaglavlje-stavki h3,
.detail-card h3,
.stats-card h3,
.feature-card h3 {
    margin: 0 0 8px;
    font-family: "Palatino Linotype", "Book Antiqua", serif;
}

.panel-header p,
.zaglavlje-stavki p,
.feature-card p,
.stats-card p,
.detail-card p {
    margin: 0;
    line-height: 1.6;
}

.panel-note {
    padding: 12px 16px;
    border-radius: var(--radius-md);
    background: rgba(209, 138, 47, 0.12);
    color: #6a451a;
    max-width: 340px;
}

.stats-grid,
.cards-grid,
.feature-grid,
.detail-grid {
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    margin-top: 18px;
}

.stats-card,
.feature-card,
.detail-card,
.promo-banner {
    padding: 22px;
    border-radius: var(--radius-lg);
    background: linear-gradient(145deg, rgba(255, 253, 248, 0.98), rgba(247, 240, 228, 0.96));
    color: var(--text-dark);
    border: 1px solid rgba(101, 76, 44, 0.08);
}

.stats-card strong {
    display: block;
    margin-top: 18px;
    color: #8f571c;
    font-size: 2rem;
}

.promo-banner {
    margin-top: 20px;
    background: linear-gradient(135deg, #f0d7ae, #e9c58b);
    color: var(--text-dark);
}

.promo-banner p {
    margin: 0;
    line-height: 1.7;
}

.form-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 16px;
}

.form-grid-full {
    grid-column: 1 / -1;
}

label span {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: #463628;
}

input,
select,
textarea,
button {
    font: inherit;
}

input,
select,
textarea {
    width: 100%;
    padding: 12px 14px;
    border-radius: 12px;
    border: 1px solid rgba(67, 53, 37, 0.16);
    background: #fffdfa;
    color: var(--text-dark);
}

textarea {
    resize: vertical;
}

input:focus,
select:focus,
textarea:focus {
    outline: 2px solid rgba(209, 138, 47, 0.26);
    border-color: rgba(209, 138, 47, 0.45);
}

.button {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-height: 44px;
    padding: 0 18px;
    border-radius: 999px;
    border: 1px solid transparent;
    background: linear-gradient(135deg, #d18a2f, #b96b20);
    color: #fffdf8;
    text-decoration: none;
    cursor: pointer;
}

.button:hover {
    filter: brightness(1.03);
}

.button-secondary {
    background: #1b2a35;
    color: #f7f2ea;
}

.button-ghost {
    background: transparent;
    color: #403123;
    border-color: rgba(64, 49, 35, 0.16);
}

.button-danger {
    background: #b94b43;
}

.table-wrap {
    width: 100%;
    max-width: 100%;
    overflow-x: auto;
}

.data-table {
    width: 100%;
    min-width: 1060px;
    border-collapse: collapse;
    margin-top: 16px;
    background: #fffdf8;
}

.data-table th,
.data-table td {
    padding: 14px 12px;
    border-bottom: 1px solid rgba(60, 46, 30, 0.09);
    vertical-align: top;
    text-align: left;
}

.data-table th {
    color: #6a4a24;
    font-size: 0.86rem;
    text-transform: uppercase;
    letter-spacing: 0.08em;
}

.col-broj {
    width: 220px;
}

.col-datum,
.col-placanje,
.col-stavke,
.col-ukupno,
.col-akcije {
    white-space: nowrap;
}

.col-datum {
    width: 130px;
}

.col-status {
    width: 150px;
}

.col-placanje {
    width: 130px;
}

.col-stavke {
    width: 80px;
}

.col-ukupno {
    width: 150px;
}

.col-akcije {
    width: 132px;
}

.broj-porudzbine {
    display: inline-block;
    line-height: 1.35;
    white-space: nowrap;
    overflow-wrap: normal;
    word-break: normal;
}

.muted-text {
    color: #776757;
    font-size: 0.92rem;
}

.action-stack {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    gap: 10px;
}

.action-stack form {
    margin: 0;
}

.action-stack .button {
    min-height: 38px;
    padding: 0 14px;
}

.filter-bar {
    grid-template-columns: minmax(0, 2fr) minmax(220px, 1fr) auto;
    align-items: end;
}

.filter-actions {
    display: flex;
    gap: 10px;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    min-height: 30px;
    padding: 0 12px;
    border-radius: 999px;
    font-size: 0.92rem;
    font-weight: 600;
}

.badge-neutral {
    background: rgba(95, 109, 121, 0.14);
    color: #41505c;
}

.badge-warm {
    background: rgba(209, 138, 47, 0.14);
    color: #935d16;
}

.badge-info {
    background: rgba(63, 122, 165, 0.14);
    color: #29597d;
}

.badge-success {
    background: rgba(63, 143, 103, 0.14);
    color: #256b4a;
}

.rezime-porudzbine-kutija {
    display: inline-grid;
    justify-items: end;
    gap: 6px;
    padding: 14px 18px;
    border-radius: var(--radius-lg);
    background: rgba(27, 42, 53, 0.07);
}

.rezime-porudzbine-kutija strong {
    font-size: 1.25rem;
}

.inline-message {
    min-height: 24px;
    color: #9b2f2f;
}

.table-total-label,
.table-total-value {
    font-weight: 700;
}

.detail-card {
    color: var(--text-dark);
    background: rgba(27, 42, 53, 0.07);
}

.print-head {
    grid-template-columns: 1fr auto;
    align-items: start;
    margin-bottom: 18px;
}

.print-meta {
    display: grid;
    gap: 8px;
    justify-items: end;
    color: #4f4235;
}

.app-footer {
    display: flex;
    justify-content: space-between;
    gap: 16px;
    margin-top: 24px;
    padding: 18px 20px 4px;
    color: var(--muted);
    font-size: 0.95rem;
}

.empty-state {
    padding: 26px;
    border-radius: var(--radius-lg);
    background: rgba(27, 42, 53, 0.07);
    color: #4a3f35;
}

.catalog-card {
    display: grid;
    gap: 14px;
    padding: 18px;
    border-radius: var(--radius-lg);
    background: rgba(27, 42, 53, 0.07);
}

.catalog-meta {
    display: flex;
    justify-content: space-between;
    gap: 12px;
    color: #6f5f4e;
}

.catalog-price {
    font-size: 1.2rem;
    color: #7d4b11;
    font-weight: 700;
}

.print-body .app-header,
.print-body .page-hero,
.print-body .app-footer,
.print-body .sidebar-card,
.print-body .top-nav {
    box-shadow: none;
}

@media (max-width: 960px) {
    .page-layout-sidebar {
        grid-template-columns: 1fr;
    }

    .sidebar-card {
        position: static;
    }

    .page-hero,
    .panel-header,
    .zaglavlje-stavki,
    .rezime-porudzbine,
    .form-actions,
    .print-head,
    .filter-bar {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 720px) {
    .app-shell {
        width: min(100% - 16px, 1200px);
        padding: 16px;
        border-radius: 24px;
    }

    .app-header {
        padding: 18px;
    }

    .form-grid {
        grid-template-columns: 1fr;
    }

    .data-table th,
    .data-table td {
        padding: 12px 10px;
        font-size: 0.95rem;
    }

    .top-nav {
        justify-content: flex-start;
    }

    .app-footer {
        flex-direction: column;
    }
}

@media print {
    body {
        background: #ffffff;
        color: #000000;
    }

    .app-stage,
    .page-hero,
    .sidebar-card,
    .flash-message,
    .filter-bar,
    .button,
    .top-nav,
    .app-footer {
        display: none !important;
    }

    .app-shell,
    .panel-card {
        width: 100%;
        margin: 0;
        padding: 0;
        background: #ffffff;
        color: #000000;
        border: 0;
        box-shadow: none;
    }
}
</style>';
?>
