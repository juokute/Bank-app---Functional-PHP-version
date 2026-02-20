<body>
    <div class="container-debit">
        <h1 class="h1-debit">Withdraw Funds (Loan Repayment / Interest Payment)</h1>

        <!-- 🔹 FLASH MESSAGES -->
        <?php if (!empty($_SESSION['success'])): ?>
            <div class="flash success" id="flash-success">
                <?= $_SESSION['success']['message'] ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($_SESSION['error'])): ?>
            <div class="flash error" id="flash-error">
                <?= $_SESSION['error']['message'] ?>
            </div>
        <?php endif; ?>


        <?php if ($client !== null) : ?>
            <div class="info-debit">
                <p><strong>First Name:</strong> <?= $client['firstName'] ?? '' ?></p>
                <p><strong>Last Name:</strong> <?= $client['lastName'] ?? ''  ?></p>
                <p><strong>Current Balance:</strong> <?= $client['balance'] ?? '' ?> Eur</p>
            </div>
        <?php else : ?>
            <p style="color:red;">Client not found.</p>
        <?php endif; ?>

        <form method="POST" action="<?= URL ?>withdraw-funds/<?= $client['id'] ?>">
            <label class="label-debit">Enter Amount</label>
            <input class="input-debit" type="number" step="0.01" name="debitAmount" id="amount" placeholder="Enter withdrawal amount" required />
            <button class="btn-debit" type="submit">Withdraw Funds</button>
        </form>

        <div class="success-debit-credit" id="msg"></div>
    </div>

    <script>
        // Fade out success message
        const flashSuccess = document.getElementById('flash-success');
        if (flashSuccess) {
            setTimeout(() => {
                flashSuccess.style.opacity = '0';
                setTimeout(() => flashSuccess.remove(), 1000);
            }, 5000);
        }

        // Fade out error message
        const flashError = document.getElementById('flash-error');
        if (flashError) {
            setTimeout(() => {
                flashError.style.opacity = '0';
                setTimeout(() => flashError.remove(), 1000);
            }, 5000);
        }

         // Front-end validacija: negalima įvesti minusinės sumos
        const debitForm = document.getElementById('debitForm');
        if (debitForm) {
            debitForm.addEventListener('submit', function(e) {
                const amountInput = document.getElementById('amount');
                if (parseFloat(amountInput.value) <= 0) {
                    alert('Please enter a valid amount greater than 0.');
                    e.preventDefault();
                }
            });
        }
    </script>

    <?php
    // Išvalome session žinutes po rodymo
    if (!empty($_SESSION['success'])) unset($_SESSION['success']);
    if (!empty($_SESSION['error'])) unset($_SESSION['error']);
    ?>
</body>