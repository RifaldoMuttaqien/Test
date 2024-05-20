<div id="note">
    <?=validation_errors('<div class="alert alert-danger">', '</div>'); ?>
    <?=($this->session->flashdata('message')) ? $this->session->flashdata('message') : '' ?>
</div>
<div class="block">
    <div class="navbar block-inner block-header">
        <div class="row">
            <p class="text-muted">Assigned Courses
                <a class="btn custom_navbar-btn btn-primary pull-right col-sm-3" href="#assign" data-toggle="modal"><i class="glyphicon glyphicon-cog"></i>&nbsp; Assign course </a>
            </p>
        </div>
    </div>

    <div class="block-content">
        <div class="row">
            <div class="col-sm-12">
                <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered datatable" id="example">
                    <thead>
                        <tr>
                            <th>Category</th>
                            <th>Sub-Category</th>
                            <th>Course Title</th>
                            <th class="hidden-xxs">Last Download</th>
                            <th class="col-sm-3" style="width: 27%">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php $i = 1;
                    foreach ($courses as $course) { ?>
                        <tr class="<?=($i & 1) ? 'even' : 'odd'; ?>">
                            <td><?=$course->category_name; ?></td>
                            <td><?=$course->sub_cat_name; ?></td>
                            <td><?=$course->course_title; ?></td>
                            <td><?=$course->last_download ?: 'Never'; ?></td>
                            <td>

                                <a onclick="return delete_confirmation()" href = "<?php echo base_url('index.php/user_control/delete_assinged_course/' . $user_id . '/'. $course->course_id); ?>" class="btn btn-sm btn-default"><i class="glyphicon glyphicon-trash"></i><span class="invisible-on-md">  Delete</span></a>

                            </td>
                        </tr>
                        <?php $i++;
                    } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div><!--/span-->

<!-- Set top feature Modal -->
<div class="modal fade" id="assign" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="TRUE">
    <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="TRUE">&times;</button>
        <h4 class="modal-title">Assign new course</h4>
      </div>
      <div class="modal-body">
        <?php echo form_open(base_url() . 'index.php/user_control/assign_new_cours/' . $user_id, 'role="form" class="form-horizontal"'); ?>

            <?php
            $option = array();
            $option[''] = 'Select Category';
            foreach ($categories as $category) {
                if ($category->active) {
                    $option[$category->category_id] = $category->category_name;
                }
            }
            ?>
            <div class="form-group">
                <label for="category" class="col-md-2 control-label mobile">Category:</label>
                <div class="col-md-5">
                    <?php echo form_dropdown('parent-category', $option,'', 'id="parent-category" class="form-control"') ?>
                </div>
                <div class="col-md-5">
                    <select name="category" id="category" class="form-control">
                        <option>Sub-category</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="courses" class="col-md-2 control-label mobile">Courses:</label>
                <div class="col-md-10">
                    <select name="courses[]" id="courses" class="form-control" multiple="true" required="required">
                        <option>Select category and sub-category to get courses</option>
                    </select>
                </div>
            </div>
      </div>
      <div class="modal-footer">
        <?php echo form_submit('submit', 'Save', 'class="btn btn-primary"') ?>
        <?php echo form_close() ?>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script>
$('select#parent-category').change(function() {
    var category = $(this).val();
    var link = '<?php echo base_url()?>'+'index.php/admin_control/get_subcategories_ajax/'+category;
    $.ajax({
        data: category,
        url: link
    }).done(function(subcategories) {
        subcategories = '<option>Select Sub-Category</option>' + subcategories;
        $('#category').html(subcategories);
    });
});

$('select#category').change(function() {
    var category = $(this).val();
    var link = '<?php echo base_url()?>'+'index.php/admin_control/get_courses_ajax/'+category;
    $.ajax({
        data: category,
        url: link
    }).done(function(courses) {
        $('#courses').html(courses);
    });
});

</script>