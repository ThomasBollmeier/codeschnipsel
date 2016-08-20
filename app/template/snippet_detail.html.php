<div class="container">
    <div class="row">
        <form
            method="post"
            action="<?= $action ?>">
            <div class="form-group">
                <input type="text"
                       id="title"
                       name="title"
                       class="cs-title-input"
                       value="<?= $title ?>"
                       size="80"
                       placeholder="Dein Titel">
            </div>
            <div class="form-group">
                <select id="language"
                        name="language"
                        class="selectpicker">
                    <option>--- Sprache unbekannt ---</option>
                    <?php foreach ($languages as $language) {
                        if ($language->name == $snippetLang) { ?>
                            <option selected><?= $language->name ?></option>
                        <?php } else { ?>
                            <option><?= $language->name ?></option>
                        <?php }
                    } ?>
                </select>
            </div>
            <div class="form-group">
                <textarea id="editor"
                          name="code"
                          rows="30"
                          cols="80"><?= $code ?></textarea>
            </div>
            <div class="form-group">
                <button type="submit"
                        class="btn btn-success"><span class="glyphicon glyphicon-save"></span> Sichern</button>
                <label class="btn btn-success btn-file">
                    <span class="glyphicon glyphicon-open-file"></span> Hochladen
                    <input id="btn-file-upload"
                           type="file">
                </label>
            </div>
        </form>
    </div>
    <div class="row">
        <a href="<?= $baseUrl ?>/#">Zurück zur Übersicht</a>
    </div>

</div>
