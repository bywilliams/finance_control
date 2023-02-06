<?php
require_once("globals.php");
require_once("templates/header.php");
require_once("models/Message.php");

$message = new Message($BASE_URL);

$flashMessage = $message->getMessage();

if(!empty($flashMessage)){
    $message->clearMessage();
}

?>

<main>

    <div class="container login-container">
        <?php if (!empty($flashMessage["msg"])) : ?>
            <div class="container text-center <?=($flashMessage["type"])?> mb-5 p-2">
                <span id="msg-status"><?=$flashMessage["msg"]?></span>
            </div>
        <?php endif; ?>
        <div class="row">

            <!-- Login Form -->
            <div class="col-md-6 login-form-1">
                <h3>Login</h3>
                <form action="<?=$BASE_URL?>auth_process.php" method="POST">
                <input type="hidden" name="type" value="login">
                    <div class="form-group">
                        <input type="email" class="form-control" name="email" placeholder="Your Email *" value="" />
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" name="password" placeholder="Your Password *" value="" />
                    </div>
                    <div class="form-group">
                        <input type="submit" class="btnSubmit" value="Login" />
                    </div>
                </form>
                <div class="form-group">
                    <a href="#" class="btnForgetPwd">Esqueci o Password?</a>
                </div>

            </div>
            <!-- End Login Form -->

            <!-- Register Form -->
            <div class="col-md-6 login-form-2">
                <div class="login-logo">
                    <img src="https://image.ibb.co/n7oTvU/logo_white.png" alt="" />
                </div>
                <h3>Criar Conta</h3>
                <form action="<?= $BASE_URL ?>auth_process.php" method="POST">
                <input type="hidden" name="type" value="register">
                    <div class="form-group">
                        <input type="email" class="form-control" name="email" placeholder="Seu Email *" value="" />
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" name="name" id="" placeholder="Nome *" value="">
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" name="lastname" id="" placeholder="Sobrenome *" value="">
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" name="password" placeholder="Digite sua senha *" value="" />
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" name="confirmPassword" placeholder="Confirme sua senha *" value="" />
                    </div>
                    <div class="form-group">
                        <input type="submit" class="btnSubmit" value="Registrar" />
                    </div>
                </form>
            </div>
            <!-- End Register Form -->
        </div>
    </div>
</main>