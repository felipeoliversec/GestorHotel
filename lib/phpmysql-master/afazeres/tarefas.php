<?php session_start(); ?>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Gerenciador de Tarefas</title>
        <link rel="stylesheet" href="tarefas.css" type="text/css" />
    </head>
    <body>
        <h1>Gerenciador de Tarefas</h1>
        <form>
            <fieldset>
                <legend>Nova tarefa</legend>
                <label>
                    Tarefa:
                    <input type="text" name="nome" />
                </label>
                <input type="submit" value="Cadastrar" />
            </fieldset>
        </form>
        <?php
            if (isset($_GET['nome'])) {
                $_SESSION['lista_tarefas'][] = $_GET['nome'];
            }
            if (isset($_SESSION['lista_tarefas'])) {
                $lista_tarefas = $_SESSION['lista_tarefas'];
            } else {
                $lista_tarefas = array();
            }
        ?>

        <table>
            <tr>
                <th>Tarefas</th>
            </tr>
            <?php foreach ($lista_tarefas as $tarefa) : ?>
                <tr>
                    <td><?php echo $tarefa; ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </body>
</html>
