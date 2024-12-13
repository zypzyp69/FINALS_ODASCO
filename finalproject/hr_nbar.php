<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HR Dashboard</title>
    <style>
        .navbar {
            background-color: rgb(0, 87, 128);
            padding: 10px 20px;
            text-align: center;
        }

        .navbar h3 {
            margin: 0;
        }

        .navbar a {
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            margin: 0 10px;
            border-radius: 5px;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .navbar a:hover {
            background-color: white; 
            color: rgb(185, 31, 39); 
        }


        h1 {
            text-align: center;
            margin-top: 50px; 
        }
    </style>
</head>
<body>
    <div class="navbar">
        <h3>
            <a href="hr_dashboard.php">Home</a>
            <a href="view_users.php">View Users</a>
            <a href="applicants_status.php">Applicants Status</a>     
            <a href="logout.php">Logout</a>  
        </h3>    
    </div>


    <h1>Welcome to the HR Dashboard</h1>


</body>
</html>
