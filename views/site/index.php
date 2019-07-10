<?php

/* @var $this yii\web\View */

$this->title = 'My Yii Application';
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>&Oacute;rdenes</h1>
    </div>

    <div class="body-content">

        <div class="row">
            <form action="?r=site/index">
            <table class="table">
                <thead>
                    <th>Nombre del cliente
                    <th>Correo electr&oacute;nico del cliente
                    <th>Tel&eacute;fono del cliente
                    <th>Estado                    
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <input type="text" name="customer_name" class="form-control">
                        </td>
                        <td>
                            <input type="text" name="customer_email" class="form-control">
                        </td>
                        <td>
                            <input type="text" name="customer_mobile" class="form-control">
                        </td>
                        <td>
                            <button class="btn btn-info">Filtrar</button>
                        </td>
                    </tr>
                    <?php
                        foreach($orders as $o){
                            ?>
                        <tr>
                            <td><a href="?r=site/order-info&id=<?= $o->id ?>"><?= $o->customer_name ?></a></td>
                            <td><a href="?r=site/order-info&id=<?= $o->id ?>"><?= $o->customer_email ?></a></td>
                            <td><a href="?r=site/order-info&id=<?= $o->id ?>"><?= $o->customer_mobile ?></a></td>
                            <td><a href="?r=site/order-info&id=<?= $o->id ?>"><?= $o->status ?></a></td>
                        </tr></a>
                        <?php
                        }
                    ?>
                </tbody>

            </table>
            </form>
        </div>

    </div>
</div>
