<div class="container">
    <div class="row">
        <div class="col-xs-2">
            <a href="<?= $baseUrl ?>/snippets/new" class="btn btn-success">
                <span class="glyphicon glyphicon-plus"></span> Neues Schnipsel</a>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <table class="table table-striped">
                <tr>
                    <th colspan="2">Titel</th>
                </tr>
                <?php foreach ($snippets as $snippet) { ?>
                    <tr>
                        <td><a href="<?= "$baseUrl/snippets/" . $snippet->getId() ?>"><?= $snippet->title ?></a>
                        </td>
                        <td class="text-right">
                            <a href="<?= "$baseUrl/snippets/" . $snippet->getId() . "/edit" ?>"
                                ><span class="glyphicon glyphicon-pencil"></span> 
                            </a>
                            <a href="#"
                               class="snippet-delete btn btn-default"
                               data-snippet-id = "<?= $snippet->getId() ?>"
                               ><span class="glyphicon glyphicon-trash"></span></a>
                        </td>
                    </tr>
                <?php } ?>
            </table>
        </div>
    </div>
</div>

<div id="confirm-snippet-deletion"
     class="modal fade"
     tabindex="-1"
     role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button"
                        class="close"
                        data-dismiss="modal"
                        aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">[Titel]</h4>
            </div>
            <div class="modal-body">
                <p>Wollen Sie wirklich fortfahren?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Nein</button>
                <button id="btn-snippet-deletion"
                        type="button"
                        class="btn btn-danger">Ja, Löschen</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->