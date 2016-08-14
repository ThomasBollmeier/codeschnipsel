<script>
    var editor = CodeMirror.fromTextArea(document.getElementById('editor'), {
        lineNumbers: true,
        theme: "monokai"
    });

    cs.setEditorLanguage(editor);

    $('#language').change(function() {
        cs.setEditorLanguage(editor);
    });
</script>