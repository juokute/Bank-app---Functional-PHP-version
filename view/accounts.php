<?php
// $title = 'Accounts';
// include __DIR__ . '/../top.php';
?>


<body>
    <div class="container-accounts">
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
                    <tr>
                        <td><?= $client['firstName'] ?></td>
                        <td><?= $client['lastName'] ?></td>
                        <td><?= $client['accountNumber'] ?></td>
                        <td><?= $client['personalId'] ?></td>
                        <td><?= $client['initialBalance'] ?> Eur</p>
                        </td>
                        <td class="list-actions">
                            <div class="links">
                                <a href="<?= URL ?>credit/<?= $client['id'] ?>" class="add">Credit</a>
                                <a href="<?= URL ?>debit/<?= $client['id'] ?>" class="withdraw">Debit</a>
                                <button class="delete" onclick="deleteRow(this)">Delete</button>
                            </div>
                        </td>
                    </tr>

                <?php endforeach ?>

            </tbody>
        </table>
    </div>

    <script>
        function deleteRow(btn) {
            if (confirm("Delete this account?")) {
                btn.closest("tr").remove();
            }
        }
    </script>
</body>





<?php
// include __DIR__ . '/../parts/bottom.php';
