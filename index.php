<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Income vs Expense Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'DM Sans', sans-serif;
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
            min-height: 100vh;
            color: #e8e8e8;
            padding: 2rem;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
        }
        h1 {
            text-align: center;
            margin-bottom: 2rem;
            font-weight: 700;
            font-size: 1.75rem;
            letter-spacing: -0.02em;
        }
        .cards {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-bottom: 2rem;
        }
        .card {
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 1.25rem;
            text-align: center;
        }
        .card.income { border-left: 4px solid #00c853; }
        .card.expense { border-left: 4px solid #ff5252; }
        .card .label { font-size: 0.85rem; opacity: 0.8; text-transform: uppercase; letter-spacing: 0.05em; }
        .card .value { font-size: 1.75rem; font-weight: 700; margin-top: 0.25rem; }
        .card.income .value { color: #00c853; }
        .card.expense .value { color: #ff5252; }
        .chart-wrap {
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 2rem;
            margin-bottom: 1rem;
        }
        .chart-wrap h2 {
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            opacity: 0.9;
        }
        .chart-container {
            position: relative;
            max-width: 320px;
            margin: 0 auto;
        }
        .balance {
            text-align: center;
            margin-top: 1rem;
            font-size: 1.1rem;
        }
        .balance .net { font-weight: 700; }
        .balance .positive { color: #00c853; }
        .balance .negative { color: #ff5252; }
        .error {
            color: #ffebee;
            background: rgba(198, 40, 40, 0.3);
            border: 1px solid #ff5252;
            text-align: center;
            padding: 1rem;
            border-radius: 8px;
            margin-top: 1rem;
        }
        .error-link { text-align: center; margin-top: 0.5rem; }
        .error-link a { color: #81d4fa; }
        /* Enter Details form - similar to index1.html */
        .form-wrap {
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 1.5rem 2rem;
            margin-bottom: 2rem;
            max-width: 380px;
            margin-left: auto;
            margin-right: auto;
        }
        .form-wrap h2 {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 1.25rem;
            opacity: 0.9;
            text-align: center;
        }
        .input-group {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 10px;
            padding: 12px 14px;
            margin-bottom: 14px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .input-group span { font-size: 1.1rem; opacity: 0.9; }
        .input-group input {
            border: none;
            outline: none;
            flex: 1;
            font-size: 1rem;
            background: transparent;
            color: #e8e8e8;
        }
        .input-group input::placeholder { color: rgba(232, 232, 232, 0.5); }
        .input-group select {
            border: none;
            outline: none;
            flex: 1;
            font-size: 1rem;
            background: transparent;
            color: #e8e8e8;
            cursor: pointer;
        }
        .input-group select option { background: #1a1a2e; color: #e8e8e8; }
        .submit-btn {
            margin-top: 8px;
            width: 100%;
            padding: 12px;
            border-radius: 10px;
            border: none;
            font-size: 1rem;
            font-weight: 600;
            color: #fff;
            cursor: pointer;
            background: linear-gradient(135deg, #00c853, #00e676);
            transition: all 0.2s ease;
        }
        .submit-btn:hover {
            opacity: 0.95;
            transform: translateY(-1px);
        }
        .form-msg {
            margin-top: 12px;
            text-align: center;
            font-size: 0.9rem;
            display: none;
        }
        .form-msg.success { color: #00c853; display: block; }
        .form-msg.error { color: #ff5252; display: block; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Income vs Expense Dashboard</h1>

        <!-- Enter Details form: dropdown type + amount + description -->
        <div class="form-wrap">
            <h2>Enter Details</h2>
            <div class="input-group">
                <span id="typeIcon">💰</span>
                <select id="type">
                    <option value="income">Income</option>
                    <option value="expense">Expense</option>
                </select>
            </div>
            <div class="input-group">
                <span>₹</span>
                <input type="number" id="amount" placeholder="Enter Amount" min="0" step="0.01">
            </div>
            <div class="input-group">
                <input type="text" id="description" placeholder="Description (optional)" maxlength="255">
            </div>
            <button type="button" class="submit-btn" onclick="submitData()">Submit</button>
            <div class="form-msg" id="formMsg"></div>
        </div>

        <div class="cards">
            <div class="card income">
                <div class="label">Total Income</div>
                <div class="value" id="totalIncome">—</div>
            </div>
            <div class="card expense">
                <div class="label">Total Expense</div>
                <div class="value" id="totalExpense">—</div>
            </div>
        </div>

        <div class="chart-wrap">
            <h2>Income vs Expense (Pie Chart)</h2>
            <div class="chart-container">
                <canvas id="pieChart"></canvas>
            </div>
            <div class="balance" id="balanceWrap">
                <span class="label">Net: </span><span class="net" id="netBalance">—</span>
            </div>
        </div>
        <p id="error" class="error" style="display:none;"></p>
        <p id="errorLink" class="error-link" style="display:none;"><a href="check_db.php">Test database connection →</a></p>
    </div>
    <script>
        const ctx = document.getElementById('pieChart').getContext('2d');
        let chart = null;

        function formatMoney(n) {
            return new Intl.NumberFormat('en-IN', {
            style: 'currency',
            currency: 'INR'
            }).format(n);
        }

        function showError(msg) {
            const el = document.getElementById('error');
            el.style.display = 'block';
            el.textContent = msg;
            document.getElementById('errorLink').style.display = 'block';
        }

        function updateUI(data) {
            if (data.error) {
                showError('Database error: ' + data.error);
                return;
            }
            document.getElementById('totalIncome').textContent = formatMoney(data.income);
            document.getElementById('totalExpense').textContent = formatMoney(data.expense);

            const net = data.income - data.expense;
            const netEl = document.getElementById('netBalance');
            netEl.textContent = formatMoney(net);
            netEl.className = 'net ' + (net >= 0 ? 'positive' : 'negative');

            const labels = ['Income', 'Expense'];
            const values = [data.income, data.expense];
            const colors = ['#00c853', '#ff5252'];

            if (chart) chart.destroy();
            chart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: labels,
                    datasets: [{
                        data: values,
                        backgroundColor: colors,
                        borderColor: 'rgba(255,255,255,0.15)',
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: { color: '#e8e8e8', padding: 16 }
                        },
                        tooltip: {
                            callbacks: {
                                label: function (item) {
                                    const total = item.dataset.data.reduce((a, b) => a + b, 0);
                                    const pct = total ? ((item.raw / total) * 100).toFixed(1) : 0;
                                    return item.label + ': ' + formatMoney(item.raw) + ' (' + pct + '%)';
                                }
                            }
                        }
                    }
                }
            });
        }

        function showFormMsg(text, isError) {
            const el = document.getElementById('formMsg');
            el.textContent = text;
            el.className = 'form-msg ' + (isError ? 'error' : 'success');
        }

        function refreshDashboard() {
            fetch('api/data.php')
                .then(r => {
                    if (!r.ok) throw new Error('API returned ' + r.status);
                    return r.json();
                })
                .then(data => updateUI(data))
                .catch(() => {});
        }

        document.getElementById('type').addEventListener('change', function() {
            document.getElementById('typeIcon').textContent = this.value === 'income' ? '💰' : '💸';
        });

        function submitData() {
            const type = document.getElementById('type').value;
            const amountStr = document.getElementById('amount').value.trim();
            const description = document.getElementById('description').value.trim();

            if (amountStr === '') {
                showFormMsg('Please enter amount', true);
                return;
            }
            const amount = parseFloat(amountStr);
            if (isNaN(amount) || amount <= 0) {
                showFormMsg('Please enter a valid amount', true);
                return;
            }

            const payload = {
                type: type,
                amount: amount,
                description: description || 'Added from form'
            };

            fetch('api/save.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            })
                .then(r => r.json())
                .then(data => {
                    if (data.status === 'success') {
                        showFormMsg('✔ ' + (data.message || 'Data saved successfully'));
                        document.getElementById('amount').value = '';
                        document.getElementById('description').value = '';
                        refreshDashboard();
                    } else {
                        showFormMsg(data.message || 'Failed to save', true);
                    }
                })
                .catch(() => showFormMsg('Could not save. Check connection.', true));
        }

        fetch('api/data.php')
            .then(r => {
                if (!r.ok) throw new Error('API returned ' + r.status);
                return r.json();
            })
            .then(data => updateUI(data))
            .catch(err => {
                showError('Could not load data. ' + (err.message || '') + ' Check PHP/MySQL and that setup.sql was run.');
            });
    </script>
</body>
</html>