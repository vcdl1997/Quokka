<?php 
    require_once 'view/_partials/header.php';

    spl_autoload_register(function($className) {
        include("model/" . $className . ".php");
    });
    
    $urlSplit = explode("/", $_GET['url']);
    $id = end($urlSplit);

    if( count($urlSplit) == 3 && !is_numeric($id) || 
        count($urlSplit) == 3 && $urlSplit[1] != "edit" || 
        count($urlSplit) == 2 && $urlSplit[1] == "edit"
    )
        header("Location: http://".$_SERVER['HTTP_HOST']."/quokka/users");

    $user = new User();
    $user = $user->findByID($id);
    $user = count($user) > 0 ? current($user) : [];
?>

<section style="display: flex; justify-content: center;">
    <div class="container-form">
        <div class="title" style="display: flex; align-items: center; justify-content: space-between;">
            Usuário
            <button class="btn-default" onclick="location.href='<?= $dots; ?>users'" title="Voltar para a tela de pesquisa de Usuários">
                <i class="fas fa-arrow-left" style="color: white;" title="Voltar para a tela de pesquisa de Usuários"></i> Voltar
            </button>
        </div><br>
        <form id="form-user">
            <div class="user-details">
                <input type="hidden" name="id" value="<?= array_key_exists("id", $user) ? $user['id'] : '' ?>">

                <div class="input-box">
                    <span class="details">Nome Completo: </span>
                    <input type="text" name="fullname" placeholder="Informe o nome completo" autocomplete="off" minlength="3" maxlength="100"
                        value="<?= array_key_exists("fullname", $user) ? $user['fullname'] : '' ?>" required>
                </div>
                <div class="input-box">
                    <span class="details">Usuário: </span>
                    <input type="text" name="username" placeholder="Informe o nome de usuário" autocomplete="off" minlength="3" maxlength="20"
                        value="<?= array_key_exists("username", $user) ? $user['username'] : '' ?>" required>
                </div>
                <div class="input-box">
                    <span class="details">Email: </span>
                    <input type="email" name="email_address" placeholder="Informe o e-mail" autocomplete="off" maxlength="160"
                        value="<?= array_key_exists("email_address", $user) ? $user['email_address'] : '' ?>">
                </div>
                <div class="input-box">
                    <span class="details">Telefone: </span>
                    <input type="text" name="home_phone" placeholder="Informe o telefone fixo" autocomplete="off" minlength="10" maxlength="14"
                        value="<?= array_key_exists("home_phone", $user) ? $user['home_phone'] : '' ?>">
                </div>
                <div class="input-box">
                    <span class="details">Celular: </span>
                    <input type="text" name="cell_phone" placeholder="Informe o celular" autocomplete="off" minlength="11" maxlength="15"
                        value="<?= array_key_exists("cell_phone", $user) ? $user['cell_phone'] : '' ?>" required>
                </div>
                <div class="input-box">
                    <span class="details">Senha: </span>
                    <input type="password" name="password" placeholder="Informe a senha" autocomplete="off" minlength="8" maxlength="30"
                        value="<?= array_key_exists("password", $user) ? str_repeat("*", (int)$user['password_extension']) : '' ?>" required>
                </div>
                <div class="input-box">
                    <span class="details">Confirme a Senha: </span>
                    <input type="password" name="password-confirm" placeholder="Confirme a senha" autocomplete="off" minlength="8" maxlength="30"
                        value="<?= array_key_exists("password", $user) ? str_repeat("*", (int)$user['password_extension']) : '' ?>" required>
                </div>
            </div>
            <div class="gender-details">
                <input type="radio" name="gender" id="dot-1" autocomplete="off" value="M" 
                    <?= array_key_exists("gender", $user) && $user['gender'] == "M" ? 'checked' : '' ?> required>
                <input type="radio" name="gender" id="dot-2" autocomplete="off" value="F" 
                    <?= array_key_exists("gender", $user) && $user['gender'] == "F" ? 'checked' : '' ?> required>
                <input type="radio" name="gender" id="dot-3" autocomplete="off" value="" 
                    <?= array_key_exists("gender", $user) && empty($user['gender']) || empty($user) ? 'checked' : '' ?> required>
                <span class="gender-title">Sexo: </span>
                <div class="category">
                    <label for="dot-1">
                        <span class="dot one"></span>
                        <span class="gender">Masculino</span>
                    </label>
                    <label for="dot-2">
                        <span class="dot two"></span>
                        <span class="gender">Feminino</span>
                    </label>
                    <label for="dot-3">
                        <span class="dot three"></span>
                        <span class="gender">Prefiro não dizer</span>
                    </label>
                </div>
            </div>
            <div class="button">
                <input type="submit" value="Salvar">
            </div>
        </form>
    </div>
</section>

<script src="<?= $dots ?>public/js/user.js"></script>
<?php require_once 'view/_partials/footer.php' ?>