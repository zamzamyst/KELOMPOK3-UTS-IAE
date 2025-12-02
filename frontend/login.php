<?php require_once 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Bus System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Login</div>
                    <div class="card-body">
                        <form id="loginForm">
                            <div class="mb-3">
                                <label>Email</label>
                                <input type="email" id="email" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label>Password</label>
                                <input type="password" id="password" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Login</button>
                        </form>
                        <div class="mt-3 text-center">
                            <p>Don't have an account? <a href="register.php">Register here</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const API_URL = "<?php echo API_URL; ?>";

        // Jika sudah login, langsung redirect ke index
        if (localStorage.getItem('token')) {
            window.location.href = 'index.php';
        }

        $('#loginForm').on('submit', function(e) {
            e.preventDefault();
            const data = {
                email: $('#email').val(),
                password: $('#password').val()
            };

            $.ajax({
                url: API_URL + '/auth/login',
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify(data),
                success: function(response) {
                    // Simpan token ke Local Storage browser
                    localStorage.setItem('token', response.token);
                    localStorage.setItem('user', JSON.stringify(response.user));
                    
                    alert('Login successful!');
                    window.location.href = 'index.php';
                },
                error: function(xhr) {
                    alert('Error: ' + (xhr.responseJSON?.error || 'Login failed'));
                }
            });
        });
    </script>
</body>
</html>