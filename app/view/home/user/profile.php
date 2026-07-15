<?php include APP_PATH . 'view/home/public/header.php'; ?>

<div class="container py-5">
    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">个人中心</h5>
                </div>
                <div class="list-group list-group-flush">
                    <a href="/user/profile" class="list-group-item list-group-item-action active">基本信息</a>
                    <a href="/user/password" class="list-group-item list-group-item-action">修改密码</a>
                    <a href="/logout" class="list-group-item list-group-item-action text-danger">退出登录</a>
                </div>
            </div>
        </div>
        
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">基本信息</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td width="120">用户名</td>
                            <td><?php echo htmlspecialchars($user['username']); ?></td>
                        </tr>
                        <tr>
                            <td>邮箱</td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                        </tr>
                        <tr>
                            <td>注册时间</td>
                            <td><?php echo date('Y-m-d H:i', strtotime($user['create_time'])); ?></td>
                        </tr>
                        <tr>
                            <td>最后登录</td>
                            <td><?php echo $user['last_login_time'] ? date('Y-m-d H:i', strtotime($user['last_login_time'])) : '从未登录'; ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include APP_PATH . 'view/home/public/footer.php'; ?>
