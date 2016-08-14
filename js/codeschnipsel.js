var cs = (function () {

    "use strict";

    return {

        onSnippetDelete: function () {

            var snippetId = $(this).data('snippetId'),
                $tableRow = $(this).parent().parent(),
                title = $tableRow.children().first().text(),
                $modal = $('#confirm-snippet-deletion'),
                $modalTitle = $modal.find('.modal-title');

            $modal.find('#btn-snippet-deletion').click(function() {
                $modal.modal('hide');
                // Send a GET request for the deletion
                location.href = '/codeschnipsel/snippets/delete/' + snippetId;
            });

            $modalTitle.text('Löschung von "' + title + '"');
            $modal.modal('show');

            return false;

        },

        setEditorLanguage: function(editor) {

            var lang = $('#language').val(),
                langModeMap = {
                    "C/C++" : "clike",
                    "Clojure": "clojure",
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