<?php
$currentPage = 'zoekterm';
$pageTitle = 'Zoektermen';
?>
<h1>Zoektermen overzicht</h1>

<?php $melding = $session->getMelding(); ?>
<?php if (!empty($melding)) : ?>
    <div class="melding"><?= htmlspecialchars($melding) ?></div>
<?php endif; ?>

<?php if (empty($zoektermen)) : ?>
    <p>Er zijn nog geen zoektermen binnengekomen.</p>
<?php else : ?>
    <table>
        <thead>
            <tr>
                <th>Zoekterm</th>
                <th>Aantal zoekopdrachten</th>
                <th>Zoektermen verwijderen</th>
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
                                        onclick="return confirm('Weet je zeker dat je \'<?=
                                        htmlspecialchars($zoekterm['zoekterm']) ?>\' wilt verwijderen?')">
                                    Delete
                                </button>
                            </form>
                        </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
