<?php
$title = 'Credit';
include __DIR__ . '/../parts/top.php';
?>

<body>
    <div class="container-credit">
        <h1 class="h1-credit">Add Funds (Credit Payout)</h1>

        <div class="info">
            <p><strong>First Name:</strong> John</p>
            <p><strong>Last Name:</strong> Smith</p>
            <p><strong>Current Balance:</strong> €5,420.00</p>
        </div>

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
include __DIR__ . '/../parts/bottom.php';
