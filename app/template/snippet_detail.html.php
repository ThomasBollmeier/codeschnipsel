<div class="container">
    <div class="row">
        <div class="col-xs-4">
            <h1><?= $snippet->title ?></h1>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <textarea id="editor"></textarea>
        </div>
    </div>
</div>

<script>
    var editor = CodeMirror.fromTextArea(document.getElementById('editor'), {
        lineNumbers : true,
        mode : "javascript"
    });
    editor.setValue("<?= $snippet->code ?>");
</script>