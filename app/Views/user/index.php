<?php $title = 'Gebruikers'; ?>
<h2>Alle gebruikers</h2>
<ul>
<?php foreach ($users as $user) : ?>
    <li><?= htmlspecialchars($user->getName()) ?> - <?= htmlspecialchars($user->getEmail()) ?></li>
<?php endforeach; ?>
</ul>
