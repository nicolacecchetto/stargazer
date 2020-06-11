		<div class="container-fluid">
			<table class="table table-bordered table-dark elementbg">
				<tr>
					<th>#</th>
					<?php foreach($this->session->objects as $object): ?>
						<th><?= anchor('defcont/orbelem?id='.$object->ID, $object->Name) ?></th>
            		<?php endforeach ?>
				</tr>
				<tr>
					<th> <a href="https://en.wikipedia.org/wiki/Right_ascension" target="_blank" data-toggle="tooltip" data-placement="right" title="RA on Wikipedia"> RA </a> </th>
					<?php foreach($this->session->objects as $object): ?>
						<td id="<?php echo $object->Name?>RA"><?php echo $object->RA?></td>
            		<?php endforeach ?>
				</tr>
				<tr>
					<th> <a href="https://en.wikipedia.org/wiki/Declination" target="_blank" data-toggle="tooltip" data-placement="right" title="Declination on Wikipedia"> Declination </a> </th>
					<?php foreach($this->session->objects as $object): ?>
						<td id="<?php echo $object->Name?>Declination"><?php echo $object->Decl?></td>
            		<?php endforeach ?>
				</tr>
				<tr>
					<th> <a href="https://en.wikipedia.org/wiki/Azimuth" target="_blank" data-toggle="tooltip" data-placement="right" title="Azimuth on Wikipedia"> Azimuth </a> </th>
					<?php foreach($this->session->objects as $object): ?>
						<td id="<?php echo $object->Name?>Azimuth"><?php echo $object->azimuth?></td>
            		<?php endforeach ?>
				</tr>
				<tr>
					<th> <a href="https://en.wikipedia.org/wiki/Horizontal_coordinate_system#:~:text=Altitude%20(alt.)%2C%20sometimes,be%20used%20instead%20of%20altitude." target="_blank" data-toggle="tooltip" data-placement="right" title="Altitude on Wikipedia"> Altitude </a> </th>
					<?php foreach($this->session->objects as $object): ?>
						<td id="<?php echo $object->Name?>Altitude"><?php echo $object->altitude?></td>
            		<?php endforeach ?>
				</tr>
			</table>
		</div>