<?php require_once 'view/_partials/header.php' ?>

<section style="display: flex; justify-content: center;">
    <div class="container-form">
        <div class="title" style="display: flex; align-items: center; justify-content: space-between;">
            Clientes 
            <button class="btn-default" onclick="location.href='<?= $dots; ?>customers/new'" title="Cadastrar Cliente">
                <i class="fas fa-plus" style="color: white;" title="Cadastrar Cliente"></i> Novo
            </button>
        </div>
        <br>
        <table id="customer-list" class="hover" style="width:100%">
            <thead>
                <tr>
                    <th class="text-left">Nome</th>
                    <th class="text-center">Apelido</th>
                    <th class="text-left">Celular</th>
                    <th class="text-left">E-mail</th>
                    <th lass="text-center">Ações</th>
                </tr>
            </thead>
        </table>		
    </div>
</section>

<script src="<?= $dots ?>public/js/customer.js"></script>
<?php require_once 'view/_partials/footer.php' ?>