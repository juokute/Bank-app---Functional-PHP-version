<?php
// $title = 'Accounts';
// include __DIR__ . '/../top.php';
?>


<body>
    <div class="container-accounts">

        <!-- 🔹 FLASH MESSAGES virš lentelės -->
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
                                <form method="POST" action="<?= URL ?>delete-account/<?= $client['id'] ?>" class="delete-form" data-client-name="<?= $client['firstName'] ?> <?= $client['lastName'] ?>" style="display:inline;">
                                    <button type="button" class="delete btn-open-modal">Delete</button>
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
        if (!empty($_SESSION['error'])) unset($_SESSION['error']);
        ?>
    </div>

    <!-- 🔹 DELETE MODAL -->
    <div id="deleteModal" class="modal" style="display:none;">
        <div class="modal-content">
            <p id="modal-text"></p>
            <div class="modal-buttons">
                <button id="cancel-btn" class="btn-delete-modal">Cancel</button>
                <button id="confirm-btn" class="btn-delete-modal btn-danger">Delete</button>
            </div>
        </div>
    </div>

    <script>
        ['success', 'error'].forEach(type => {
            const flash = document.getElementById('flash-' + type);
            if (flash) {
                setTimeout(() => {
                    flash.style.opacity = '0'; // fade out
                    setTimeout(() => flash.remove(), 1000);
                }, 5000);
            }
        });

         // 🔹 Modal delete logic
        let currentForm = null;
        const modal = document.getElementById('deleteModal');
        const modalText = document.getElementById('modal-text');
        const cancelBtn = document.getElementById('cancel-btn');
        const confirmBtn = document.getElementById('confirm-btn');

        document.querySelectorAll('.btn-open-modal').forEach(button => {
            button.addEventListener('click', function() {
                currentForm = this.closest('form');
                const clientName = currentForm.dataset.clientName;
                modalText.textContent = `Are you sure you want to delete ${clientName}'s account?`;
                modal.style.display = 'flex';
            });
        });

        cancelBtn.addEventListener('click', () => {
            modal.style.display = 'none';
            currentForm = null;
        });

        confirmBtn.addEventListener('click', () => {
            if (currentForm) currentForm.submit();
        });

        // Close modal on click outside content
        window.addEventListener('click', e => {
            if (e.target == modal) {
                modal.style.display = 'none';
                currentForm = null;
            }
        });

    </script>

</body>