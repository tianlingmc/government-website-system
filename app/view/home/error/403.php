<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - 无权访问</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        }
        .error-container {
            text-align: center;
            padding: 3rem;
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            max-width: 500px;
            width: 90%;
        }
        .error-icon {
            font-size: 5rem;
            color: #dc2626;
            margin-bottom: 1.5rem;
        }
        .error-code {
            font-size: 4rem;
            font-weight: 700;
            color: #dc2626;
            line-height: 1;
            margin-bottom: 0.5rem;
        }
        .error-title {
            font-size: 1.5rem;
            color: #374151;
            margin-bottom: 1rem;
        }
        .error-message {
            color: #6b7280;
            margin-bottom: 2rem;
        }
        .btn-home {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
            border: none;
            padding: 0.75rem 2rem;
            border-radius: 50px;
            color: white;
            font-weight: 500;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
        }
        .btn-home:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(220, 38, 38, 0.4);
            color: white;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-icon">
            <i class="fas fa-ban"></i>
        </div>
        <div class="error-code">403</div>
        <h1 class="error-title">无权访问</h1>
        <p class="error-message">
            抱歉，您没有权限访问此页面。<br>
            如需访问后台管理系统，请使用管理员账号登录。
        </p>
        <a href="/" class="btn-home">
            <i class="fas fa-home me-2"></i>返回首页
        </a>
    </div>
</body>
</html>
