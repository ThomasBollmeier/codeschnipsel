<script>

    var editor = CodeMirror.fromTextArea(document.getElementById('editor'), {
        lineNumbers: true,
        theme: "monokai"
    });

    cs.setEditorLanguage(editor);

    $('#language').change(function() {
        cs.setEditorLanguage(editor);
    });

    $('#btn-file-upload').on('change', function() {
        cs.onFileUpload.bind($(this)[0])(editor);
    });

</script>