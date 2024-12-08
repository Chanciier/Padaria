<?php
session_start();
include_once('config.php');

// Verificação de sessão
if ((!isset($_SESSION['idnew_table']) || !isset($_SESSION['senha']))) {
    unset($_SESSION['idnew_table'], $_SESSION['senha']);
    header('location: login.php');
}
$logado = $_SESSION['idnew_table'];

// Atualizar pedido
if (isset($_POST['edit_pedido'])) {
    $id = $_POST['idpedidos'];
    $item = $_POST['item'];
    $obs = $_POST['obs'];
    $data_ped = $_POST['data_ped'];
    $data_ent = $_POST['data_ent'];
    $nome_cl = $_POST['nome_cl'];

    $sql_update = "UPDATE pedidos 
                   SET item='$item', obs='$obs', data_ped='$data_ped', data_ent='$data_ent', nome_cl='$nome_cl'
                   WHERE idpedidos='$id'";
    $result_update = $conexao->query($sql_update);

    if ($result_update) {
        echo "<script>alert('Pedido atualizado com sucesso!');</script>";
    } else {
        echo "<script>alert('Erro ao atualizar o pedido.');</script>";
    }
}

// Pesquisa de pedidos
$searchQuery = '';
if (!empty($_GET['search'])) {
    $data = $_GET['search'];
    $searchQuery = "WHERE idpedidos LIKE '%$data%' 
                    OR item LIKE '%$data%' 
                    OR obs LIKE '%$data%' 
                    OR data_ped LIKE '%$data%' 
                    OR data_ent LIKE '%$data%' 
                    OR nome_cl LIKE '%$data%'";
}
$sql = "SELECT * FROM pedidos $searchQuery ORDER BY data_ent ASC";
$result = $conexao->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/sistema.css">
    <link rel="stylesheet" href="./css/style_nav.css">
    <link rel="shortcut icon" href="./img/icon.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <title>Sistema</title>
</head>
<body>
    <!-- Cabeçalho -->
    <header class="d-flex justify-content-between align-items-center p-3 bg-dark text-white">
        <a class="text-decoration-none text-white" href="index.php"><h1>Logo Padoca</h1></a>
        <div>
            <a href="sair.php" class="btn btn-danger">Sair</a>
        </div>
    </header>

    <!-- Barra de ferramentas -->
    <div class="container my-4">
        <div class="d-flex justify-content-between align-items-center">
            <!-- Barra de pesquisa -->
            <input type="search" class="form-control w-50" placeholder="Pesquisar" id="pesquisar">
            <button onclick="searchData()" class="btn btn-primary ms-2">Pesquisar</button>

            <!-- Botão Adicionar -->
            <button class="btn btn-success ms-2" data-bs-toggle="modal" data-bs-target="#addModal">Adicionar Pedido</button>
        </div>
    </div>

    <!-- Tabela de pedidos -->
    <div class="container">
        <table class="table table-striped table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Item</th>
                    <th>Observação</th>
                    <th>Data de Emissão</th>
                    <th>Data de Entrega</th>
                    <th>Cliente</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                    <?php while ($user_data = mysqli_fetch_assoc($result)) : 
                        // Formatando as datas no padrão brasileiro (DD/MM/AAAA)
                        $data_ped = date('d/m/Y', strtotime($user_data['data_ped']));
                        $data_ent = date('d/m/Y', strtotime($user_data['data_ent']));
                    ?>
                    <tr>
                        <td><?= $user_data['idpedidos'] ?></td>
                        <td><?= $user_data['item'] ?></td>
                        <td><?= $user_data['obs'] ?></td>
                        <td><?= $user_data['data_ped'] ?></td>
                        <td><?= $user_data['data_ent'] ?></td>
                        <td><?= $user_data['nome_cl'] ?></td>
                        <td>
                            <button 
                                class="btn btn-warning btn-sm" 
                                data-bs-toggle="modal" 
                                data-bs-target="#editModal" 
                                onclick="loadEditData(<?= htmlspecialchars(json_encode($user_data)) ?>)">Editar</button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal Adicionar Pedido -->
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addModalLabel">Adicionar Pedido</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="item" class="form-label">Item</label>
                            <input type="text" name="item" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="obs" class="form-label">Observação</label>
                            <input type="text" name="obs" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="data_ped" class="form-label">Data de Emissão</label>
                            <input type="date" name="data_ped" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="data_ent" class="form-label">Data de Entrega</label>
                            <input type="date" name="data_ent" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="nome_cl" class="form-label">Cliente</label>
                            <input type="text" name="nome_cl" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="add_pedido" class="btn btn-primary">Salvar</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Editar Pedido -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Editar Pedido</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="idpedidos" id="edit_id">
                        <div class="mb-3">
                            <label for="item" class="form-label">Item</label>
                            <input type="text" name="item" id="edit_item" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="obs" class="form-label">Observação</label>
                            <input type="text" name="obs" id="edit_obs" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="data_ped" class="form-label">Data de Emissão</label>
                            <input type="date" name="data_ped" id="edit_data_ped" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="data_ent" class="form-label">Data de Entrega</label>
                            <input type="date" name="data_ent" id="edit_data_ent" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="nome_cl" class="form-label">Cliente</label>
                            <input type="text" name="nome_cl" id="edit_nome_cl" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="edit_pedido" class="btn btn-primary">Salvar Alterações</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        function searchData() {
            const searchValue = document.getElementById('pesquisar').value.trim();
            if (searchValue) {
                window.location.href = `?search=${searchValue}`;
            }
        }

        function loadEditData(data) {
            document.getElementById('edit_id').value = data.idpedidos;
            document.getElementById('edit_item').value = data.item;
            document.getElementById('edit_obs').value = data.obs;
            document.getElementById('edit_data_ped').value = data.data_ped;
            document.getElementById('edit_data_ent').value = data.data_ent;
            document.getElementById('edit_nome_cl').value = data.nome_cl;
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
