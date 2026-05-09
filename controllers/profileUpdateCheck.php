<?php
    session_start();
    require_once('../models/userModel.php');

    if(!isset($_SESSION['user_id'])){
        header('location: ../views/login.php');
        exit();
    }

    if(isset($_POST['profile_update_submit'])){
        $id = $_SESSION['user_id'];
        $name = trim($_REQUEST['name']);
        $email = trim($_REQUEST['email']);
        $errors = [];

        if($name == "" || $email == ""){
            $errors['form'] = "null name/email!";
        }

        if($email != "" && !filter_var($email, FILTER_VALIDATE_EMAIL)){
            $errors['email'] = "invalid email format!";
        }

        if(emailExistsForOther($email, $id)){
            $errors['email_exists'] = "email already used by another user!";
        }

        $profile_picture = "";
        $currentUser = getUserById($id);

        if(isset($_FILES['profile_picture']) && $_FILES['profile_picture']['name'] != ""){
            if($_FILES['profile_picture']['error'] != 0){
                $errors['picture'] = "image upload failed! error code: ".$_FILES['profile_picture']['error'];
            }else{
                $tmp = $_FILES['profile_picture']['tmp_name'];
                $size = $_FILES['profile_picture']['size'];

                if($size > 2*1024*1024){
                    $errors['picture'] = "image size must be <= 2MB!";
                }else{
                    $mime = mime_content_type($tmp);
                    $allowed = [
                        'image/jpeg' => 'jpg',
                        'image/jpg' => 'jpg',
                        'image/png' => 'png'
                    ];

                    if(!isset($allowed[$mime])){
                        $errors['picture'] = "only jpg/png allowed!";
                    }else{
                        $newName = "profile_".$id."_".time().".".$allowed[$mime];

                        $uploadDirFs = __DIR__.'/../public/uploads/';
                        $uploadPathFs = $uploadDirFs.$newName;

                        if(!is_dir($uploadDirFs)){
                            mkdir($uploadDirFs, 0777, true);
                        }

                        if(!is_writable($uploadDirFs)){
                            $errors['picture'] = "upload folder is not writable!";
                        }else{
                            if(move_uploaded_file($tmp, $uploadPathFs)){
                                $profile_picture = "public/uploads/".$newName;
                            }else{
                                $errors['picture'] = "image upload failed!";
                            }
                        }
                    }
                }
            }
        }

        if(count($errors) > 0){
            $_SESSION['profile_errors'] = $errors;
            $_SESSION['profile_old'] = ['name'=>$name, 'email'=>$email];
            header('location: ../views/profile.php');
            exit();
        }else{
            $user = [
                'id' => $id,
                'name' => $name,
                'email' => $email,
                'profile_picture' => $profile_picture
            ];

            if(updateUserProfile($user)){
                if($profile_picture != "" && $currentUser && $currentUser['profile_picture'] != ""){
                    $oldPath = __DIR__.'/../'.$currentUser['profile_picture'];
                    if(file_exists($oldPath)){
                        unlink($oldPath);
                    }
                }

                $_SESSION['name'] = $name;
                $_SESSION['profile_success'] = "profile updated successfully.";
            }else{
                $_SESSION['profile_errors'] = ['db' => 'profile update failed!'];
                $_SESSION['profile_old'] = ['name'=>$name, 'email'=>$email];
            }

            header('location: ../views/profile.php');
            exit();
        }
    }else{
        echo "invalid request!";
    }
?>
