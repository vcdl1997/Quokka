<?php 
	require_once 'view/_partials/header.php';

	spl_autoload_register(function($className) {
        include("model/" . $className . ".php");
    });

    $customer = new Customer();
    $aniversariantes = $customer->fetchBirthdaysMonth(true)['total'];

	$schedulingWages = new SchedulingWages();
    $receivable = $schedulingWages->receivable();
	$payable = $schedulingWages->payable();
?>

<!-- <div class="card">
	<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.</p>
</div> -->
<br>
<div class="container-indicators">

	<div class="row-cards-dashboard">
		<div class="column-card-dashboard">
			<div class="card-dashboard count-birthdays">
				<h3>Aniversariantes no mês <i class="fas fa-birthday-cake"></i></h3>
				<h1 id="count-birthdays">
					<?= $aniversariantes ?>
				</h1>
			</div>
		</div>
	
		<div class="column-card-dashboard">
			<div class="card-dashboard amount-receivable">
				<h3>A Receber <i class="fas fa-hand-holding-usd"></i></h3>
				<h1 id="amount-receivable">
					R$ <?= $receivable ?>
				</h1>
			</div>
		</div>
	
		<div class="column-card-dashboard">
			<div class="card-dashboard amount-payable">
				<h3>A Pagar <i class="fas fa-file-invoice-dollar"></i></h3>
				<h1 id="amount-payable">
					R$ <?= $payable ?>
				</h1>
			</div>
		</div>
	</div>
	
</div>

<?php require_once 'view/_partials/footer.php' ?>