<div class="container">
    <div class="row">
        <form
            method="post"
            action="<?= $action ?>">
            <div class="form-group">
                <?php if (!$readOnly) {?>
                <label for="title"
                       class="cs-label">Titel</label>
                <input type="text"
                       id="title"
                       name="title"
                       class="cs-text-input"
                       value="<?= $title ?>"
                       size="80"
                       placeholder="Dein Titel">
                <?php } else { ?>
                <h2><?= $title ?></h2>
                <?php } ?>
            </div>
            <div class="form-group">
                <label for="language"
                       class="cs-label">Sprache</label>
                <select id="language"
                        name="language"
                        class="selectpicker"
                        <?php if ($readOnly) { ?>
                        disabled
                        <?php } ?>
                        >
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
                          cols="80"
                          width="100%"
                          height="100%"
                          <?php if ($readOnly) { ?>
                          disabled
                          <?php } ?>
                          ><?= $code ?></textarea>
            </div>
            <div class="form-group">
                <label for="tags"
                       class="cs-label">Stichworte</label>
                <input id="tags"
                       name="tags"
                       class="cs-text-input"
                       type="text"
                       value="<?= $tagsStr ?>"
                       size="80"
                       data-role="tagsinput"
                       <?php if ($readOnly) { ?>
                       disabled
                       <?php } ?>
                       >
            </div>
            <?php if (!$readOnly) { ?>
            <div class="form-group">
                <button type="submit"
                        class="btn btn-success"><span class="glyphicon glyphicon-save"></span> Sichern</button>
                <label class="btn btn-success btn-file">
                    <span class="glyphicon glyphicon-open-file"></span> Hochladen
                    <input id="btn-file-upload"
                           type="file">
                </label>
                <a href="<?= "$baseUrl/snippets/" . $id ?>"
                   class="btn btn-success cs-button"
                   ><span class="glyphicon glyphicon-book"></span> Zum Lesemodus</a>
            </div>
            <?php } else if ($isEditAllowed) { ?>
            <div class="form-group">
                <a href="<?= "$baseUrl/snippets/" . $id . "/edit" ?>"
                   class="btn btn-success"
                   ><span class="glyphicon glyphicon-pencil"></span> Bearbeiten</a>
            </div>
            <?php } ?>
        </form>
    </div>
    <div class="row">
        <a href="<?= $baseUrl ?>/#">Zurück zur Übersicht</a>
    </div>

</div>
