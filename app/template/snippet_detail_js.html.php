<script>

    var editor = CodeMirror.fromTextArea(document.getElementById('editor'), {
        <?php if ($readOnly) {?>
        readOnly: true,
        <?php } ?>
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

    var tags = $('#tags').tagsinput({
        tagClass: 'label label-success'
    });

</script>