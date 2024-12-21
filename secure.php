<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Admin Security Check for BLDEACET Central Library. Secure access for authorized personnel only.">
    <link rel="icon" href="bldea_logo.webp" type="image/x-icon">
    <title>Admin Security Check | BLDEACET Central Library</title>
    <link href="https://fonts.googleapis.com/css2?family=Arapey&family=Poppins:wght@400;500&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            color: #222831;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: radial-gradient(circle at center, #fbe7fd, #e5b7f1, #cf8de1, #b765d3, #9b4d96);
            background-size: cover;
            padding: 20px;
            overflow: hidden;
        }

        .modal {
            margin: auto;
            padding: 40px;
            background: rgba(255, 255, 255, 0.8);
            box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.1);
            border-radius: 16px;
            max-width: 500px;
            width: 100%;
        }

        /* Add some breathing space for small screens */
        @media (max-width: 768px) {
            body {
                padding: 10px;
            }

            .modal {
                padding: 20px;
            }
        }

        /* Form Section */
        .modal-form {
            flex: 1.2;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        h1 {
            font-family: 'Arapey', serif;
            font-size: 2.8em;
            font-weight: 400;
            margin-bottom: 20px;
            color: #000;
        }

        span {
            color: #9b4d96;
        }

        p {
            font-size: 1em;
            margin-bottom: 20px;
            color: #95122c;
            line-height: 1.6;
        }

        label {
            font-size: 1em;
            display: block;
            margin-bottom: 8px;
        }

        input {
            width: 100%;
            padding: 12px;
            border-radius: 8px;
            border: 1px solid #d1c4e9;
            background: rgba(255, 255, 255, 0.95);
            color: #222831;
            font-size: 1em;
            margin-bottom: 20px;
            outline: none;
            transition: box-shadow 0.3s ease, border 0.3s ease;
        }

        input::placeholder {
            color: #aaa;
        }

        input:disabled{
            background: rgba(255, 255, 255, 0.8);
            color: #f4f4f4;
        }

        button {
            background-color: #222831;
            color: #fff;
            padding: 12px 20px;
            border: none;
            border-radius: 8px;
            font-size: 1.1em;
            cursor: pointer;
            transition: transform 0.3s ease, background 0.3s ease;
        }

        button:hover {
            background-color: #9b4d96;
        }

        button:disabled{
            background: #dad9d9;
            color: #f4f4f4;
        }

        .error-message {
            color: #d32f2f;
            font-size: 0.95em;
            margin-top: 10px;
        }

        @media (max-width: 768px) {
            .modal {
                flex-direction: column;
                height: auto;
            }

            .modal-image {
                height: 200px;
            }

            .modal-form {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="modal" id="modalContainer">
        <!-- Form Section -->
        <div class="modal-form">
            <h1><b>//</b> Admin Security Check <span>&#10042;</span></h1>
            <p><strong>Note:</strong> This section is restricted to administrators. Unauthorized access is prohibited.</p>
            <form id="securityForm">
                <label for="securityKey">Enter Security Key:</label>
                <input type="password" id="securityKey" placeholder="Security Key" required>
                <button type="submit">Access Admin</button>
                <div id="error" class="error-message"></div>
            </form>
        </div>
    </div>
    <script>
        const modal = document.getElementById('modalContainer');
        const form = document.getElementById('securityForm');
        const errorDiv = document.getElementById('error');
        const securityKeyInput = document.getElementById('securityKey');
        const button = form.querySelector('button');
        
        let attemptCount = parseInt(localStorage.getItem('attemptCount')) || 0;
        let lockEndTime = parseInt(localStorage.getItem('lockEndTime')) || 0;
        
        // Check if the form should be locked on page load
        const now = Date.now();
        if (lockEndTime > now) {
            const remainingTime = Math.ceil((lockEndTime - now) / 1000);
            disableForm(remainingTime);
        } else {
            resetLock(); // Reset the lock if the time has expired
        }
        
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            
            // Send the security key to the backend for validation
            fetch('validateSecurityKey.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    securityKey: securityKeyInput.value
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    resetLock(); // Clear lock and attempts on successful login
                    window.location.href = "admin.php";
                } else {
                    attemptCount++;
                    localStorage.setItem('attemptCount', attemptCount);
        
                    errorDiv.textContent = "Incorrect security key. Please try again.";
                    modal.classList.add('shake');
        
                    setTimeout(() => {
                        modal.classList.remove('shake');
                    }, 400);
        
                    if (attemptCount >= 3) {
                        lockForm();
                    }
                }
            })
            .catch(error => {
                errorDiv.textContent = 'Error validating security key.';
            });
        });

        function lockForm() {
            const lockDuration = 60 * 1000; // 60 seconds in milliseconds
            const lockEndTime = Date.now() + lockDuration;
            localStorage.setItem('lockEndTime', lockEndTime);

            disableForm(lockDuration / 1000);

            const timer = setInterval(() => {
                const now = Date.now();
                const remainingTime = Math.ceil((lockEndTime - now) / 1000);

                if (remainingTime <= 0) {
                    clearInterval(timer);
                    enableForm();
                } else {
                    errorDiv.textContent = `Too many failed attempts. Try again later!`;
                }
            }, 1000);
        }

        function disableForm(seconds) {
            securityKeyInput.disabled = true;
            button.disabled = true;
            errorDiv.textContent = `Too many failed attempts. Try again later!`;
        }

        function enableForm() {
            resetLock();
            securityKeyInput.disabled = false;
            button.disabled = false;
            errorDiv.textContent = "";
        }

        function resetLock() {
            localStorage.removeItem('lockEndTime');
            localStorage.setItem('attemptCount', 0);
            attemptCount = 0;
        }
    </script>
</body>
</html>
