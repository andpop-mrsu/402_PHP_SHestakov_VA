<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">  {{-- ← Добавь эту строку --}}
    <title>Калькулятор — Laravel</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg: #0d0f1a;
            --surface: #161a2b;
            --surface-soft: #1e2238;
            --surface-strong: #202742;
            --surface-hover: #252a48;
            --field: #111427;
            --border: #2d325a;
            --border-strong: #3b4170;
            --text: #e5e7eb;
            --muted: #94a3b8;
            --accent: #7c3aed;
            --accent-soft: #a78bfa;
            --success-bg: #064e3b;
            --success-text: #34d399;
            --danger-bg: #4c0519;
            --danger-text: #f87171;
            --warning-bg: #422006;
            --warning-text: #fbbf24;
            --shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background:
                radial-gradient(circle at top left, rgba(124, 58, 237, 0.22), transparent 30%),
                radial-gradient(circle at top right, rgba(59, 130, 246, 0.16), transparent 24%),
                linear-gradient(180deg, #0b1020 0%, var(--bg) 100%);
            min-height: 100vh;
            padding: 20px;
            color: var(--text);
        }

        body::before {
            content: "";
            position: fixed;
            inset: 0;
            background-image:
                linear-gradient(rgba(148, 163, 184, 0.04) 1px, transparent 1px),
                linear-gradient(90deg, rgba(148, 163, 184, 0.04) 1px, transparent 1px);
            background-size: 32px 32px;
            pointer-events: none;
            opacity: 0.35;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
            background: var(--surface);
            border: 1px solid rgba(167, 139, 250, 0.12);
            border-radius: 25px;
            box-shadow: var(--shadow);
            padding: 50px;
            position: relative;
            overflow: hidden;
        }

        .container::before {
            content: "";
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at top, rgba(167, 139, 250, 0.12), transparent 35%);
            pointer-events: none;
        }

        header {
            text-align: center;
            margin-bottom: 40px;
            position: relative;
            z-index: 1;
        }

        header h1 {
            color: var(--accent-soft);
            font-size: 2.6rem;
            margin-bottom: 20px;
            letter-spacing: 0.02em;
        }

        .lead {
            max-width: 600px;
            margin: 0 auto;
            color: var(--muted);
            font-size: 1.05rem;
            line-height: 1.6;
        }

        main {
            position: relative;
            z-index: 1;
        }

        nav {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
            flex-wrap: wrap;
        }

        .nav-btn {
            background: var(--border);
            border: none;
            padding: 10px 20px;
            font-size: 1rem;
            border-radius: 10px;
            cursor: pointer;
            color: var(--text);
            transition: background 0.2s ease, transform 0.2s ease, box-shadow 0.2s ease;
        }

        .nav-btn:hover {
            background: var(--border-strong);
            transform: translateY(-1px);
        }

        .nav-btn.active {
            background: var(--accent);
            color: white;
            box-shadow: 0 10px 30px rgba(124, 58, 237, 0.35);
        }

        .game-box {
            background: var(--surface-soft);
            border: 1px solid rgba(167, 139, 250, 0.08);
            border-radius: 20px;
            padding: 50px 30px;
            margin: 30px 0;
            min-height: 220px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        #gameForm {
            width: min(100%, 520px);
        }

        .expression {
            font-size: 4rem;
            font-weight: 800;
            color: #c4b5fd;
            margin: 30px 0;
            letter-spacing: 3px;
            text-shadow: 0 0 18px rgba(167, 139, 250, 0.18);
        }

        .expression-label {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            order: -1;
            padding: 8px 14px;
            border: 1px solid rgba(167, 139, 250, 0.16);
            border-radius: 999px;
            background: rgba(167, 139, 250, 0.08);
            color: var(--accent-soft);
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }

        .form-group {
            margin: 20px 0;
            text-align: left;
            width: 100%;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: var(--muted);
        }

        input[type="text"],
        input[type="number"] {
            width: 100%;
            padding: 15px 18px;
            border: 2px solid var(--border);
            border-radius: 15px;
            font-size: 1.3rem;
            background: var(--field);
            color: #fff;
            text-align: center;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        input[type="text"]::placeholder,
        input[type="number"]::placeholder {
            color: #64748b;
        }

        input[type="text"]:focus,
        input[type="number"]:focus {
            border-color: var(--accent);
            box-shadow: 0 0 10px rgba(124, 58, 237, 0.4);
            outline: none;
        }

        button {
            background: var(--accent);
            color: white;
            border: none;
            padding: 18px 30px;
            font-size: 1.2rem;
            border-radius: 15px;
            cursor: pointer;
            transition: background 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease;
            width: 100%;
            font-weight: bold;
            margin-top: 15px;
        }

        button:hover {
            transform: translateY(-2px);
            background: #6d28d9;
            box-shadow: 0 12px 24px rgba(124, 58, 237, 0.3);
        }

        .result {
            margin: 25px 0;
            padding: 18px;
            border-radius: 15px;
            font-size: 1.3rem;
            font-weight: bold;
            text-align: center;
        }

        .result.correct {
            background: var(--success-bg);
            color: var(--success-text);
        }

        .result.incorrect {
            background: var(--danger-bg);
            color: var(--danger-text);
        }

        .result.warning {
            background: var(--warning-bg);
            color: var(--warning-text);
        }

        .result.error {
            background: var(--danger-bg);
            color: var(--danger-text);
        }

        .hidden { display: none; }

        #historySection {
            padding: 20px 0;
        }

        #historySection h2 {
            color: var(--accent-soft);
            font-size: 2rem;
            text-align: center;
            margin-bottom: 24px;
        }

        #historyContent {
            display: grid;
            gap: 14px;
        }

        .history-item {
            background: linear-gradient(180deg, var(--surface-strong), var(--surface-soft));
            border-radius: 16px;
            padding: 18px;
            border-left: 4px solid var(--accent);
            border: 1px solid rgba(167, 139, 250, 0.08);
            transition: transform 0.2s ease, border-color 0.2s ease, background 0.2s ease;
        }

        .history-item.correct { border-left-color: var(--success-text); }
        .history-item.incorrect { border-left-color: var(--danger-text); }

        .history-item:hover {
            transform: translateY(-2px);
            background: linear-gradient(180deg, var(--surface-hover), var(--surface-soft));
            border-color: rgba(167, 139, 250, 0.18);
        }

        .history-item h3 {
            margin-bottom: 5px;
            color: var(--text);
            font-size: 1.1rem;
        }

        .history-item p {
            margin: 3px 0;
            color: var(--muted);
            line-height: 1.5;
        }

        .empty-history {
            text-align: center;
            padding: 50px;
            color: var(--muted);
            font-style: italic;
            font-size: 1.2rem;
        }

        .loading {
            text-align: center;
            padding: 40px;
            color: var(--accent-soft);
            font-size: 1.2rem;
        }

        .error {
            text-align: center;
            padding: 18px;
            border-radius: 15px;
            background: var(--danger-bg);
            color: var(--danger-text);
            font-weight: bold;
        }

        footer {
            text-align: center;
            margin-top: 50px;
            color: var(--muted);
            padding-top: 20px;
            border-top: 1px solid var(--border);
            position: relative;
            z-index: 1;
        }

        strong {
            color: var(--text);
        }

        @media (max-width: 768px) {
            .container {
                padding: 28px 18px;
                border-radius: 20px;
            }

            header h1 {
                font-size: 2rem;
            }

            .lead {
                font-size: 0.96rem;
            }

            .expression {
                font-size: 2.6rem;
                letter-spacing: 1px;
                word-break: break-word;
                text-align: center;
            }

            .game-box {
                padding: 32px 18px;
            }

            nav {
                gap: 12px;
            }

            .nav-btn {
                width: 100%;
            }

            #historySection h2 {
                font-size: 1.6rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>Калькулятор</h1>
            <p class="lead">Решайте случайные выражения, отправляйте ответы и сразу просматривайте историю своих попыток в одном интерфейсе.</p>
            <nav>
                <button onclick="showGame()" class="nav-btn active" id="gameBtn">Играть</button>
                <button onclick="showHistory()" class="nav-btn" id="historyBtn">История</button>
            </nav>
        </header>

        <main id="content">
            <!-- Игра -->
            <div id="gameSection">
                <div class="game-box">
                    <div class="expression" id="expression">Загрузка...</div>
                    <div class="expression-label">Пример</div>
                    <div id="result" class="result hidden"></div>
                    <form id="gameForm">
                        <div class="form-group">
                            <label for="player_name">Ваше имя:</label>
                            <input type="text" id="player_name" name="player_name" value="Игрок" required>
                        </div>
                        <div class="form-group">
                            <label for="answer">Ваш ответ:</label>
                            <input type="number" id="answer" name="answer" required>
                        </div>
                        <button type="submit">Проверить ответ</button>
                    </form>
                </div>
            </div>

            <!-- История -->
            <div id="historySection" class="hidden">
                <h2>📊 История игр</h2>
                <div id="historyContent">
                    <p class="loading">Загрузка истории...</p>
                </div>
            </div>
        </main>

        <footer>
            <p>Лабораторная работа 4 &copy; {{ date('Y') }} Roman Tenishev</p>
        </footer>
    </div>

    <script>
        let currentExpression = null;

        // Генерация случайного выражения (клиентская)
        function generateExpression() {
            const operators = ['+', '-', '*'];
            let parts = [];
            
            for (let i = 0; i < 4; i++) {
                const operand = Math.floor(Math.random() * 50) + 1;
                parts.push(operand);
                
                if (i < 3) {
                    const operatorIndex = Math.floor(Math.random() * 3);
                    parts.push(operators[operatorIndex]);
                }
            }
            
            return parts.join('');
        }

        // Показать секцию игры
        function showGame() {
            document.getElementById('gameSection').classList.remove('hidden');
            document.getElementById('historySection').classList.add('hidden');
            document.getElementById('gameBtn').classList.add('active');
            document.getElementById('historyBtn').classList.remove('active');
            
            if (!currentExpression) {
                loadNewExpression();
            }
        }

        // Показать секцию истории
        function showHistory() {
            document.getElementById('gameSection').classList.add('hidden');
            document.getElementById('historySection').classList.remove('hidden');
            document.getElementById('gameBtn').classList.remove('active');
            document.getElementById('historyBtn').classList.add('active');
            
            loadHistory();
        }

        // Загрузить новое выражение
        function loadNewExpression() {
            currentExpression = generateExpression();
            document.getElementById('expression').textContent = currentExpression;
            document.getElementById('answer').value = '';
            document.getElementById('result').classList.add('hidden');
        }

        // Отправить ответ на сервер
        document.getElementById('gameForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const playerName = document.getElementById('player_name').value.trim() || 'Игрок';
            const answer = parseInt(document.getElementById('answer').value);
            
            if (isNaN(answer)) {
                showError('Введите корректное число!');
                return;
            }
            
            if (!currentExpression) {
                showError('Ошибка: выражение не загружено');
                return;
            }
            
            try {
                const response = await fetch('/api/games', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        player_name: playerName,
                        expression: currentExpression,
                        answer: answer
                    })
                });
                
                const data = await response.json();
                
                if (response.ok) {
                    const resultDiv = document.getElementById('result');
                    resultDiv.textContent = data.is_correct 
                        ? '✅ Правильно!' 
                        : `❌ Неправильно! Правильный ответ: ${data.correct_answer}`;
                    resultDiv.className = data.is_correct ? 'result correct' : 'result incorrect';
                    resultDiv.classList.remove('hidden');
                    
                    setTimeout(() => {
                        loadNewExpression();
                    }, 1500);
                } else {
                    showError('Ошибка: ' + (data.error || 'Неизвестная ошибка'));
                }
            } catch (error) {
                showError('Ошибка подключения к серверу: ' + error.message);
            }
        });

        // Загрузить историю игр
        async function loadHistory() {
            const historyContent = document.getElementById('historyContent');
            historyContent.innerHTML = '<p class="loading">Загрузка истории...</p>';
            
            try {
                const response = await fetch('/api/games');
                const games = await response.json();
                
                if (response.ok) {
                    if (games.length === 0) {
                        historyContent.innerHTML = '<p class="empty-history">Пока нет сыгранных игр</p>';
                    } else {
                        let html = '';
                        games.forEach(game => {
                            const statusClass = game.is_correct ? 'correct' : 'incorrect';
                            const statusText = game.is_correct ? '✅ Правильно' : '❌ Неправильно';
                            
                            html += `
                                <div class="history-item ${statusClass}">
                                    <h3>${game.player_name}</h3>
                                    <p><strong>Выражение:</strong> ${game.expression}</p>
                                    <p><strong>Ваш ответ:</strong> ${game.player_answer || '—'}</p>
                                    <p><strong>Правильный ответ:</strong> ${game.correct_answer || '—'}</p>
                                    <p><strong>Результат:</strong> ${statusText}</p>
                                    <p><strong>Дата:</strong> ${new Date(game.created_at).toLocaleString()}</p>
                                </div>
                            `;
                        });
                        historyContent.innerHTML = html;
                    }
                } else {
                    historyContent.innerHTML = '<p class="error">Ошибка при загрузке истории</p>';
                }
            } catch (error) {
                historyContent.innerHTML = `<p class="error">Ошибка подключения: ${error.message}</p>`;
            }
        }

        // Показать ошибку
        function showError(message) {
            const resultDiv = document.getElementById('result');
            resultDiv.textContent = message;
            resultDiv.className = 'result error';
            resultDiv.classList.remove('hidden');
        }

        // Инициализация
        document.addEventListener('DOMContentLoaded', function() {
            loadNewExpression();
        });
    </script>
</body>
</html>
