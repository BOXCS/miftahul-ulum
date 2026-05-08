<form method="POST" action="{{ route('register.superadmin') }}">
    @csrf
    <label>Username:</label>
    <input type="text" name="username" required>

    <label>Email:</label>
    <input type="email" name="email" required>

    <label>Password:</label>
    <input type="password" name="password" required>

    <button type="submit">Register Superadmin</button>
</form>
