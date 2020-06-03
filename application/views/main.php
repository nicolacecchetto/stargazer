		<div class="container-fluid">
			<table class="table table-bordered table-dark elementbg">
				<tr>
					<th>#</th>
					<?php foreach($this->session->objects as $object): ?>
						<th><?= anchor('defcont/orbelem?id='.$object->ID, $object->Name) ?></th>
            		<?php endforeach ?>
				</tr>
				<tr>
					<th> <a href="https://en.wikipedia.org/wiki/Right_ascension" target="_blank" data-toggle="tooltip" data-placement="right" title="RA"> RA </a> </th>
					<?php foreach($this->session->objects as $object): ?>
						<td id="<?php echo $object->Name?>RA"><?php echo $object->RA?></td>
            		<?php endforeach ?>
				</tr>
				<tr>
					<th> <a href="https://en.wikipedia.org/wiki/Declination" target="_blank" data-toggle="tooltip" data-placement="right" title="Declination"> Declination </a> </th>
					<?php foreach($this->session->objects as $object): ?>
						<td id="<?php echo $object->Name?>Declination"><?php echo $object->Decl?></td>
            		<?php endforeach ?>
				</tr>
				<tr>
					<th> <a href="https://en.wikipedia.org/wiki/Azimuth" target="_blank" data-toggle="tooltip" data-placement="right" title="Azimuth"> Azimuth </a> </th>
					<?php foreach($this->session->objects as $object): ?>
						<td id="<?php echo $object->Name?>Azimuth"><?php echo $object->azimuth?></td>
            		<?php endforeach ?>
				</tr>
				<tr>
					<th> <a href="#" target="_blank" data-toggle="tooltip" data-placement="right" title="Altitude"> Altitude </a> </th>
					<?php foreach($this->session->objects as $object): ?>
						<td id="<?php echo $object->Name?>Altitude"><?php echo $object->altitude?></td>
            		<?php endforeach ?>
				</tr>
			</table>
			<div class="row footer">
				<div class="col-sm-6 footerleft">
					<p> Last Update <?php echo $this->session->lastTimeStamp ?> </p>
					<p> Latitude : <?php echo $this->session->lat ?>, Longitude : <?php echo $this->session->long ?> </p>
					<p> <?= anchor('defcont/planetsnow', 'Refresh Data') ?> </p>
				</div>
				<div class="col-sm-6 footerright">
					<p> Made by Nicola Cecchetto </p>
				</div>
			</div>
		</div>
	</body>
</html>