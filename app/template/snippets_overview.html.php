<div class="container">
    <div class="row">
        <div class="col-xs-2">
            <a href="/codeschnipsel/new-snippet" class="btn btn-success">
                <span class="glyphicon glyphicon-plus"></span> Neues Schnipsel</a>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <table class="table table-striped">
                <tr>
                    <th>Titel</th>
                </tr>
                <?php foreach ($snippets as $snippet) { ?>
                    <tr>
                        <td><a href="<?= "/codeschnipsel/snippets/" . $snippet->getId() ?>"><?= $snippet->title ?></a>
                        </td>
                    </tr>
                <?php } ?>
            </table>
        </div>
    </div>
</div>

