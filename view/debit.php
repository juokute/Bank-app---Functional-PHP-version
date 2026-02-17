<body>
    <div class="container-debit">
        <h1 class="h1-debit">Withdraw Funds (Loan Repayment / Interest Payment)</h1>

        <?php if ($client !== null) : ?>
            <div class="info-debit">
                <p><strong>First Name:</strong> <?= $client['firstName'] ?? '' ?></p>
                <p><strong>Last Name:</strong> <?= $client['lastName'] ?? ''  ?></p>
                <p><strong>Current Balance:</strong> <?= $client['initialBalance'] ?? '' ?> Eur</p>
            </div>
        <?php else : ?>
            <p style="color:red;">Client not found.</p>
        <?php endif; ?>

        <form onsubmit="withdrawFunds(event)">
            <label class="label-debit">Enter Amount</label>
            <input class="input-debit" type="number" id="amount" placeholder="Enter withdrawal amount" required />
            <button class="btn-debit" type="submit">Withdraw Funds</button>
        </form>

        <div class="success-debit" id="msg"></div>
    </div>

    <script>
        function withdrawFunds(e) {
            e.preventDefault();
            const amount = document.getElementById('amount').value;
            document.getElementById('msg').textContent = "✅ " + amount + " € successfully deducted (demo).";
        }
    </script>
</body>