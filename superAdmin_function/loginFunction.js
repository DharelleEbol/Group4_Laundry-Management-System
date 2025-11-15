document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('.login-form');
    const errorDiv = document.querySelector('.error');

    if (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value.trim();

            // Simple email validation
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                errorDiv.textContent = 'Please enter a valid email address.';
                return;
            }

            const formData = new FormData(form);

            fetch('api/auth.php?action=login', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    window.location.href = 'dashboard.php';
                } else {
                    errorDiv.textContent = data.message;
                }
            });
        });
    }
});
