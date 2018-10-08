<?php $__env->startSection("content"); ?>


    <div class="pricing-header px-3 py-3 pt-md-5 pb-md-4 mx-auto text-center">
        <h1 class="display-4">Lista de Clientes</h1>
        <p class="lead"></p>
    </div>

    <div class="container">
        <?php if(empty($clientes[0])): ?>
            <h3>Não há dados para listar.</h3>
        <?php endif; ?>
        <?php if(!empty($clientes[0])): ?>
            <div class="row">
                <form action="/cliente/exportar/csv" method="post">
                    <?php echo csrf_field(); ?>

                    <button class="btn btn-primary">Gerar CSV</button>
                    <div style="margin-top: 40px;"></div>
                </form>
                <form action="/cliente/exportar/xls" method="post">
                    <?php echo csrf_field(); ?>
                    <button class="btn btn-success" style="margin-left: 20px;">Gerar XLS</button>
                </form>
            </div>
            <div class="row">
                <?php $__currentLoopData = $clientes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cliente): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-md-6 box-shadow">
                        <div align="center">
                            <h4><?php echo e($cliente->nome); ?></h4>
                        </div>
                        <p>E-mail: <?php echo e($cliente->email); ?></p>
                        <p>CPF: <?php echo e($cliente->cpf); ?></p>
                        <p>Data de Nascimento: <?php echo e(date("d/m/Y",strtotime($cliente->data_nascimento))); ?></p>
                        <p class="text-muted"><b>Endereço: </b> <?php echo e($cliente->endereco->logradouro); ?>

                            , <?php echo e($cliente->endereco->numero); ?>

                            <?php if(!empty($cliente->endereco->complemento)): ?>
                                , <?php echo e($cliente->endereco->complemento); ?>

                            <?php endif; ?>
                            - <?php echo e($cliente->endereco->bairro); ?> - <?php echo e($cliente->endereco->cidade); ?>

                            , <?php echo e($cliente->endereco->cep); ?>

                        </p>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
                <?php echo e($clientes->links()); ?>

            </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make("layout.layout", \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>