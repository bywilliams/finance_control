<?php 

require_once("templates/header_iframe.php"); 
require_once("dao/FinancialMovimentDAO.php");

$financialMovimentDao = new FinancialMovimentDAO($conn, $BASE_URL);

$categorys = $financialMovimentDao->getAllCategorys();

// Traz as última movimentações do usuário
$latestFinancialMoviments = $financialMovimentDao->getLatestFinancialMoviment($userData->id);

// Traz total de entradas do usuário
$totalEntry = $financialMovimentDao->getAllCashInflow($userData->id);

// Traz total de saídas do usuário
$totalCashOutflow = $financialMovimentDao->getAllCashOutflow($userData->id);

// Traz o balanço entre entradas e saídas do usuário
$total_balance = $financialMovimentDao->getTotalBalance($userData->id);

$totalEntry <= "0,00" ? $total_balance = -(float)$totalCashOutflow : $totalEntry;

//$total_balance = number_format((float)$total_balance, 2, ',', '.');

$balance_color_text = "";
$total_balance > 0 ? $balance_color_text = "text-success" : $balance_color_text = "text-danger"; 


?>

<body id="iframe-body">


    <div class="container-fluid">

        <div class="card-div mb-3 my-3 text-center">
            <div class="row">
                <div class="col-md-3">
                    <div class="card mb-3 shadow-sm">
                        <div class="card-header">
                            <h4 class="my-0 font-weight-normal">Receita Mensal</h4>
                        </div>
                        <div class="card-body">
                            <h1 class="card-title pricing-card-title text-success">+ R$ <?= $totalEntry ?> </h1>
                            <small class="text-muted"><strong>Menor receita</strong> <br> Venda de placa: R$ 50,00 <br>
                                <strong>Maior receita</strong> <br> Salario: R$ 1.200,00
                            </small>


                            <!-- <button type="button" class="btn btn-lg btn-block btn-outline-primary">Sign up for free</button> -->
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card mb-3 shadow-sm">
                        <div class="card-header">
                            <h4 class="my-0 font-weight-normal">Despesa Mensal</h4>
                        </div>
                        <div class="card-body">
                            <h1 class="card-title pricing-card-title text-danger">- R$ <?= $totalCashOutflow ?></h1>
                            <small class="text-muted"><strong>Menor despesa</strong> <br> Conta de agua: R$ 62,00 <br>
                                <strong>Maior despesa</strong> <br> Calça: R$ 100,00
                            </small>

                            <!-- <button type="button" class="btn btn-lg btn-block btn-primary">Get started</button> -->
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card mb-3 shadow-sm">
                        <div class="card-header">
                            <h4 class="my-0 font-weight-normal">Saldo</h4>
                        </div>
                        <div class="card-body">
                            <h1 class="card-title pricing-card-title <?= $balance_color_text ?>"> R$
                                <?= $total_balance ?>
                                <small class="text-muted"></small>
                            </h1>
                            <i class="fa-solid fa-sack-dollar fa-5x <?= $balance_color_text ?>"></i>
                            <!-- <img src="<?= $BASE_URL ?>assets/home/dashboard-main/money_bag.png" alt=""> -->
                            <!-- <button type="button" class="btn btn-lg btn-block btn-primary">Contact us</button> -->
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card mb-3 shadow-sm">
                        <div class="card-header">
                            <h4 class="my-0 font-weight-normal">Receita vs. Despesas</h4>
                        </div>
                        <div class="card-body">
                            <h1 class="card-title pricing-card-title text-success"> <small class="text-muted"></small>
                            </h1>
                            <canvas id="myChart2"></canvas>
                            <!-- <button type="button" class="btn btn-lg btn-block btn-primary">Contact us</button> -->
                        </div>
                    </div>
                </div>




            </div>

        </div>

        <div class="actions p-5 mb-4 bg-light rounded-3 shadow-sm">
            <form action="<?= $BASE_URL ?>moviment_process.php" method="post">
                <div class="row">
                    <div class="col-md-3">
                        <h4 class="font-weight-normal">Descriçao</h4>
                        <input type="text" name="description" id="description" class="form-control"
                            placeholder="Ex: Conta de luz">
                    </div>
                    <div class="col-md-2">
                        <h4 class="font-weight-normal">Valor</h4>
                        <input type="text" name="value" id="value" class="form-control number-separator"
                            placeholder="Ex: 80,00:">
                    </div>
                    <div class="col-md-2 text-center">
                        <h4 class="font-weight-normal">Tipo</h4>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="type_action" id="entry" value="1"
                                onclick="show_expense()">
                            <label class="form-check-label" for="inlineRadio1">Entrada</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="type_action" id="out" value="2"
                                onclick="show_expense()">
                            <label class="form-check-label" for="inlineRadio2">Saída</label>
                        </div>
                    </div>
                    <div class="col-md-2 text-center" id="expense">
                        <h4 class="font-weight-normal">Despesa</h4>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="expense_type" id="fixa"
                                class="expense_type" value="F">
                            <label class="form-check-label" for="inlineRadio1">Fixa</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="expense_type" id="variavel"
                                class="expense_type" value="V">
                            <label class="form-check-label" for="inlineRadio2">Variada</label>
                        </div>
                    </div>
                    <div class="col-md-2" id="category_div">
                        <h4 class="font-weight-normal">Categoria</h4>
                        <select class="form-control" name="category" id="">
                            <option value="">Selecione</option>
                            <?php foreach($categorys as $category): ?>
                            <option value="<?= $category->id ?>"> <?= $category->category_name ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-1">
                        <input type="submit" class="btn btn-lg btn-success" value="Adicionar"></input>
                    </div>
                </div>
            </form>
        </div>

        <div class="actions mb-5 pb-2 px-3 bg-light rounded-3 shadow-sm">
            <h3 class="font-weight-normal text-center">Últimas 5 movimentações</h3>
            <!-- <hr class="dashed"> -->
            <div class="row" id="latest_moviments">

                <table class="table">
                    <thead>
                        <!-- <th>id</th> -->
                        <th>Descrição</th>
                        <th>Valor</th>
                        <th>Data</th>
                        <th>Tipo</th>
                        <th>Categoria</th>
                        <th>Ação</th>
                    </thead>
                    <tbody>
                        <?php foreach ($latestFinancialMoviments as $financialMoviment): ?>
                        <tr class="pb-2">
                            <!-- <th scope="row"><?= $financialMoviment->id ?></th> -->
                            <td>
                                <span class="table_description"> <strong> <?= $financialMoviment->description ?> </strong></span>
                            </td>
                            <td>
                                <span> <?= $financialMoviment->value ?></span>
                            </td>
                            <td>
                                <span> <?= $financialMoviment->create_at ?> </span>
                            </td>
                            <td>
                                <?php if ($financialMoviment->type == 1): ?>
                                <i class="fa-solid fa-circle-up entrada"></i>
                                <?php else: ?>
                                <i class="fa-solid fa-circle-down saida"></i>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span> <?= $financialMoviment->category ?> </span>
                            </td>
                            <td><a href="moviment_process.php?id=<?=$financialMoviment->id?>" title="Editar"><i
                                        class="fa-solid fa-file-pen"></i></a>
                                <a href="moviment_process.php?delete=s&id=<?=$financialMoviment->id?>"
                                    title="Deletar"><i class="fa-solid fa-trash-can"></i></a>
                            </td>
                        </tr>

                        <?php endforeach; ?>
                    </tbody>
                </table>

            </div>
        </div>
</body>
<script src="js/Chart.js"></script>
<script>
// Mychart graficos dos projetos em forma de pizza 
var xValues = ["jan", "fev", "mar", "abr", "mai", "jun", "jul", "ago", "set", "out", "nov", "dez"];

new Chart("myChart2", {
    type: "line",
    data: {
        labels: xValues,
        datasets: [{
            data: [860, 1140, 1060, 1060, 1070, 1110, 1330, 2210, 7830, 2478],
            borderColor: "red",
            fill: false
        }, {
            data: [1600, 1700, 1700, 1900, 2000, 2700, 4000, 5000, 6000, 9000],
            borderColor: "green",
            fill: false
        }]
    },
    options: {
        legend: {
            display: false
        }
    }
});
</script>

<?php require_once("templates/footer.php"); ?>