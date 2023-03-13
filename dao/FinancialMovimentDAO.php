<?php 
    require_once("models/FinancialMoviment.php");
    require_once("models/User.php");
    require_once("models/Message.php");


    Class FinancialMovimentDAO implements FinancialMovimentDAOInterface {

        private $conn;
        private $url;
        private $message;

        public function __construct(PDO $conn, $url) {
            $this->conn = $conn;
            $this->url = $url;
            $this->message = new Message($url);
        }


        public function buildFinancialMoviment($data) {

            $financialMoviment = new FinancialMoviment();

            $financialMoviment->id = $data['id'];
            $financialMoviment->description = $data['description'];
            $financialMoviment->value = number_format($data['value'], 2, ',', '.');
            $financialMoviment->type = $data['type'];
            $financialMoviment->users_id = $data["users_id"];
            $financialMoviment->create_at = $data['create_at'];
            $financialMoviment->update_at = $data["update_at"];

            return $financialMoviment;
        }

        public function findAll() {

        }

        public function getLatestFinancialMoviment($id) {
            
            $financialMoviments = [];

            $stmt = $this->conn->query("SELECT * FROM tb_finances WHERE users_id = $id ORDER BY id DESC LIMIT 5");

            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                
                $financialMovimentsArray = $stmt->fetchAll();

                foreach ($financialMovimentsArray as $financialMoviment){

                    $financialMoviments[] = $this->buildFinancialMoviment($financialMoviment);
                
                }
            }
            return $financialMoviments;
        }

        public function getAllCashInflow($id) {

            // Pega mês atual
            $mes = date('m');

            $stmt = $this->conn->query("SELECT SUM(value) as sum FROM tb_finances WHERE MONTH(create_at) = '$mes' AND users_id = $id AND type = 1");
            $stmt->execute();
            $row = $stmt->fetch();

            $sum = number_format($row['sum'], 2, ',', '.');

            return $sum;

        }

        public function getAllCashOutflow($id) {

            // Pega mês atual
            $mes = date('m');

            $stmt = $this->conn->query("SELECT SUM(value) as sum FROM tb_finances WHERE MONTH(create_at) = '$mes' AND users_id = $id AND type = 2");
            $stmt->execute();
            $row = $stmt->fetch();

            $sum = number_format($row['sum'], 2, ',', '.');

            return $sum;

        }

        public function getTotalBalance($id) {

            // Pega mês atual
            $mes = date('m');

            $stmt = $this->conn->query("SELECT (SELECT SUM(value) FROM tb_finances WHERE MONTH(create_at) = '$mes' AND users_id = $id AND TYPE = 1) - (SELECT SUM(value) FROM tb_finances WHERE MONTH(create_at) = '$mes' AND users_id = $id AND TYPE = 2) AS total_balance");
            $stmt->execute();
            $row = $stmt->fetch();

            $total_balance = number_format($row['total_balance'], 2, ',', '.');

            return $total_balance;

        }

        public function getCashInflowReport($monthy) {

        }

        public function getOutReport($monthy) {

        }

        public function findById($id) {

        }

        public function create(FinancialMoviment $financialMoviment) {

            $stmt = $this->conn->prepare("INSERT INTO tb_finances (
                id, description, value, type, expense, create_at, users_id
            ) VALUES(
                :id, :description, :value, :type, :expense, now(), :users_id
            )");

            $stmt->bindParam(':id', $financialMoviment->id);
            $stmt->bindParam(':description', $financialMoviment->description);
            $stmt->bindParam(':value', $financialMoviment->value);
            $stmt->bindParam(':type', $financialMoviment->type);
            $stmt->bindParam(':expense', $financialMoviment->expense);
            // $stmt->bindParam(':create_at', $financialMoviment->create_at);
            $stmt->bindParam(':users_id', $financialMoviment->users_id);
            
            $stmt->execute();
            
            $type_moviment = "";

            switch($financialMoviment->type):
                case 1:
                    $type_moviment = "Entrada";
                    break; 
                case 2:
                    $type_moviment = "Saída";
            endswitch;

            $this->message->setMessage("$type_moviment registrada com sucesso!", "success", "back");

            echo"<script>
                function topo(){
                    parent.scroll(0,0);
                }
            </script>";

        }

        public function update(FinancialMoviment $financialMoviment) {

        }

        public function destroy($id) {

            // Checa se existe id
            if ($id) {
                
                $stmt = $this->conn->prepare("DELETE FROM tb_finances WHERE id = :id");
                $stmt->bindParam(":id", $id);

                if ($stmt->execute()) {
                    $this->message->setMessage("Registro excluído com sucesso!", "success", "back");
                }

            }

        }

    }