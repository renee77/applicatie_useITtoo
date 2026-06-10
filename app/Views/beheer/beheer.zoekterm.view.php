<?php
$currentPage = 'zoekterm';
$pageTitle = __('searches.page_title');
?>
<h1><?= __('searches.title') ?></h1>

<?php $melding = $session->getMelding(); ?>
<?php if (!empty($melding)) : ?>
    <div class="melding"><?= htmlspecialchars($melding) ?></div>
<?php endif; ?>

<?php if (empty($zoektermen)) : ?>
    <p><?= __('searches.empty_searches') ?></p>
<?php else : ?>
    <table>
        <thead>
            <tr>
                <th><?= __('searches.search') ?></th>
                <th><?= __('searches.amount') ?></th>
                <th><?= __('searches.delete') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($zoektermen as $zoekterm) : ?>
                <tr>
                    <td><?= htmlspecialchars($zoekterm['zoekterm']) ?></td>
                    <td><?= $zoekterm['aantal'] ?></td>
                        <td>
                            <form method="POST" action="<?= BASE_URL ?>/beheer/zoekterm/delete">
                                <input type="hidden" name="zoekterm" 
                                value="<?= htmlspecialchars($zoekterm['zoekterm']) ?>">
                                <button type="submit" class="deleteBtn changesBtn"
                                    onclick="
                                    return 
                                    confirm('<?= __('searches.conf') . htmlspecialchars($zoekterm['zoekterm']) ?>')">
                                    <?= __('searches.delete_btn') ?>
                                </button>
                            </form>
                        </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
