<?php
// $title = 'Log in';
// include __DIR__ . '/../parts/top.php';
?>

<body>
    <div class="container-login">
        <div class="card-login">
            <h1 class="h1-login">Employee Login</h1>

            <form method="POST" action="<?= URL ?>login">
                <label class="label-login">Employee ID</label>
                <input class="input-login" type="text" name="id" required />

                <label class="label-login">Password</label>
                <input class="input-login" type="password" name="pass" required />

                <button class="btn-login" type="submit">Sign In</button>
            </form>

            <?php if (!empty($_SESSION['error'])): ?>
                <div class="error" id="loginError"><?= $_SESSION['error']['message'] ?></div>
                <?php unset($_SESSION['error']); ?>
                <script>
                    // Paslėpti po 5 sekundžių
                    setTimeout(() => {
                        const errorDiv = document.getElementById('loginError');
                        if (errorDiv) {
                            errorDiv.style.display = 'none';
                        }
                    }, 5000); // 5000 ms = 5 s
                </script>
            <?php endif; ?>

            <div class="error" id="msg"></div>
            <div class="footer">Secure internal banking system</div>
        </div>


    </div>
</body>

<?php
// echo password_hash("topsecret777", PASSWORD_ARGON2ID);
