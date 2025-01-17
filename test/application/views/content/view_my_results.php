<div id="note">
    <?php
        date_default_timezone_set($this->session->userdata['time_zone']);
    if ($message) {
        echo $message;
    }
    ?>
</div>

<div class="block">
    <div class="navbar block-inner block-header">
        <div class="row"><p class="text-muted">Results </p></div>
    </div>
    <div class="block-content">
        <div class="row">
            <div class="col-sm-12">
                <?php if (isset($results) != NULL) { ?>
                    <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="example">
                        <thead>
                            <tr>
                                <th>Exam Title</th>
                                <th class="hidden-xxs">Assessment</th>
                                <th class="hidden-xxs">Question</th>
                                <th class="hidden-xs">Date</th>
                                <th class="text-center" style=" width: 10%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 1;
                            foreach ($results as $result) {
                            ?>
                                <tr class="<?= ($i & 1) ? 'even' : 'odd'; ?>">
                                    <td><?= $result->title_name; ?></td>
                                    <td class="hidden-xxs"><?= ($result->result_percent >= $result->pass_mark) ? '<span class="label label-primary">DONE</span>' : '<span class="label label-primary">DONE</span>' ?></td>
                                    <td class="hidden-xxs"><?php echo $result->question_answered; ?></td>
                                    <td class="hidden-xs"><?= date("D, d M", strtotime($result->exam_taken_date)); ?></td>
                                    <td class="text-center" style=" width: 17%">
                                        <div class="btn-group">
                                            <?php if($result->student_can_see_ans_key){ ?>
                                                <a class="btn btn-default btn-xs" href = "<?= base_url('index.php/exam_control/view_exam_detail/' . $result->result_id); ?>"><i class="glyphicon glyphicon-list-alt"></i> Details</a>
                                            <?php } ?>
                                            <a class="btn btn-default btn-xs" href = "<?= base_url('index.php/exam_control/view_result_detail/' . $result->result_id); ?>"><i class="glyphicon glyphicon-file"></i> Certificate</a>
                                        </div>
                                    </td>
                                </tr>
                                <?php
                                $i++;
                            }
                            ?>
                        </tbody>
                    </table>
                    <?php
                } else {
                    echo 'No results!';
                }
                ?>
            </div>
        </div>
    </div>
</div><!--/span-->