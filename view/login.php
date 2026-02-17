<?php
// $title = 'Log in';
// include __DIR__ . '/../parts/top.php';
?>

<body>
    <div class="container-login">
        <div class="card-login">
            <h1 class="h1-login">Employee Login</h1>

            <form onsubmit="login(event)">
                <label class="label-login">Employee ID</label>
                <input class="input-login" type="text" id="id" required />

                <label class="label-login">Password</label>
                <input class="input-login" type="password" id="pass" required />

                <button class="btn-login" type="submit">Sign In</button>
            </form>

            <div class="error" id="msg"></div>
            <div class="footer">Secure internal banking system</div>
        </div>

        <script>
            function login(e) {
                e.preventDefault();
                const id = document.getElementById('id').value;
                const pass = document.getElementById('pass').value;
                if (id === "admin" && pass === "1234") {
                    document.getElementById('msg').style.color = "#22c55e";
                    document.getElementById('msg').textContent = "Login successful (demo).";
                } else {
                    document.getElementById('msg').style.color = "#ef4444";
                    document.getElementById('msg').textContent = "Invalid credentials.";
                }
            }
        </script>
    </div>
</body>

<?php
// include __DIR__ . '/../parts/bottom.php';
