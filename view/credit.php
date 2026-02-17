<?php
// $title = 'Credit';
// include __DIR__ . '/../parts/top.php';
?>

<body>
    <div class="container-credit">
        <h1 class="h1-credit">Add Funds (Credit Payout)</h1>

        <?php if ($client !== null) : ?>
            <div class="info">
                <p><strong>First Name:</strong> <?= $client['firstName'] ?? '' ?></p>
                <p><strong>Last Name:</strong> <?= $client['lastName'] ?? ''  ?></p>
                <p><strong>Current Balance:</strong> <?= $client['initialBalance'] ?? '' ?> Eur</p>
            </div>
        <?php else : ?>
            <p style="color:red;">Client not found.</p>
        <?php endif; ?>

        <form onsubmit="addFunds(event)">
            <label class="label-credit">Enter Amount</label>
            <input class="input-credit" type="number" id="amount" placeholder="Enter credit amount" required />
            <button class="btn-credit" type="submit">Add Funds</button>
        </form>

        <div class="success" id="msg"></div>
    </div>

    <script>
        function addFunds(e) {
            e.preventDefault();
            const amount = document.getElementById('amount').value;
            document.getElementById('msg').textContent = "✅ " + amount + " € successfully added (demo).";
        }
    </script>
</body>



<?php
// include __DIR__ . '/../parts/bottom.php';
