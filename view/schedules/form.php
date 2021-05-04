<?php  
    require_once 'view/_partials/header.php'; 

    spl_autoload_register(function($className) {
        include("model/" . $className . ".php");
    });

    $user = new Customer();
    $customer = new Customer();

    $optionsUser = $user->buildSelectOptions(['id', 'fullname']);
    $optionsCustomer = $customer->buildSelectOptions(['id', 'fullname']);
?>

<section style="display: flex; justify-content: center;">
    <div class="container-form">
        <div class="title" style="display: flex; align-items: center; justify-content: space-between;">
            Agendamento
            <button class="btn-default" onclick="location.href='<?= $dots; ?>schedules'" title="Voltar para a tela de Agendamentos">
                <i class="fas fa-arrow-left" style="color: white;" title="Voltar para a tela de Agendamentos"></i> Voltar
            </button>
        </div><br>
        <form id="form-scheduling">
            <div class="user-details">
                <input type="hidden" name="id">
                <div class="input-box">
                    <span class="details">Serviço: </span>
                    <select name="id_service" required>
                        <option value="">Selecione</option>
                        <option value="1">Venda</option>
                        <option value="2">Pagamento de Contas</option>
                    </select>
                </div>
                <div class="input-box">
                    <span class="details">Data do Serviço: </span>
                    <input type="date" name="realization_date" autocomplete="off" required>
                </div>
                <div class="input-box">
                    <span class="details">Valor: </span>
                    <input type="text" name="value" placeholder="Informe o valor" autocomplete="off">
                </div>
                <div class="input-box">
                    <span class="details">Cliente: </span>
                    <select name="id_customer" required>
                        <option value="">Selecione</option>
                        <?= $optionsUser ?>
                    </select>
                </div>
                <div class="input-box">
                    <span class="details">Funcionário: <small>Quem vai realizar o serviço</small></span>
                    <select name="id_user" required>
                        <option value="">Selecione</option>
                        <?= $optionsCustomer ?>
                    </select>
                </div>
                <div class="input-box">
                    <span class="details">Forma de Pagamento: </span>
                    <select name="id_form_payment" required>
                        <option value="">Selecione</option>
                        <option value="1">Dinheiro</option>
                        <option value="2">Cartão</option>
                        <option value="3">Cheque</option>
                    </select>
                </div>
                <div class="input-box">
                    <span class="details">Numero de Parcelas: </span>
                    <select name="plots" required>
                        <option value="">Selecione</option>
                        <option value="1">1 Parcela</option>
                        <option value="2">2 Parcelas</option>
                        <option value="3">3 Parcelas</option>
                        <option value="4">4 Parcelas</option>
                        <option value="5">5 Parcelas</option>
                        <option value="6">6 Parcelas</option>
                        <option value="7">7 Parcelas</option>
                        <option value="8">8 Parcelas</option>
                        <option value="9">9 Parcelas</option>
                        <option value="10">10 Parcelas</option>
                        <option value="11">11 Parcelas</option>
                        <option value="12">12 Parcelas</option>
                    </select>
                </div>
                <div class="input-box">
                    <span class="details">Vencimentos: </span>
                    <select name="maturities" required>
                        <option value="">Selecione</option>
                        <option value="15">Quinzenal</option>
                        <option value="30">Mensal</option>
                    </select>
                </div>
                <div class="input-box">
                </div>
                <div class="input-box" style="width: 98% !important;">
                    <span class="details">Descrição: </span>
                    <textarea name="description" required></textarea>
                </div>
            </div>
            <div class="button">
                <input type="submit" value="Salvar">
            </div>
        </form>
    </div>
</section>
<script src="<?= $dots ?>public/js/schedule.js"></script>
<?php require_once 'view/_partials/footer.php' ?>