        <div class="btn-group btn-group-justified">
            <a href="<?php echo site_url('defcont/planetsnow') ?>" class="btn btn-primary elementbg">Planets Right Now</a>
            <a href="<?php echo site_url('defcont/formdate') ?>" class="btn btn-primary elementbg">Planets by Date</a>
            <a href="<?php echo site_url('defcont/formlatlong') ?>" class="btn btn-primary elementbg">Change Coordinates</a>
            <div class="btn-group">
                <button type="button" class="btn btn-primary dropdown-toggle elementbg" data-toggle="dropdown">
                Orbital Elements <span class="caret"></span></button>
                <ul class="dropdown-menu" role="menu">
                    <?php foreach($this->session->objects as $object): ?>
						<li><?= anchor('defcont/orbelem?id='.$object->ID, $object->Name) ?></li>
            		<?php endforeach ?>
                </ul>
            </div>
        </div>