<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MoneyTree Bank - <?= $title ?></title>
    <link rel="stylesheet" href="<?= URL ?>style.css">
    <script src="<?= URL ?>app.js" defer></script>
</head>

<body>

    <div class="container">

        <div class="nav">
            <div class="logo">MoneyTree Bank</div>

            <div class="menu">
                <a href="<?= URL ?>">Home</a>

                <?php if (auth()): ?>
                    <a href="<?= URL ?>accounts">Accounts</a>
                    <a href="<?= URL ?>create">Create Account</a>
                    <?php if (isset($_SESSION['employee'])): ?>
                        <a href="#" id="logoutBtn">Log out</a>
                    <?php endif; ?>
                <?php else: ?>
                    <a href="<?= URL ?>login">Log in</a>
                <?php endif; ?>

            </div>
        </div>

        <div id="logoutModal" class="modal" style="display:none;">
            <div class="modal-content">
                <p>Are you sure you want to log out?</p>
                <div class="modal-buttons">
                    <button id="cancelLogout" class="btn-delete-modal">Cancel</button>
                    <button id="confirmLogout" class="btn-delete-modal btn-danger">Log out</button>
                </div>
            </div>
        </div>

        <script>
            const URL = "<?= URL ?>";
        </script>

        <script>
            const logoutBtn = document.getElementById("logoutBtn");
            const logoutModal = document.getElementById("logoutModal");
            const cancelLogout = document.getElementById("cancelLogout");
            const confirmLogout = document.getElementById("confirmLogout");

            if (logoutBtn) {
                logoutBtn.addEventListener("click", e => {
                    e.preventDefault();
                    logoutModal.style.display = "flex";
                });
            }

            cancelLogout?.addEventListener("click", () => {
                logoutModal.style.display = "none";
            });

            confirmLogout?.addEventListener("click", () => {
                window.location.href = URL + "logout";
            });

            window.addEventListener("click", e => {
                if (e.target === logoutModal) {
                    logoutModal.style.display = "none";
                }
            });
        </script>