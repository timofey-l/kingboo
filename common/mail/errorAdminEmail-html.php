<b>$_POST:</b>
<br/>
<pre>
	<?= var_export($_POST, true) ?>
</pre>

<b>$_SERVER:</b>
<br/>
<pre>
	<?= var_export($_SERVER, true) ?>
</pre>
<?php if (isset($message)): ?>
    <pre>
        <?= $message ?>
    </pre>
<?php endif; ?>