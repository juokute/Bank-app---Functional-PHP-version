<body>

    <div class="container-create">
        <h1 class="h1-create">Create Bank Account</h1>

        <form method="post" action="<?= URL ?>store">

            <?php
            $old = $_SESSION['old'] ?? [];
            unset($_SESSION['old']);
            ?>

            <label class="label-create">First Name</label>
            <input class="input-create" type="text" name="firstName" value="<?= $old['firstName'] ?? '' ?>" required />

            <label class="label-create">Last Name</label>
            <input class="input-create" type="text" name="lastName" value="<?= $old['lastName'] ?? '' ?>" required />

            <label class="label-create">Account Number</label>
            <input class="balance input-create" type="text" name="accountNumber" value="<?= getNewIban() ?>" readonly />

            <label class="label-create">Personal ID</label>
            <input class="input-create" type="text" name="personalId" value="<?= $old['personalId'] ?? '' ?>" required />

            <label class="label-create">Initial Balance</label>
            <input class="balance input-create" type="number" name="balance" value="0.00" readonly />

            <button class="btn-create" type="submit">Create Account</button>
        </form>


        <?php if (isset($_SESSION['error'])) : ?>
            <div class="flash error">
                <?= $_SESSION['error']['message'] ?>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['success'])) : ?>
            <div class="flash success">
                <?= $_SESSION['success']['message'] ?>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>




    </div>


    <script>
        document.querySelectorAll('.flash').forEach(flash => {
            setTimeout(() => {
                flash.style.opacity = '0';
                setTimeout(() => flash.remove(), 1000);
            }, 5000);
        });
    </script>
</body>