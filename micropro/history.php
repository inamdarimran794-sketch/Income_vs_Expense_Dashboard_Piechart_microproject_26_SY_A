<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction History</title>
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
        .chart-wrap {
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 2rem;
            margin-bottom: 1rem;
        }
        .table-wrap {
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 1.5rem;
            margin-top: 1.5rem;
            overflow-x: auto;
        }
        .chart-wrap h2 {
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            opacity: 0.9;
            text-align: center;
        }
        .table-wrap h2 {
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 1rem;
            opacity: 0.9;
        }
        .chart-container {
            position: relative;
            max-width: 320px;
            margin: 0 auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 540px;
        }
        th, td {
            padding: 12px 14px;
            text-align: left;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }
        th {
            color: #bcd4ff;
            font-size: 0.9rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }
        td {
            color: #f2f4f8;
        }
        tbody tr:last-child td {
            border-bottom: none;
        }
        .type-badge {
            display: inline-block;
            padding: 6px 10px;
            border-radius: 999px;
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: capitalize;
        }
        .type-income {
            background: rgba(0, 200, 83, 0.16);
            color: #65f29a;
        }
        .type-expense {
            background: rgba(255, 82, 82, 0.16);
            color: #ff9d9d;
        }
        .amount-income {
            color: #65f29a;
            font-weight: 600;
        }
        .amount-expense {
            color: #ff9d9d;
            font-weight: 600;
        }
        .empty-state {
            text-align: center;
            color: rgba(232, 232, 232, 0.75);
            padding: 1rem 0 0.25rem;
        }
        .actions {
            display: flex;
            justify-content: center;
            margin-top: 1.5rem;
        }
        .back-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            padding: 12px 22px;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 600;
            color: #fff;
            background: linear-gradient(135deg, #1e88e5, #42a5f5);
            transition: all 0.2s ease;
        }
        .back-btn:hover {
            opacity: 0.95;
            transform: translateY(-1px);
        }
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
    </style>
</head>
<body>
    <div class="container">
        <h1>Transaction History</h1>

        <div class="chart-wrap">
            <h2>Income vs Expense Pie Chart</h2>
            <div class="chart-container">
                <canvas id="pieChart"></canvas>
            </div>
        </div>

        <div class="table-wrap">
            <h2>Transaction List</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Type</th>
                        <th>Amount</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody id="transactionTableBody">
                    <tr>
                        <td colspan="4" class="empty-state">Loading transactions...</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <p id="error" class="error" style="display:none;"></p>
        <p id="errorLink" class="error-link" style="display:none;"><a href="config/check_db.php">Test database connection →</a></p>

        <div class="actions">
            <a class="back-btn" href="index.php">Back</a>
        </div>
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

        function renderTransactions(items) {
            const body = document.getElementById('transactionTableBody');

            if (!Array.isArray(items) || items.length === 0) {
                body.innerHTML = '<tr><td colspan="4" class="empty-state">No transactions found.</td></tr>';
                return;
            }

            body.innerHTML = items.map(item => {
                const typeClass = item.type === 'income' ? 'type-income' : 'type-expense';
                const amountClass = item.type === 'income' ? 'amount-income' : 'amount-expense';
                const amountPrefix = item.type === 'income' ? '+ ' : '- ';

                return `
                    <tr>
                        <td>${item.id}</td>
                        <td><span class="type-badge ${typeClass}">${item.type}</span></td>
                        <td class="${amountClass}">${amountPrefix}${formatMoney(item.amount)}</td>
                        <td>${item.description}</td>
                    </tr>
                `;
            }).join('');
        }

        function updateChart(data) {
            if (data.error) {
                showError('Database error: ' + data.error);
                return;
            }

            if (chart) {
                chart.destroy();
            }

            chart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: ['Income', 'Expense'],
                    datasets: [{
                        data: [data.income, data.expense],
                        backgroundColor: ['#00c853', '#ff5252'],
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
                                label: function(item) {
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

        Promise.all([
            fetch('api/data.php').then(r => {
                if (!r.ok) {
                    throw new Error('Chart API returned ' + r.status);
                }
                return r.json();
            }),
            fetch('api/transactions.php').then(r => {
                if (!r.ok) {
                    throw new Error('Transaction API returned ' + r.status);
                }
                return r.json();
            })
        ])
            .then(([chartData, transactionData]) => {
                updateChart(chartData);

                if (transactionData.error) {
                    showError('Database error: ' + transactionData.error);
                    return;
                }

                renderTransactions(transactionData.transactions);
            })
            .catch(err => {
                showError('Could not load data. ' + (err.message || '') + ' Check PHP/MySQL and that setup.sql was run.');
            });
    </script>
</body>
</html>
