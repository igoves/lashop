<?php if(Session::has('toastr')): ?>
    <?php
        $toastr     = Session::get('toastr');
        $type       = array_get($toastr->get('type'), 0, 'success');
        $message    = array_get($toastr->get('message'), 0, '');
        $options    = json_encode($toastr->get('options', []));
    ?>
    <script>
        $(function () {
            toastr.<?php echo e($type, false); ?>('<?php echo $message; ?>', null, <?php echo $options; ?>);
        });
    </script>
<?php endif; ?>