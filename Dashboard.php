<?php namespace Model\Dashboard;

use Model\Core\Autoloader;
use Model\Core\Module;

class Dashboard extends Module
{
	public function render(array $cards)
	{
		$totalCards = 0;
		foreach ($cards as $row) {
			?>
			<div class="row pb-3">
				<?php
				foreach ($row as $col) {
					if (!isset($col['class'], $col['cards']))
						$this->model->error('Invalid dashboard configuration ("class" or "cards" missing)');
					?>
					<div class="<?= entities($col['class']) ?>">
						<?php
						foreach ($col['cards'] as $idx => $cardOptions) {
							if (!isset($cardOptions['type'], $cardOptions['options']))
								$this->model->error('Invalid dashboard configuration ("type" or "options" missing)');
							?>
							<div class="card<?= $idx > 0 ? ' mt-3' : '' ?>">
								<?php
								$className = Autoloader::searchFile('Card', $cardOptions['type']);
								if (!$className)
									$this->model->error('No card type named "' . $cardOptions['type'] . '"');

								$card = new $className($this->model, $totalCards);
								$card->render($cardOptions['options']);

								$totalCards++;
								?>
							</div>
							<?php
						}
						?>
					</div>
					<?php
				}
				?>
			</div>
			<?php
		}
	}
}
