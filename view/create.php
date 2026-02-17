<?php
// $title = 'Create account';
// include __DIR__ . '/../parts/top.php';
?>

<body>
    <div class="container-create">
        <h1 class="h1-create">Create Bank Account</h1>

        <form onsubmit="createAccount(event)" method="post" action="<?= URL ?>store">
            <label class="label-create">First Name</label>
            <input class="input-create" type="text" name="firstName" required />

            <label class="label-create">Last Name</label>
            <input class="input-create" type="text" name="lastName" required />

            <label class="label-create">Account Number</label>
            <input class="input-create" type="text" name="accountNumber" required placeholder="LTxx xxxx xxxx xxxx xxxx" />

            <label class="label-create">Personal ID</label>
            <input class="input-create" type="text" name="personalId" required />

            <label class="label-create">Initial Balance</label>
            <input class="balance input-create" type="number" name="initialBalance" value="0.00" readonly />

            <button class="btn-create" type="submit">Create Account</button>
        </form>

        <div class="notice" id="msg"></div>
    </div>

    <script>
        function createAccount(e) {
            // e.preventDefault();
            document.getElementById('msg').textContent = "✅ Account successfully created (demo only).";
        }
    </script>
</body>



<?php
// include __DIR__ . '/../parts/bottom.php';
