<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="description" content="Scan book barcodes with your phone camera to read ISBN numbers and look up book details.">
    <title>Könyv azonosító</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --bg-primary: #0a0a0f;
            --bg-secondary: #12121a;
            --bg-card: rgba(22, 22, 35, 0.85);
            --bg-glass: rgba(255, 255, 255, 0.04);
            --border-glass: rgba(255, 255, 255, 0.08);
            --text-primary: #f0f0f5;
            --text-secondary: #8888a0;
            --text-muted: #55556a;
            --accent: #6c5ce7;
            --accent-light: #a29bfe;
            --accent-glow: rgba(108, 92, 231, 0.3);
            --success: #00cec9;
            --success-glow: rgba(0, 206, 201, 0.25);
            --danger: #fd79a8;
            --warning: #fdcb6e;
            --radius: 16px;
            --radius-sm: 10px;
            --radius-xs: 6px;
            --transition: 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: var(--bg-primary);
            color: var(--text-primary);
            min-height: 100dvh;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }

        /* Layout */
        .app-container {
            position: relative;
            z-index: 1;
            max-width: 480px;
            margin: 0 auto;
            padding: 16px;
            min-height: 100dvh;
            display: flex;
            flex-direction: column;
        }

        /* Header */
        .app-header {
            text-align: center;
            padding: 20px 0 16px;
        }

        .app-header h1 {
            font-size: 1.5rem;
            font-weight: 700;
            background: linear-gradient(135deg, var(--text-primary) 0%, var(--accent-light) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            letter-spacing: -0.02em;
        }

        .app-header p {
            font-size: 0.8rem;
            color: var(--text-muted);
            margin-top: 4px;
            font-weight: 400;
        }

        /* Scanner Section */
        .scanner-section {
            position: relative;
            margin-bottom: 16px;
        }

        .scanner-viewport {
            position: relative;
            width: 100%;
            aspect-ratio: 4/3;
            border-radius: var(--radius);
            overflow: hidden;
            background: var(--bg-secondary);
            border: 1px solid var(--border-glass);
        }

        .scanner-viewport video {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .scanner-viewport canvas {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }

        .scanner-viewport.active {
            border-color: var(--accent);
            box-shadow: 0 0 30px var(--accent-glow), inset 0 0 30px rgba(0, 0, 0, 0.3);
        }

        /* Scan overlay */
        .scan-overlay {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            pointer-events: none;
            z-index: 2;
        }

        .scan-frame {
            width: 83%;
            height: 45%;
            position: relative;
        }

        .scan-frame .corner {
            position: absolute;
            width: 24px;
            height: 24px;
            border-color: var(--accent-light);
            border-style: solid;
            border-width: 0;
        }

        .scan-frame .corner.tl { top: 0; left: 0; border-top-width: 3px; border-left-width: 3px; border-top-left-radius: 8px; }
        .scan-frame .corner.tr { top: 0; right: 0; border-top-width: 3px; border-right-width: 3px; border-top-right-radius: 8px; }
        .scan-frame .corner.bl { bottom: 0; left: 0; border-bottom-width: 3px; border-left-width: 3px; border-bottom-left-radius: 8px; }
        .scan-frame .corner.br { bottom: 0; right: 0; border-bottom-width: 3px; border-right-width: 3px; border-bottom-right-radius: 8px; }

        /* Placeholder state */
        .scanner-placeholder {
            position: absolute;
            inset: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 12px;
            z-index: 3;
            transition: opacity var(--transition);
        }

        .scanner-placeholder.hidden {
            opacity: 0;
            pointer-events: none;
        }

        .scanner-placeholder svg {
            width: 48px;
            height: 48px;
            color: var(--text-muted);
            opacity: 0.5;
        }

        .scanner-placeholder span {
            font-size: 0.85rem;
            color: var(--text-muted);
        }

        /* Scan button */
        .scan-btn-wrap {
            display: flex;
            justify-content: center;
            margin-top: 12px;
        }

        .scan-btn {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 14px 36px;
            border: none;
            border-radius: 50px;
            font-family: inherit;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            transition: all var(--transition);
            outline: none;
            position: relative;
            overflow: hidden;
        }

        .scan-btn.start {
            background: linear-gradient(135deg, var(--accent) 0%, #7c6cf0 100%);
            color: white;
            box-shadow: 0 4px 20px var(--accent-glow);
        }

        .scan-btn.start:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 30px var(--accent-glow);
        }

        .scan-btn.start:active {
            transform: translateY(0);
        }

        .scan-btn.stop {
            background: linear-gradient(135deg, #e74c6f 0%, var(--danger) 100%);
            color: white;
            box-shadow: 0 4px 20px rgba(253, 121, 168, 0.3);
        }

        .scan-btn.stop:hover {
            transform: translateY(-2px);
        }

        .scan-btn svg {
            width: 20px;
            height: 20px;
        }

        /* Pulse animation for scanning state */
        .scan-btn.stop::before {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: 50px;
            background: inherit;
            animation: btnPulse 2s ease-in-out infinite;
            z-index: -1;
        }

        @keyframes btnPulse {
            0%, 100% { transform: scale(1); opacity: 0.5; }
            50% { transform: scale(1.08); opacity: 0; }
        }

        /* Result Card */
        .result-card {
            background: var(--bg-card);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--border-glass);
            border-radius: var(--radius);
            padding: 20px;
            margin-bottom: 16px;
            animation: slideUp 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            display: none;
        }

        .result-card.visible {
            display: block;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .result-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 16px;
        }

        .result-icon {
            width: 40px;
            height: 40px;
            border-radius: var(--radius-sm);
            background: linear-gradient(135deg, var(--success) 0%, #00b8b3 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .result-icon svg {
            width: 20px;
            height: 20px;
            color: white;
        }

        .result-label {
            font-size: 0.75rem;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.08em;
            font-weight: 500;
        }

        .result-isbn {
            font-size: 1.6rem;
            font-weight: 700;
            letter-spacing: 0.04em;
            color: var(--text-primary);
            font-variant-numeric: tabular-nums;
        }

        .isbn-actions {
            display: flex;
            gap: 8px;
            margin-top: 12px;
        }

        .isbn-action-btn {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            border: 1px solid var(--border-glass);
            border-radius: var(--radius-xs);
            background: var(--bg-glass);
            color: var(--text-secondary);
            font-family: inherit;
            font-size: 0.78rem;
            font-weight: 500;
            cursor: pointer;
            transition: all var(--transition);
            outline: none;
        }

        .isbn-action-btn:hover {
            background: rgba(255, 255, 255, 0.08);
            color: var(--text-primary);
            border-color: rgba(255, 255, 255, 0.15);
        }

        .isbn-action-btn.copied {
            border-color: var(--success);
            color: var(--success);
        }

        .isbn-action-btn svg {
            width: 14px;
            height: 14px;
        }

        /* Book Info */
        .book-info {
            margin-top: 16px;
            padding-top: 16px;
            border-top: 1px solid var(--border-glass);
            display: none;
        }

        .book-info.visible {
            display: flex;
            gap: 14px;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .book-cover {
            width: 70px;
            height: 100px;
            border-radius: var(--radius-xs);
            background: var(--bg-glass);
            overflow: hidden;
            flex-shrink: 0;
            border: 1px solid var(--border-glass);
        }

        .book-cover img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .book-cover .no-cover {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-muted);
            font-size: 0.65rem;
            text-align: center;
            padding: 8px;
        }

        .book-details {
            flex: 1;
            min-width: 0;
        }

        .book-title {
            font-size: 0.95rem;
            font-weight: 600;
            color: var(--text-primary);
            line-height: 1.3;
            margin-bottom: 4px;
        }

        .book-author {
            font-size: 0.8rem;
            color: var(--text-secondary);
            margin-bottom: 8px;
        }

        .book-meta {
            font-size: 0.72rem;
            color: var(--text-muted);
            line-height: 1.5;
        }

        .book-loading {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 16px;
            padding-top: 16px;
            border-top: 1px solid var(--border-glass);
            color: var(--text-muted);
            font-size: 0.8rem;
        }

        .book-loading.hidden {
            display: none;
        }

        .spinner {
            width: 18px;
            height: 18px;
            border: 2px solid var(--border-glass);
            border-top-color: var(--accent-light);
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* History Section */
        .history-section {
            flex: 1;
        }

        .section-title {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 12px;
        }

        .section-title h2 {
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }

        .section-title .count {
            font-size: 0.72rem;
            color: var(--text-muted);
            background: var(--bg-glass);
            padding: 3px 10px;
            border-radius: 20px;
            border: 1px solid var(--border-glass);
        }

        .history-list {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .history-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 14px;
            background: var(--bg-card);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid var(--border-glass);
            border-radius: var(--radius-sm);
            cursor: pointer;
            transition: all var(--transition);
            animation: slideIn 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes slideIn {
            from { opacity: 0; transform: translateX(-10px); }
            to { opacity: 1; transform: translateX(0); }
        }

        .history-item:hover {
            background: rgba(22, 22, 35, 0.95);
            border-color: rgba(255, 255, 255, 0.12);
        }

        .history-thumb {
            width: 36px;
            height: 48px;
            border-radius: 4px;
            background: var(--bg-glass);
            overflow: hidden;
            flex-shrink: 0;
            border: 1px solid var(--border-glass);
        }

        .history-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .history-thumb .no-thumb {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .history-thumb .no-thumb svg {
            width: 16px;
            height: 16px;
            color: var(--text-muted);
            opacity: 0.4;
        }

        .history-content {
            flex: 1;
            min-width: 0;
        }

        .history-isbn {
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--text-primary);
            font-variant-numeric: tabular-nums;
        }

        .history-book-title {
            font-size: 0.75rem;
            color: var(--text-secondary);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            margin-top: 2px;
        }

        .history-time {
            font-size: 0.68rem;
            color: var(--text-muted);
            flex-shrink: 0;
        }

        .empty-history {
            text-align: center;
            padding: 40px 20px;
            color: var(--text-muted);
        }

        .empty-history svg {
            width: 40px;
            height: 40px;
            opacity: 0.3;
            margin-bottom: 12px;
        }

        .empty-history p {
            font-size: 0.82rem;
        }

        /* Toast notification */
        .toast {
            position: fixed;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%) translateY(100px);
            background: var(--bg-card);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--border-glass);
            border-radius: var(--radius-sm);
            padding: 12px 20px;
            font-size: 0.82rem;
            color: var(--text-primary);
            z-index: 100;
            transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            pointer-events: none;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .toast.show {
            transform: translateX(-50%) translateY(0);
        }

        .toast svg {
            width: 16px;
            height: 16px;
            color: var(--success);
        }

        /* Error banner */
        .error-banner {
            background: rgba(253, 121, 168, 0.1);
            border: 1px solid rgba(253, 121, 168, 0.2);
            border-radius: var(--radius-sm);
            padding: 12px 16px;
            margin-bottom: 16px;
            font-size: 0.8rem;
            color: var(--danger);
            display: none;
            animation: fadeIn 0.3s ease;
        }

        .error-banner.visible {
            display: block;
        }
    </style>
</head>
<body>

<div class="app-container">
    <!-- Header -->
    <header class="app-header">
        <h1>📚 Könyv azonosító</h1>
        <p>Vidd a kamerád a könyv vonalkódjára!</p>
    </header>

    <!-- Error Banner -->
    <div id="errorBanner" class="error-banner"></div>

    <!-- Scanner -->
    <section class="scanner-section">
        <div id="scannerViewport" class="scanner-viewport">
            <div class="scanner-placeholder" id="scannerPlaceholder">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0ZM18.75 10.5h.008v.008h-.008V10.5Z" />
                </svg>
                <span>A kezdéshez nyomj a "Beolvasás"-ra</span>
            </div>
            <div class="scan-overlay">
                <div class="scan-frame">
                    <div class="corner tl"></div>
                    <div class="corner tr"></div>
                    <div class="corner bl"></div>
                    <div class="corner br"></div>
                </div>
            </div>
        </div>

        <div class="scan-btn-wrap">
            <button id="scanBtn" class="scan-btn start" onclick="toggleScanner()">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0 1 3.75 9.375v-4.5ZM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 0 1-1.125-1.125v-4.5ZM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0 1 13.5 9.375v-4.5Z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 0 1-1.125-1.125v-4.5Z" />
                </svg>
                <span>Beolvasás</span>
            </button>
        </div>
    </section>

    <!-- Result Card -->
    <section id="resultCard" class="result-card">
        <div class="result-header">
            <div class="result-icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                </svg>
            </div>
            <div>
                <div class="result-label">ISBN Felismerve</div>
                <div class="result-isbn" id="resultIsbn">—</div>
            </div>
        </div>

        <div class="isbn-actions">
            <button class="isbn-action-btn" id="copyBtn" onclick="copyIsbn()">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.666 3.888A2.25 2.25 0 0 0 13.5 2.25h-3c-1.03 0-1.9.693-2.166 1.638m7.332 0c.055.194.084.4.084.612v0a.75.75 0 0 1-.75.75H9.75a.75.75 0 0 1-.75-.75v0c0-.212.03-.418.084-.612m7.332 0c.646.049 1.288.11 1.927.184 1.1.128 1.907 1.077 1.907 2.185V19.5a2.25 2.25 0 0 1-2.25 2.25H6.75A2.25 2.25 0 0 1 4.5 19.5V6.257c0-1.108.806-2.057 1.907-2.185a48.208 48.208 0 0 1 1.927-.184" />
                </svg>
                <span>Copy ISBN</span>
            </button>
            <button class="isbn-action-btn" onclick="searchGoogle()">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                </svg>
                <span>Search</span>
            </button>
        </div>

        <div class="book-loading hidden" id="bookLoading">
            <div class="spinner"></div>
            <span>Looking up book info…</span>
        </div>

        <div class="book-info" id="bookInfo">
            <div class="book-cover" id="bookCover"></div>
            <div class="book-details">
                <div class="book-title" id="bookTitle"></div>
                <div class="book-author" id="bookAuthor"></div>
                <div class="book-meta" id="bookMeta"></div>
            </div>
        </div>
    </section>

    <!-- History -->
    <section class="history-section">
        <div class="section-title" id="historyHeader" style="display:none;">
            <h2>Scan History</h2>
            <span class="count" id="historyCount">0</span>
        </div>
        <div class="history-list" id="historyList"></div>
        <div class="empty-history" id="emptyHistory">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
            </svg>
            <p>Scanned books will appear here</p>
        </div>
    </section>
</div>

<!-- Toast -->
<div id="toast" class="toast">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
    </svg>
    <span id="toastMsg">Copied!</span>
</div>

<!-- QuaggaJS -->
<script src="https://cdn.jsdelivr.net/npm/@ericblade/quagga2@1.8.4/dist/quagga.min.js"></script>

<script>
    // State
    let isScanning = false;
    let lastScannedCode = '';
    let scanHistory = [];
    let scanCooldown = false;
    let candidateCode = '';
    let candidateHits = 0;
    let candidateTimer = null;

    // Checksum & format validator for ISBN-13 and ISBN-10
    function isValidIsbn(raw) {
        if (!raw) return false;
        const clean = raw.replace(/[-\s]/g, '').toUpperCase();

        // ISBN-13: 13 digits, must start with 978 or 979
        if (/^(978|979)\d{10}$/.test(clean)) {
            let sum = 0;
            for (let i = 0; i < 12; i++) {
                sum += parseInt(clean[i], 10) * (i % 2 === 0 ? 1 : 3);
            }
            const checkDigit = (10 - (sum % 10)) % 10;
            return checkDigit === parseInt(clean[12], 10);
        }

        // ISBN-10: 9 digits + check digit (0-9 or X)
        if (/^\d{9}[\dX]$/.test(clean)) {
            let sum = 0;
            for (let i = 0; i < 9; i++) {
                sum += parseInt(clean[i], 10) * (10 - i);
            }
            const lastChar = clean[9];
            sum += (lastChar === 'X') ? 10 : parseInt(lastChar, 10);
            return sum % 11 === 0;
        }

        return false;
    }

    // DOM elements
    const scanBtn = document.getElementById('scanBtn');
    const viewport = document.getElementById('scannerViewport');
    const placeholder = document.getElementById('scannerPlaceholder');
    const resultCard = document.getElementById('resultCard');
    const resultIsbn = document.getElementById('resultIsbn');
    const bookLoading = document.getElementById('bookLoading');
    const bookInfo = document.getElementById('bookInfo');
    const errorBanner = document.getElementById('errorBanner');

    function toggleScanner() {
        if (isScanning) {
            stopScanner();
        } else {
            startScanner();
        }
    }

    async function startScanner() {
        errorBanner.classList.remove('visible');

        // Check if getUserMedia is available (requires HTTPS or localhost)
        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
            showError('Camera not available. On mobile devices, this app requires HTTPS. Try accessing via a secure connection.');
            return;
        }

        // Explicitly request camera permission first.
        // iOS Safari REQUIRES getUserMedia to be called directly from a user gesture (tap/click).
        // Android Chrome also benefits from explicit permission request.
        let stream;
        try {
            stream = await navigator.mediaDevices.getUserMedia({
                video: {
                    facingMode: { ideal: 'environment' },
                    width: { ideal: 1280 },
                    height: { ideal: 960 }
                },
                audio: false
            });
        } catch (permErr) {
            console.error('Camera permission error:', permErr);

            if (permErr.name === 'NotAllowedError' || permErr.name === 'PermissionDeniedError') {
                showError('Camera access denied. Please allow camera permission in your browser settings and try again.');
            } else if (permErr.name === 'NotFoundError' || permErr.name === 'DevicesNotFoundError') {
                showError('No camera found. Please make sure your device has a camera.');
            } else if (permErr.name === 'NotReadableError' || permErr.name === 'TrackStartError') {
                showError('Camera is in use by another app. Close other apps using the camera and try again.');
            } else if (permErr.name === 'OverconstrainedError') {
                // Retry without facing mode constraint (some devices don't support environment)
                try {
                    stream = await navigator.mediaDevices.getUserMedia({
                        video: true,
                        audio: false
                    });
                } catch (retryErr) {
                    showError('Could not access camera. Please check your camera permissions.');
                    return;
                }
            } else if (permErr.name === 'TypeError') {
                showError('Camera access requires a secure connection (HTTPS). Please access this page over HTTPS.');
            } else {
                showError(`Camera error: ${permErr.message || permErr.name}. Please check permissions and try again.`);
            }

            if (!stream) return;
        }

        // Permission granted! Stop the pre-check stream so Quagga can acquire camera.
        stream.getTracks().forEach(track => track.stop());

        // Now init Quagga — camera permission already granted, so this will work on iOS & Android.
        Quagga.init({
            inputStream: {
                name: "Live",
                type: "LiveStream",
                target: viewport,
                constraints: {
                    facingMode: "environment",
                    width: { ideal: 1280 },
                    height: { ideal: 960 }
                }
            },
            decoder: {
                readers: [
                    "ean_reader"
                ]
            },
            locate: true,
            frequency: 10
        }, function(err) {
            if (err) {
                console.error('Quagga init error:', err);
                showError('Scanner failed to start. Please refresh the page and try again.');
                return;
            }
            Quagga.start();
            isScanning = true;
            viewport.classList.add('active');
            placeholder.classList.add('hidden');
            updateScanButton();
        });

        Quagga.onDetected(onBarcodeDetected);
    }

    function stopScanner() {
        Quagga.stop();
        Quagga.offDetected(onBarcodeDetected);
        isScanning = false;
        candidateCode = '';
        candidateHits = 0;
        if (candidateTimer) clearTimeout(candidateTimer);
        viewport.classList.remove('active');
        placeholder.classList.remove('hidden');

        // Remove video elements created by Quagga
        const videoEl = viewport.querySelector('video');
        if (videoEl) videoEl.remove();
        const canvasEls = viewport.querySelectorAll('canvas');
        canvasEls.forEach(c => c.remove());

        updateScanButton();
    }

    function updateScanButton() {
        if (isScanning) {
            scanBtn.className = 'scan-btn stop';
            scanBtn.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5.25 7.5A2.25 2.25 0 0 1 7.5 5.25h9a2.25 2.25 0 0 1 2.25 2.25v9a2.25 2.25 0 0 1-2.25 2.25h-9a2.25 2.25 0 0 1-2.25-2.25v-9Z" />
                    </svg>
                    <span>Stop</span>
                `;
        } else {
            scanBtn.className = 'scan-btn start';
            scanBtn.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0 1 3.75 9.375v-4.5ZM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 0 1-1.125-1.125v-4.5ZM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0 1 13.5 9.375v-4.5Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 0 1-1.125-1.125v-4.5Z" />
                    </svg>
                    <span>Beolvasás</span>
                `;
        }
    }

    function onBarcodeDetected(result) {
        if (!result || !result.codeResult || !result.codeResult.code) return;

        // Reject noisy frames with high decode error rate
        if (result.codeResult.decodedCodes) {
            const errors = result.codeResult.decodedCodes
                .filter(x => x.error !== undefined)
                .map(x => x.error);
            if (errors.length > 0) {
                const avgError = errors.reduce((a, b) => a + b, 0) / errors.length;
                if (avgError > 0.15) return;
            }
        }

        const code = result.codeResult.code.trim();

        // Strict ISBN validation: only ISBN-13 (978/979) or ISBN-10 with valid checksum
        if (!isValidIsbn(code)) {
            return;
        }

        // Frame consensus: require 2 matching consecutive detections to eliminate sensor jitter
        if (code === candidateCode) {
            candidateHits++;
        } else {
            candidateCode = code;
            candidateHits = 1;
        }

        if (candidateTimer) clearTimeout(candidateTimer);
        candidateTimer = setTimeout(() => {
            candidateCode = '';
            candidateHits = 0;
        }, 800);

        if (candidateHits < 2) {
            return;
        }

        // Accepted! Reset candidate buffer
        candidateCode = '';
        candidateHits = 0;

        // Debounce: ignore same code within 3 seconds
        if (scanCooldown && code === lastScannedCode) return;

        const isIsbn13 = code.length === 13;
        lastScannedCode = code;
        scanCooldown = true;
        setTimeout(() => { scanCooldown = false; }, 3000);

        // Vibrate for haptic feedback if available
        if (navigator.vibrate) navigator.vibrate(100);

        // Display result
        const displayIsbn = isIsbn13 ? formatIsbn13(code) : code;
        resultIsbn.textContent = displayIsbn;
        resultCard.classList.add('visible');

        //lookupBook(code); // API KEY NEEDED

        addToHistory(code);

        showToast('ISBN verified!');

        stopScanner();
    }

    function formatIsbn13(isbn) {
        // Format: 978-X-XXXX-XXXX-X (simplified grouping)
        return isbn.replace(/(\d{3})(\d{1})(\d{4})(\d{4})(\d{1})/, '$1-$2-$3-$4-$5');
    }

    async function lookupBook(isbn) {
        bookInfo.classList.remove('visible');
        bookLoading.classList.remove('hidden');

        try {
            const response = await fetch(`/api/book/${isbn}`);
            const data = await response.json();

            if (data.success && data.book) {
                displayBookInfo(data.book);
                updateHistoryWithBook(isbn, data.book);
            } else {
                bookLoading.classList.add('hidden');
            }
        } catch (err) {
            console.error('Book lookup failed:', err);
            bookLoading.classList.add('hidden');
        }
    }

    function displayBookInfo(book) {
        bookLoading.classList.add('hidden');

        const coverEl = document.getElementById('bookCover');
        if (book.cover) {
            coverEl.innerHTML = `<img src="${book.cover}" alt="Book cover">`;
        } else {
            coverEl.innerHTML = `<div class="no-cover">No cover</div>`;
        }

        document.getElementById('bookTitle').textContent = book.title || 'Unknown Title';
        document.getElementById('bookAuthor').textContent = book.authors || 'Unknown Author';

        const metaParts = [];
        if (book.publisher) metaParts.push(book.publisher);
        if (book.publish_date) metaParts.push(book.publish_date);
        if (book.pages) metaParts.push(`${book.pages} pages`);
        document.getElementById('bookMeta').textContent = metaParts.join(' · ') || '';

        bookInfo.classList.add('visible');
    }

    function addToHistory(isbn) {
        // duplicates
        if (scanHistory.some(h => h.isbn === isbn)) return;

        const entry = {
            isbn: isbn,
            time: new Date(),
            title: null,
            cover: null
        };
        scanHistory.unshift(entry);
        renderHistory();
    }

    function updateHistoryWithBook(isbn, book) {
        const entry = scanHistory.find(h => h.isbn === isbn);
        if (entry) {
            entry.title = book.title;
            entry.cover = book.cover;
            renderHistory();
        }
    }

    function renderHistory() {
        const list = document.getElementById('historyList');
        const header = document.getElementById('historyHeader');
        const empty = document.getElementById('emptyHistory');

        if (scanHistory.length === 0) {
            header.style.display = 'none';
            empty.style.display = 'block';
            return;
        }

        header.style.display = 'flex';
        empty.style.display = 'none';
        document.getElementById('historyCount').textContent = scanHistory.length;

        list.innerHTML = scanHistory.map((entry, i) => `
                <div class="history-item" onclick="showHistoryItem(${i})" style="animation-delay: ${i * 0.05}s">
                    <div class="history-thumb">
                        ${entry.cover
            ? `<img src="${entry.cover}" alt="Cover">`
            : `<div class="no-thumb"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" /></svg></div>`
        }
                    </div>
                    <div class="history-content">
                        <div class="history-isbn">${entry.isbn.length === 13 ? formatIsbn13(entry.isbn) : entry.isbn}</div>
                        ${entry.title ? `<div class="history-book-title">${entry.title}</div>` : ''}
                    </div>
                    <div class="history-time">${formatTime(entry.time)}</div>
                </div>
            `).join('');
    }

    function showHistoryItem(index) {
        const entry = scanHistory[index];
        resultIsbn.textContent = entry.isbn.length === 13 ? formatIsbn13(entry.isbn) : entry.isbn;
        lastScannedCode = entry.isbn;
        resultCard.classList.add('visible');

        if (entry.title) {
            // Re-lookup for full details
            lookupBook(entry.isbn);
        }

        // Scroll to result
        resultCard.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }

    function formatTime(date) {
        return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
    }

    function copyIsbn() {
        const isbn = lastScannedCode;
        if (!isbn) return;

        navigator.clipboard.writeText(isbn).then(() => {
            const btn = document.getElementById('copyBtn');
            btn.classList.add('copied');
            btn.querySelector('span').textContent = 'Copied!';
            setTimeout(() => {
                btn.classList.remove('copied');
                btn.querySelector('span').textContent = 'Copy ISBN';
            }, 2000);
        });
    }

    function searchGoogle() {
        if (!lastScannedCode) return;
        window.open(`https://www.google.com/search?q=ISBN+${lastScannedCode}`, '_blank');
    }

    function showToast(msg) {
        const toast = document.getElementById('toast');
        document.getElementById('toastMsg').textContent = msg;
        toast.classList.add('show');
        setTimeout(() => toast.classList.remove('show'), 2500);
    }

    function showError(msg) {
        errorBanner.textContent = msg;
        errorBanner.classList.add('visible');
    }
</script>
</body>
</html>
