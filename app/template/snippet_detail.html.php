<div class="container">
    <form
        <?php
            $id = $snippet->getId();
            echo 'method = "post" ';
            if ($id != -1) {
                echo 'action = "/codeschnipsel/snippets/'. $id .'"';
            } else {
                echo 'action = "/codeschnipsel/snippets"';
            }
        ?>
        >
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
        <div class="row">
            <div class="col-xs-2">
                <button type="submit" class="btn-block btn-success">Sichern</button>
            </div>
        </div>
    </form>
</div>

<script>
    var editor = CodeMirror.fromTextArea(document.getElementById('editor'), {
        lineNumbers: true,
        mode: "javascript"
    });
    editor.setValue("<?= $snippet->code ?>");
</script>