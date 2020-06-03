        <div class="container-fluid">
            <table class="table table-bordered table-dark elementbg">
                <tr>
					<th>Longitude of the Ascending Node</th>
                    <th>Inclination (relative to the Ecliptic)</th>
                    <th>Argument of the Periapsis</th>
                    <th>Semimajor Axis</th>
                    <th>Orbital Eccentricity</th>
                    <th>Mean Anomaly</th>
                </tr>
                <tr>
                    <td><?php echo $object->N?></td>
                    <td><?php echo $object->i?></td>
                    <td><?php echo $object->w?></td>
                    <td><?php echo $object->a?></td>
                    <td><?php echo $object->e?></td>
                    <td><?php echo $object->M?></td>
                </tr>
            </table>
        </div>