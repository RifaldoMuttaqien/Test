<div id="note">
<?=($this->session->flashdata('message')) ? $this->session->flashdata('message') : '';?>
</div>
<div class="block">  
    <div class="navbar block-inner block-header">
        <div class="row">
            <p class="text-muted">Exam Title: <?php echo (!empty($mock_title)) ? $mock_title->title_name : ''; ?>
            <?php 
            if (($mock_title->user_id == $this->session->userdata['user_id']) || ($this->session->userdata['user_role_id'] < 3)) {
            ?>
                <a class="btn custom_navbar-btn btn-primary pull-right col-sm-3" href="<?php echo base_url('index.php/admin_control/add_question') . '/' . $mock_title->title_id; ?>"><i class="glyphicon glyphicon-plus-sign"></i>&nbsp; Add Question</a>
            <?php 
            } ?>
            </p>
        </div>
    </div>
    <div class="block-content">
    <div class="row">
    <div class="col-sm-12">
    <?php if (isset($mocks) != NULL) { ?>
    <table cellpadding="0" cellspacing="0" border="0" class="table table-bordered table-hover" id="example">
        <thead>
            <tr>
                <th class="col-sm-1">Sl.</th>
                <th>Question</th>
                <th class="col-sm-3">Action</th>
            </tr>
        </thead>
        <tbody>
        <?php
            $i = 1;
            foreach ($mocks as $mock) {
        ?>
        <tr class="accordion-group <?=($i & 1) ? 'even' : 'odd'; ?>">
            <td class="col-sm-1"><?=$i; ?> : </td>
            <td class="accordion-heading">
                <a id="question_title-<?=$mock->ques_id;?>" href="#collapse_<?=$i; ?>"  data-toggle="collapse" class="accordion-toggle" style="text-decoration: none; padding: 0; color: #363636;">
                    <?=$mock->question; ?>
                </a>
                <div class="accordion-body collapse" id="collapse_<?php echo $i; ?>">
                    <div class="accordion-inner"><br/>
                    <p><span class="text-muted"> Option type: </span><?=$mock->option_type ?> 
                    <?php if ($mock->option_type == 'Radio') { ?>
                        <span class="pull-right"> <i class="glyphicon glyphicon-warning-sign"></i> Radio can't have more than 1 right answer.</span> <br/>
                    <?php } ?>
                    </p>
                    <?php if ($mock_ans[$mock->ques_id][0]) { ?>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>sl.</th>
                                    <th>Options</th>
                                    <th>Right Ans.</th>
                                    <th style="width: 15%">Action</th>
                                </tr>
                            </thead>
                            <?php
                            foreach ($mock_ans[$mock->ques_id] as $all_ans) {
                                $sl = 1;
                                foreach ($all_ans as $ans) { ?>
                                    <tr>
                                        <td style="width: 5%"><?php echo $sl; ?></td>
                                        <td>
                                            <a href="#" data-name="ans-text" data-type="textarea" data-rows="2" data-url="<?php echo base_url('index.php/admin_control/update_answer/'.$mock->ques_id); ?>" data-pk="<?=$ans->ans_id; ?>" class="data-modify-<?=$ans->ans_id; ?> no-style"><?=form_prep($ans->answer); ?></a>
                                        </td>
                                        <td>
                                            <a href="#" data-name="right-ans" data-type="select" data-source="[{value:0,text:' No '},{value:1,text:' Yes '}]" data-value="<?=$ans->right_ans; ?>" data-url="<?php echo base_url('index.php/admin_control/update_answer/'.$mock->ques_id); ?>" data-pk="<?=$ans->ans_id; ?>" class="data-modify-<?=$ans->ans_id; ?> no-style"><?=($ans->right_ans != 0) ? 'Yes' : 'No'; ?></a>
                                        </td>
                                        <td class="btn-group">
                                            <a class="btn btn-sm btn-default modify" name="modify-<?=$ans->ans_id; ?>"><i class="glyphicon glyphicon-edit"></i></a>
                                            <a class="btn btn-sm btn-default" onclick="return delete_confirmation();" href = "<?php echo base_url('index.php/admin_control/delete_answer/' . $ans->ans_id); ?>"><i class="glyphicon glyphicon-trash"></i></a>
                                        </td>
                                    </tr>
                                    <?php
                                    $sl++;
                                }
                            } ?>
                        </table>
                        <?php
                        } else { ?>
                        <table class="table table-bordered">
                            <tr><th>Empty !!!</th><tr>
                        </table>
                        <?php } ?>
                    </div>
                </div>
            </td>
            <td class="col-xs-3">
                <div class="btn-group">
                    <a href="#collapse_<?=$i; ?>"  data-toggle="collapse" class="btn btn-sm btn-default accordion-toggle "><i class="glyphicon glyphicon-resize-small"></i><span class="invisible-on-sm"> View</span></a>
                    <a class="btn btn-sm btn-default update"  data-update="<?=$mock->ques_id;?>" href="#update_ques" data-toggle="modal"><i class="glyphicon glyphicon-edit"></i><span class="invisible-on-md">  Modify</span></a>
                    <a onclick="return delete_confirmation()" href = "<?php echo base_url('index.php/admin_control/delete_question/' . $mock->ques_id); ?>" class="btn btn-sm btn-default"><i class="glyphicon glyphicon-trash"></i><span class="invisible-on-md">  Delete</span></a>
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
        echo 'This mock have no question!';
    }
    ?>
    </div>
    </div>
    </div>
</div><!--/span-->
