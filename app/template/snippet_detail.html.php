<div class="container">
    <form
        method="post"
        action="<?= $action ?>"
        >
        <div class="row">
            <div class="col-xs-4">
                <input type="text"
                       name="title"
                       value="<?= $title ?>"
                       placeholder="Dein Titel">
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <textarea id="editor"
                          name="code"
                          rows="20"
                          cols="80"><?= $code ?></textarea>
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
    /*
    var editor = CodeMirror.fromTextArea(document.getElementById('editor'), {
        lineNumbers: true,
        mode: "javascript"
    });
    editor.setValue(" code ?>");
    */
</script>