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
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
