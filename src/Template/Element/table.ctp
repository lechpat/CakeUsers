<table>
	<tr>
		<th><?= $this->Paginator->sort('username', __d('users', 'Username')) ?></th>
		<th><?= $this->Paginator->sort('email', __d('users', 'Email')) ?></th>
		<th><?= $this->Paginator->sort('email_verified', __d('users', 'Email Verified')) ?></th>
		<th><?= $this->Paginator->sort('created', __d('users', 'Created')) ?></th>
	</tr>
	<?php foreach ($users as $user) : ?>
		<tr>
			<td>
				<?= $this->Html->link($user->username, ['action' => 'view', $user->id]) ?>
			</td>
			<td>
				<?= h($user->email) ?>
			</td>
			<td>
				<?= $user->email_verified == 1 ? __d('users', 'Yes') : __d('users', 'No') ?>
			</td>
			<td>
				<?php
					if (empty($user->created)) {
						echo __d('users', 'N/A');
					} else {
						echo h($this->Time->format($user->created, '%c'));
					}
				?>
			</td>
		</tr>
	<?php endforeach; ?>
</table>
