<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Security Settings</title>
    <style>
        body, html {
            font-family: Arial, Helvetica, sans-serif;
            margin: 0;
            padding: 0;
            height: 100%;
            background-color: #f8f8f8;
            color: #333;
        }
        .container {
            display: flex;
            height: 100%;
        }
        .sidebar {
            width: 200px;
            background-color: #333;
            color: white;
            padding: 30px 20px;
            border-right: 1px solid #e0e0e0;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            overflow-y: auto;
        }
        .main-content {
            flex-grow: 1;
            padding: 30px;
            overflow-y: auto;
            background-color: #f8f8f8;
        }
        .profile-details {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding: 24px;
            margin-bottom: 30px;
        }
        h1 {
            font-size: 24px;
            font-weight: 600;
            margin: 0 0 8px 0;
        }
        h2 {
            font-size: 20px;
            font-weight: 500;
            margin: 0 0 24px 0;
            padding-bottom: 16px;
            border-bottom: 1px solid #e0e0e0;
        }
        h3 {
            font-size: 18px;
            font-weight: 500;
            margin: 0 0 16px 0;
        }
        h4 {
            font-size: 16px;
            font-weight: 500;
            margin: 16px 0 8px 0;
        }
        p {
            color: #666;
            font-size: 14px;
            margin: 0 0 16px 0;
        }
        .menu-item {
            padding: 8px 12px;
            margin-bottom: 4px;
            cursor: pointer;
            font-size: 14px;
            display: flex;
            align-items: center;
            border-radius: 4px;
        }
        .menu-item.active {
            background-color: #444;
            font-weight: 500;
            color: white;
        }
        .menu-item svg {
            margin-right: 10px;
        }
        .menu-item a {
            color: white;
            text-decoration: none;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 200;
            font-size: 14px;
        }
        .form-group input,
        .form-group select {
            width: 100%;
            padding: 8px;
            border: 1px solid #d0d0d0;
            border-radius: 4px;
            font-size: 14px;
            background-color: white;
            color: #333;
        }
        .form-group select {
            appearance: auto;
            -webkit-appearance: auto;
            -moz-appearance: auto;
            padding-right: 8px;
        }
        .save-button {
            background-color: #81589a;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }
        .edit-button {
            padding: 6px 12px;
            background-color: transparent;
            border: 1px solid #d0d0d0;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            color: #333;
        }
        .info-section {
            margin-top: 24px;
        }
        .activity-log {
            width: 100%;
            border-collapse: collapse;
            margin-top: 16px;
        }
        .activity-log th,
        .activity-log td {
            text-align: left;
            padding: 12px;
            border-bottom: 1px solid #e0e0e0;
        }
        .activity-log th {
            background-color: #f1f3f4;
            font-weight: 500;
        }
        .activity-log tr:last-child td {
            border-bottom: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <h1>Account</h1>
            <p>Manage your account info.</p>
            <div class="menu-item">
                <a href="updated user profile page SysDev.html">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M20 21V19C20 17.9391 19.5786 16.9217 18.8284 16.1716C18.0783 15.4214 17.0609 15 16 15H8C6.93913 15 5.92172 15.4214 5.17157 16.1716C4.42143 16.9217 4 17.9391 4 19V21" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M12 11C14.2091 11 16 9.20914 16 7C16 4.79086 14.2091 3 12 3C9.79086 3 8 4.79086 8 7C8 9.20914 9.79086 11 12 11Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Profile
            </a>
            </div>
            <div class="menu-item">
                <a href="residence page in user managment .html">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M3 9L12 2L21 9V20C21 20.5304 20.7893 21.0391 20.4142 21.4142C20.0391 21.7893 19.5304 22 19 22H5C4.46957 22 3.96086 21.7893 3.58579 21.4142C3.21071 21.0391 3 20.5304 3 20V9Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M9 22V12H15V22" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Residence
            </a>
            </div>
            <div class="menu-item active">
                <a href="security page Sys Dev.html">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M12 16V12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M12 8H12.01" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Security
            </a>
            </div>
        </div>
        <div class="main-content">
            <h2>Security Settings</h2>
            <div class="profile-details">
                <h3>Password Reset/Recovery</h3>
                <p>If you've forgotten your password, you can reset it here. For security reasons, we'll send a password reset link to your registered email address.</p>
                <form>
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <button type="submit" class="save-button">Send Reset Link</button>
                </form>
                <div class="info-section">
                    <h4>Two-Factor Authentication</h4>
                    <p>Enhance your account security by enabling two-factor authentication.</p>
                    <button class="edit-button">Enable 2FA</button>
                </div>
            </div>
            <div class="profile-details">
                <h3>User Activity Logs</h3>
                <p>Below is a record of recent account activity. If you notice any suspicious activity, please contact support immediately.</p>
                <table class="activity-log">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Action</th>
                            <th>IP Address</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>2024-08-30 14:23</td>
                            <td>Login successful</td>
                            <td>192.168.1.1</td>
                        </tr>
                        <tr>
                            <td>2024-08-29 09:45</td>
                            <td>Password changed</td>
                            <td>192.168.1.1</td>
                        </tr>
                        <tr>
                            <td>2024-08-28 16:30</td>
                            <td>Login successful</td>
                            <td>192.168.1.1</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>