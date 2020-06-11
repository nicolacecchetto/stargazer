        <div class="container-fluid">
            <?= form_open('defcont/planetsnow') ?>
                <div class="form-group">
                    <label for="lat">Latitude:</label>
                    <input type="int" class="form-control" id="lat" name="lat" placeholder="Enter Latitude">
                </div>
                <div class="form-group">
                    <label for="long">Longitude:</label>
                    <input type="int" class="form-control" id="long" name="long" placeholder="Enter Longitude">
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-default">Submit</button>
                </div>
            </form>
        </div>