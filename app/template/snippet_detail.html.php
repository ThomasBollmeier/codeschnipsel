<div class="container">
    <form
        method="post"
        action="<?= $action ?>"
    >
        <div class="form-group">
            <input type="text"
                   name="title"
                   class="cs-title-input"
                   value="<?= $title ?>"
                   placeholder="Dein Titel">
        </div>
        <div class="form-group">
                <textarea id="editor"
                          name="code"
                          rows="20"
                          cols="80"><?= $code ?></textarea>
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-success">Sichern</button>
        </div>
    </form>
</div>

<script>
    var editor = CodeMirror.fromTextArea(document.getElementById('editor'), {
        lineNumbers: true,
        mode: "javascript",
        theme: "monokai"
    });
</script>