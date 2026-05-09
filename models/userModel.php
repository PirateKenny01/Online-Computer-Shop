<?php
    require_once('../config/db.php');

    function emailExists($email){
        $con = getConnection();
        $sql = "select id from users where email=? limit 1";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        $status = false;
        if(mysqli_num_rows($result) > 0){
            $status = true;
        }

        mysqli_stmt_close($stmt);
        mysqli_close($con);
        return $status;
    }

    function addUser($user){
        $con = getConnection();
        $sql = "insert into users(name, email, password_hash, role) values(?, ?, ?, ?)";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "ssss", $user['name'], $user['email'], $user['password_hash'], $user['role']);
        $status = mysqli_stmt_execute($stmt);

        mysqli_stmt_close($stmt);
        mysqli_close($con);
        return $status;
    }

    function getUserByEmail($email){
        $con = getConnection();
        $sql = "select id, name, email, password_hash, role from users where email=? limit 1";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        $user = null;
        if(mysqli_num_rows($result) == 1){
            $user = mysqli_fetch_assoc($result);
        }

        mysqli_stmt_close($stmt);
        mysqli_close($con);
        return $user;
    }

    function updateRememberToken($id, $token_hash){
        $con = getConnection();
        $sql = "update users set remember_token=? where id=?";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "si", $token_hash, $id);
        $status = mysqli_stmt_execute($stmt);

        mysqli_stmt_close($stmt);
        mysqli_close($con);
        return $status;
    }

    function getUserByRememberToken($token_hash){
        $con = getConnection();
        $sql = "select id, name, role from users where remember_token=? limit 1";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "s", $token_hash);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        $user = null;
        if(mysqli_num_rows($result) == 1){
            $user = mysqli_fetch_assoc($result);
        }

        mysqli_stmt_close($stmt);
        mysqli_close($con);
        return $user;
    }

    function clearRememberToken($id){
        $con = getConnection();
        $sql = "update users set remember_token=null where id=?";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id);
        $status = mysqli_stmt_execute($stmt);

        mysqli_stmt_close($stmt);
        mysqli_close($con);
        return $status;
    }
?>
