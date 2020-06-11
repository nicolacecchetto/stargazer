        <div class="container-fluid">
            <?= form_open('defcont/planetsbydate') ?>
                <div class="form-group">
                    <label for="date">Date:</label>
                    <input type="date" class="form-control" id="date" name="date" placeholder="Enter Date">
                </div>
                <div class="form-group">
                    <label for="time">Time (UT):</label>
                    <input type="time" class="form-control" id="time" name="time" placeholder="Enter Time (Universal Time or Greenwich Mean Time)">
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-default">Submit</button>
                </div>
            </form>
        </div>