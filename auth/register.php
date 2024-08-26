<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/3.4.38/vue.cjs.js"></script>
    <title>Document</title>
</head>
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;
    }

    .container {
        background: #fff;
        padding: 2rem;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        width: 300px;
    }

    h1 {
        margin-bottom: 1rem;
        font-size: 24px;
        text-align: center;
    }

    form {
        display: flex;
        flex-direction: column;
    }

    label {
        margin-bottom: 0.5rem;
    }

    input {
        margin-bottom: 1rem;
        padding: 0.5rem;
        border: 1px solid #ddd;
        border-radius: 4px;
    }

    button {
        padding: 0.75rem;
        background-color: #007bff;
        border: none;
        color: #fff;
        border-radius: 4px;
        cursor: pointer;
    }

    button:hover {
        background-color: #0056b3;
    }

    .error {
        color: red;
        font-size: 0.875rem;
        margin-bottom: 1rem;
    }

    .message {
        font-size: 1rem;
        margin-top: 1rem;
    }
</style>

<body>
    <div class="container">
        <h1>Register</h1>
        <form id="registrationForm" action="./action/register_action.php" method="POST">
            <div>
                <label for="name">Name:</label>
                <input type="text" id="name" required>
                <div id="nameError" class="error"></div>
            </div>
            <div>
                <label for="email">Email:</label>
                <input type="email" id="email" required>
                <div id="emailError" class="error"></div>
            </div>
            <div>
                <label for="password">Password:</label>
                <input type="password" id="password" required>
                <div id="passwordError" class="error"></div>
            </div>
            <button type="submit">Register</button>
            <div id="message" class="message"></div>
        </form>
    </div>
</body>

</html>