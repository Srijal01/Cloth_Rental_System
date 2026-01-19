<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page Not Found | Cloth Rental System</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .error-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px;
        }
        
        .error-content {
            text-align: center;
            background: white;
            padding: 60px 40px;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            max-width: 600px;
            animation: fadeInUp 0.8s ease-out;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .error-code {
            font-size: 120px;
            font-weight: 800;
            color: #667eea;
            line-height: 1;
            margin-bottom: 20px;
            font-family: 'Playfair Display', serif;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
        }
        
        .error-title {
            font-size: 32px;
            font-weight: 700;
            color: #2d3436;
            margin-bottom: 16px;
            font-family: 'Playfair Display', serif;
        }
        
        .error-message {
            font-size: 16px;
            color: #636e72;
            margin-bottom: 32px;
            line-height: 1.6;
        }
        
        .error-icon {
            font-size: 80px;
            margin-bottom: 20px;
            animation: float 3s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-15px);
            }
        }
        
        .btn-home {
            display: inline-block;
            padding: 14px 36px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 30px;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }
        
        .btn-home:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 25px rgba(102, 126, 234, 0.6);
        }
        
        .helpful-links {
            margin-top: 40px;
            padding-top: 30px;
            border-top: 2px solid #f0f0f0;
        }
        
        .helpful-links h3 {
            font-size: 18px;
            color: #2d3436;
            margin-bottom: 16px;
            font-weight: 600;
        }
        
        .helpful-links ul {
            list-style: none;
            padding: 0;
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
        }
        
        .helpful-links a {
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .helpful-links a:hover {
            color: #764ba2;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-content">
            <div class="error-icon">👗</div>
            <h1 class="error-code">404</h1>
            <h2 class="error-title">Oops! Page Not Found</h2>
            <p class="error-message">
                The page you're looking for seems to have wandered off. 
                Maybe it's trying on a new outfit? Let's get you back to browsing our beautiful collection!
            </p>
            <a href="index.php" class="btn-home">🏠 Back to Homepage</a>
            
            <div class="helpful-links">
                <h3>Quick Links</h3>
                <ul>
                    <li><a href="index.php">Browse Products</a></li>
                    <li><a href="auth/login.php">Login</a></li>
                    <li><a href="auth/register.php">Register</a></li>
                    <li><a href="orders.php">My Orders</a></li>
                </ul>
            </div>
        </div>
    </div>
</body>
</html>
