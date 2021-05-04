<?php 
    require_once 'view/_partials/header.php';

    spl_autoload_register(function($className) {
        include("model/" . $className . ".php");
    });
    
    $urlSplit = explode("/", $_GET['url']);
    $id = end($urlSplit);

    // die(var_dump($urlSplit[1]));
    if( count($urlSplit) == 3 && !is_numeric($id) || 
        count($urlSplit) == 3 && $urlSplit[1] != "edit" || 
        count($urlSplit) == 2 && $urlSplit[1] == "edit"
    )
        header("Location: http://".$_SERVER['HTTP_HOST']."/quokka/customers");

    $customer = new Customer();
    $customer = $customer->findByID($id);
    $customer = count($customer) > 0 ? current($customer) : [];
?>

<section style="display: flex; justify-content: center;">
    <div class="container-form">
        <div class="title" style="display: flex; align-items: center; justify-content: space-between;">
            Cliente
            <button class="btn-default" onclick="location.href='<?= $dots; ?>customers'" title="Voltar para a tela de pesquisa de Clientes">
                <i class="fas fa-arrow-left" style="color: white;" title="Voltar para a tela de pesquisa de Clientes"></i> Voltar
            </button>
        </div><br>
        <form id="form-customer">
            <div class="user-details">
                <input type="hidden" name="id" value="<?= array_key_exists("id", $customer) ? $customer['id'] : '' ?>">
                <div class="input-box">
                    <span class="details">Nome Completo: </span>
                    <input type="text" name="fullname" placeholder="Informe o nome completo" autocomplete="off" minlength="3" maxlength="100"
                        value="<?= array_key_exists("fullname", $customer) ? $customer['fullname'] : '' ?>" autofocus required>  
                </div>
                <div class="input-box">
                    <span class="details">Apelido: </span>
                    <input type="text" name="surname" placeholder="Informe o apelido" autocomplete="off" minlength="3" maxlength="20"
                        value="<?= array_key_exists("surname", $customer) ? $customer['surname'] : '' ?>">
                </div>
                <div class="input-box">
                    <span class="details">Telefone: </span>
                    <input type="text" name="home_phone" placeholder="Informe o telefone fixo" autocomplete="off" minlength="10" maxlength="30"
                        value="<?= array_key_exists("home_phone", $customer) ? $customer['home_phone'] : '' ?>">
                </div>
                <div class="input-box">
                    <span class="details">Celular: </span>
                    <input type="text" name="cell_phone" placeholder="Informe o celular" autocomplete="off" minlength="10" maxlength="30"
                        value="<?= array_key_exists("cell_phone", $customer) ? $customer['cell_phone'] : '' ?>" required>
                </div>
                <div class="input-box">
                    <span class="details">Contato(s) para Recado: </span>
                    <textarea name="contact_for_message" placeholder="Informe o nome e o telefone do contato para recado" autocomplete="off" 
                        minlength="15" maxlength="200"
                        ><?= array_key_exists("contact_for_message", $customer) ? $customer['contact_for_message'] : '' ?></textarea>
                </div>
                <div class="input-box">
                    <span class="details">Email: </span>
                    <input type="email" name="email_address" placeholder="Informe o e-mail" autocomplete="off" maxlength="160"
                        value="<?= array_key_exists("email_address", $customer) ? $customer['email_address'] : '' ?>">
                </div>
                <div class="input-box">
                    <span class="details">Endereço Completo: </span>
                    <textarea name="full_address" placeholder="Informe o endereço completo" autocomplete="off" 
                        minlength="15" maxlength="300" required
                        ><?= array_key_exists("full_address", $customer) ? $customer['full_address'] : '' ?></textarea>
                </div>
                <div class="input-box">
                    <span class="details">Data de nascimento: </span>
                    <input type="date" name="birth_date" autocomplete="off"
                        value="<?= array_key_exists("birth_date", $customer) ? $customer['birth_date'] : '' ?>" required>
                </div>
            </div>
            <div class="button">
                <input type="submit" value="Salvar">
            </div>
        </form>
    </div>
</section>

<script src="<?= $dots ?>public/js/customer.js"></script>
<?php require_once 'view/_partials/footer.php' ?>