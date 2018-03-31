<div class="jokelist">
	<ul class="categories">
		<?php foreach ($categories as $category): ?>
			<li><a href="/joke/list?category=<?=$category->id?>"><?=$category->name?></a><li>
		<?php endforeach; ?>
	</ul>
</div>

<div class="jokes">
	<p><?= $totalJokes; ?> jokes have been submitted to the Internet Joke Database.</p>

	<?php foreach ($jokes as $joke): ?>
		<blockquote>
			<!--<p>-->
				<!--<?= htmlspecialchars($joke->joketext, ENT_QUOTES, 'UTF-8'); ?> -->
				<?=(new \Ninja\Markdown($joke->joketext))->toHtml()?>
				(by <a href="mailto <?= htmlspecialchars($joke->getAuthor()->email, ENT_QUOTES, 'UTF-8'); ?>">
					<?= htmlspecialchars($joke->getAuthor()->name, ENT_QUOTES, 'UTF-8'); ?></a> on
					<?php $date = new DateTime($joke->jokedate);
					echo $date->format('jS F Y'); ?>) <!--nicer date-->

				<?php if ($user):?>
					<?php if ($user->id == $joke->authorId || $user->hasPermission(\Ijdb\Entity\Author::EDIT_USER_ACCESS)): ?>
						<a href="/joke/edit?id=<?=$joke->id;?>">Edit</a><!--"/joke/edit&id..."is wrong; 'action' miswritten to 'acttion', always redirect to Home page.-->
					<?php endif;?>

					<?php if ($user->id == $joke->authorId || $user->hasPermission(\Ijdb\Entity\Author::DELETE_JOKES)): ?>
						<form action="/joke/delete" method="post">
							<input type="hidden" name="id" value="<?= $joke->id;?>">
							<input type="submit" value="Delete">
						</form>
					<?php endif; ?>
			<?php endif;?>
			<!--</p>-->
		</blockquote>
	<?php endforeach; ?>


Select page:

<?php

$numPages = ceil($totalJokes/10);

for ($i = 1; $i <= $numPages; $i++):
  if ($i == $currentPage):
?>
  <a class="currentpage" href="/joke/list?page=<?=$i?><?=!empty($categoryId) ? '&category=' . $categoryId : '' ?>"><?=$i?></a>
<?php else: ?>
  <a href="/joke/list?page=<?=$i?><?=!empty($categoryId) ? '&category=' . $categoryId : '' ?>"><?=$i?></a>
<?php endif; ?>
<?php endfor; ?>
</div>
