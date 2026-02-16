<?php
$title = 'Debit';
include __DIR__ . '/../parts/top.php';
?>

<body>
    <div class="container-debit">
        <h1 class="h1-debit">Withdraw Funds (Loan Repayment / Interest Payment)</h1>

        <div class="info-debit">
            <p><strong>First Name:</strong> John</p>
            <p><strong>Last Name:</strong> Smith</p>
            <p><strong>Current Balance:</strong> €5,420.00</p>
        </div>

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


<?php
include __DIR__ . '/../parts/bottom.php';
