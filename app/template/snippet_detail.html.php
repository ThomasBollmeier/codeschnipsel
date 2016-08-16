<div class="container">
    <div class="row">
        <form
            method="post"
            action="<?= $action ?>">
            <div class="form-group">
                <input type="text"
                       name="title"
                       class="cs-title-input"
                       value="<?= $title ?>"
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
                          rows="20"
                          cols="80"><?= $code ?></textarea>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-success">Sichern</button>
            </div>
        </form>
    </div>
    <div class="row">
        <a href="<?= $baseUrl ?>/#">Zurück zur Übersicht</a>
    </div>

</div>
