<div class="container">
    <div class="row">
        <table class="table table-striped">
            <tr>
                <th>Titel</th>
            </tr>
        <?php foreach ($snippets as $snippet) { ?>
            <tr>
                <td><a href="<?= "/codeschnipsel/snippets/".$snippet->getId() ?>"><?= $snippet->title ?></a></td>
            </tr>
        <?php } ?>
        </table>
    </div>
</div>

