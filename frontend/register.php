<?php require_once 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - Bus System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Register</div>
                    <div class="card-body">
                        <form id="registerForm">
                            <div class="mb-3">
                                <label>Name</label>
                                <input type="text" id="name" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label>Email</label>
                                <input type="email" id="email" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label>Password</label>
                                <input type="password" id="password" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Register</button>
                        </form>
                        <div class="mt-3 text-center">
                            <p>Already have an account? <a href="login.php">Login here</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const API_URL = "<?php echo API_URL; ?>";

        $('#registerForm').on('submit', function(e) {
            e.preventDefault();
            const data = {
                name: $('#name').val(),
                email: $('#email').val(),
                password: $('#password').val()
            };

            $.ajax({
                url: API_URL + '/auth/register',
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify(data),
                success: function(response) {
                    alert('Registration successful! Please login.');
                    window.location.href = 'login.php';
                },
                error: function(xhr) {
                    alert('Error: ' + (xhr.responseJSON?.error || 'Registration failed'));
                }
            });
        });
    </script>
</body>
</html>