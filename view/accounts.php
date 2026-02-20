<?php
// $title = 'Accounts';
// include __DIR__ . '/../top.php';
?>


<body>
    <div class="container-accounts">

        <!-- 🔹 FLASH MESSAGES virš lentelės -->
        <?php if (!empty($_SESSION['success'])): ?>
            <div class="flash success" id="flash-message">
                <?= $_SESSION['success']['message'] ?>
            </div>
        <?php endif; ?>

        <div class="counting">
            <h1>Bank Accounts</h1>

            <div class="totalAccounts">
                <h3>Total Accounts</h3>
                <h2><?= count($clients) ?></h2>

            </div>

        </div>



        <table>
            <thead>
                <tr>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Account Number</th>
                    <th>Personal ID</th>
                    <th>Balance</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($clients as $client) : ?>
                    <tr id="client-<?= $client['id'] ?>">
                        <td><?= $client['firstName'] ?></td>
                        <td><?= $client['lastName'] ?></td>
                        <td><?= $client['accountNumber'] ?></td>
                        <td><?= $client['personalId'] ?></td>
                        <td><?= $client['balance'] ?> Eur</td>
                        </td>
                        <td class="list-actions" style="position: relative;">
                            <div class="links">
                                <a href="<?= URL ?>credit/<?= $client['id'] ?>" class="add">Credit</a>
                                <a href="<?= URL ?>debit/<?= $client['id'] ?>" class="withdraw">Debit</a>
                                <form method="POST" action="<?= URL ?>delete-account/<?= $client['id'] ?>" style="display:inline;">
                                    <button class="delete" onclick="return confirm('Delete this account?')">
                                        Delete
                                    </button>
                                </form>


                            </div>

                        </td>
                    </tr>

                <?php endforeach ?>

            </tbody>
        </table>

        <?php
        // Išvalome session tik po HTML render
        if (!empty($_SESSION['success'])) unset($_SESSION['success']);
        ?>
    </div>

    <script>
        const flash = document.getElementById('flash-message');
        if (flash) {
            setTimeout(() => {
                flash.style.opacity = '0'; // fade out
                setTimeout(() => flash.remove(), 1000);
            }, 5000);
        }
    </script>

</body>