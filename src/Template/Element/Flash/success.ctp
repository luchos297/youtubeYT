<?= $this->Html->script([
    '/assets/js/jquery-1.10.2.min.js',
    '/assets/plugins/bootstrap/js/bootstrap.min.js',

    'http://cdnjs.cloudflare.com/ajax/libs/underscore.js/1.5.2/underscore-min.js',
    'http://cdnjs.cloudflare.com/ajax/libs/backbone.js/1.1.0/backbone-min.js',

    '/assets/plugins/messenger/js/messenger.min.js',
    '/assets/plugins/messenger/js/messenger-theme-future.js',

    '/assets/plugins/messenger/js/demo/location-sel.js',        
    '/assets/plugins/messenger/js/demo/theme-sel.js',
    '/assets/plugins/messenger/js/demo/demo.js',
    '/assets/plugins/messenger/js/demo/demo-messages.js'        
]);?>

<script>
    $(document).ready(function() {
        showSuccess("<?= h($message) ?>");
    });
</script>
