<?php
$title = 'Accounts';
include __DIR__ . '/../parts/top.php';
?>

<section>

    <body>
        <div class="container-accounts">
            <h1>Bank Accounts</h1>
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
                    <tr>
                        <td>John</td>
                        <td>Smith</td>
                        <td>LT12 7300 0100 1234 5678</td>
                        <td>39001010001</td>
                        <td>€5,420.00</td>
                        <td class="list-actions">
                            <div class="links">
                                <a href="<?= URL ?>credit" class="add">Credit</a>
                                <a href="<?= URL ?>debit" class="withdraw">Debit</a>
                                <button class="delete" onclick="deleteRow(this)">Delete</button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>Emma</td>
                        <td>Johnson</td>
                        <td>LT98 7300 0100 9876 5432</td>
                        <td>48505050005</td>
                        <td>€12,150.50</td>
                        <td class="list-actions">
                            <div class="links">
                                <a href="<?= URL ?>credit" class="add">Credit</a>
                                <a href="<?= URL ?>debit" class="withdraw">Debit</a>
                                <button class="delete" onclick="deleteRow(this)">Delete</button>
                            </div>
                        </td>
                    </tr>
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
</section>





<?php
include __DIR__ . '/../parts/bottom.php';
