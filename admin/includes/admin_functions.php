<!-- 添加一些用于验证用户权限的功能，例如检查用户是否为管理员  -->


function isAdmin($user_role)
{
    return $user_role === 'admin';
}

function checkAdmin()
{
    if (!isset($_SESSION['user_role']) || !isAdmin($_SESSION['user_role'])) {
        header("Location: ../index.php");
        exit();
    }
}
