<div class="container">
    <div class="container-title">
        <h4>Lista de Pedidos</h4>
    </div>

    <hr>

    <div class="table-content">
        <table class="striped">
            <thead>
                <th>ID Pedido</th>
                <th>Nome</th>
                <th>Data do Pedido</th>
                <th>Situação do Pedido</th>
                <th>Total (R$)</th>
                <th>Forma de Pagamento</th>
                <th>Ações</th>
            </thead>
            <tbody>
                <?php 
                foreach($this->Data as $row):
                    extract($row); ?>
                        <tr>
                            <td><?= $id; ?></td>
                            <td><?= $customer_name; ?></td>
                            <td><?= date("d/m/Y", strtotime($data)); ?></td>
                            <td>
                                <?php
                                    switch($order_description) {
                                        case 'Aguardando Pagamento':
                                            echo "<span class='badge blue' style='color: #FFF'>Aguardando Pagamento</span>";
                                            break;

                                            case 'Pagamento Identificado':
                                                echo "<span class='badge grey' style='color: #FFF'>Pagamento Identificado</span>";
                                                break;

                                            case 'Pedido Cancelado':
                                                echo "<span class='badge red' style='color: #FFF'>Pedido Cancelado</span>";
                                                break;
                                    }
                                ?>
                            </td>
                            <td><?= str_replace(".", ",", $valor_total); ?></td>
                            <td><?= $id_forma_pgto . " - " . $forma_pgto; ?></td>
                            <td>
                                <?php if($id_situacao == 1 && $id_forma_pgto == 3): ?>
                                    <a href="<?= URL . '/ProcessTransaction/' . $id ?>" class="tooltipped" data-position="top" data-tooltip="Processar Transação"><i class="fa-solid fa-check confirm"></i></a>
                                <?php endif; ?>                            
                            </td>
                        </tr>
                    <?php endforeach ?>                    
            </tbody>
        </table>
    </div>

    <?php
    if(isset($_SESSION['error_message'])) {
        echo $_SESSION['error_message'];
        unset($_SESSION['error_message']);
    }

    if(isset($_SESSION['success_message'])) {
        echo $_SESSION['success_message'];
        unset($_SESSION['success_message']);
    }
    ?>

</div>