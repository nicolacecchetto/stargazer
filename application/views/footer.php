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