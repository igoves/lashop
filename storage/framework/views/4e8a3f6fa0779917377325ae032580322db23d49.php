<h3>Order Form</h3>
<div class="card">
    <div class="card-body">
        <div class="form-group">
            <?php echo e(Form::label('name', 'Name'), false); ?>

            <?php echo e(Form::text('name', null, ['class' => 'form-control']), false); ?>

        </div>
        <div class="form-group">
            <?php echo e(Form::label('title', 'Email'), false); ?>

            <?php echo e(Form::email('email', null, ['class' => 'form-control']), false); ?>

        </div>
        <div class="form-group">
            <?php echo e(Form::label('title', 'Phone'), false); ?>

            <?php echo e(Form::text('phone', null, ['class' => 'form-control']), false); ?>

        </div>
        <div class="form-group">
            <?php echo e(Form::label('content', 'Comment'), false); ?>

            <?php echo e(Form::textArea('comment', null, ['class' => 'form-control', 'rows' => 2]), false); ?>

        </div>
        <div class="form-group">
            <?php echo e(Form::submit($submitButtonText, ['class' => 'btn btn-primary btn-block']), false); ?>

        </div>
    </div>
</div>