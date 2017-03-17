var cs = (function () {

    "use strict";

    return {

        onSigninClicked: function(baseUrl) {

            var email = $('#email').val(),
                password = $('#password').val();

            $.post(baseUrl + "/signin", {
                email: email,
                password: password
            }).done(function() {
                location.href = baseUrl;
            });

        },

        onSnippetDelete: function () {

            var snippetId = $(this).data('snippetId'),
                $tableRow = $(this).parent().parent(),
                title = $tableRow.children().first().text(),
                $modal = $('#confirm-snippet-deletion'),
                $modalTitle = $modal.find('.modal-title');

            $modal.find('#btn-snippet-deletion').click(function() {
                $modal.modal('hide');
                // Send a DELETE request to delete the snippet
                $.ajax({
                    url: '/codeschnipsel/snippets/' + snippetId,
                    method: 'DELETE'
                }).done(function() {
                   location.href = '/codeschnipsel/snippets'; 
                });
            });

            $modalTitle.text('Löschung von "' + title + '"');
            $modal.modal('show');

            return false;

        },

        onFileUpload: function (editor) {

            var files = this.files,
                reader;

            if (files) {
                reader = new FileReader();
                reader.onload = function() {
                    var title = $('#title').val();
                    if (!title) {
                        $('#title').val(files[0].name);
                    }
                    editor.setValue(this.result);
                };
                reader.readAsText(files[0]);
            }

        },

        setEditorLanguage: function(editor) {

            var lang = $('#language').val(),
                langModeMap = {
                    "C/C++" : "clike",
                    "Clojure": "clojure",
                    "Erlang": "erlang",
                    "JavaScript" : "javascript",
                    "PHP": "php",
                    "Python": "python",
                    "Ruby": "ruby",
                    "Haskell": "haskell"
                },
                mode = langModeMap[lang];

            if (mode) {
                editor.setOption('mode', mode);
            }

        }

    };

})();