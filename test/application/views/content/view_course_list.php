<section id="exams">
    <div class="container">
        <div class="box">
            <div class="row">
                <div class="col-xs-10 col-xs-offset-1">
                    <?=validation_errors('<div class="alert alert-danger">', '</div>'); ?>
                    <?=($this->session->flashdata('message')) ? $this->session->flashdata('message') : '' ?>
                    <?=(isset($message)) ? $message : ''; ?>
                </div>
                <div class="col-lg-2 col-md-3 col-sm-12 col-xs-12 nopadding">
                    <ul class="nav  category-menu" style="float:left;">
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle " data-toggle="dropdown"><i class=" fa fa-sitemap"></i> &nbsp;All Categories <span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
                            <?php
                            foreach ($categories as $value) {
                                $sub = $this->db->get_where('sub_categories', array('cat_id' => $value->category_id))->result();
                                if(!empty($sub)){ ?>
                                    <li class="dropdown-submenu">
                                        <a href="#" tabindex="-1" class="dropdown-toggle" data-toggle="dropdown"><?=$value->category_name; ?></a>
                                        <ul class="dropdown-menu">
                                            <h3><i class="fa fa-code-fork"></i> <?=$value->category_name; ?></h3>
                                            <?php foreach ($sub as $sub_cat) { ?>
                                            <li>
                                                <a href="<?=base_url('index.php/course/view_course_by_category/'.$sub_cat->id); ?>"><?=$sub_cat->sub_cat_name; ?></a>
                                            </li>
                                            <?php } ?>
                                        </ul>
                                    </li>
                                <?php }else{ ?>
                                    <!-- <li><a href="#"><?=$value->category_name; ?></a></li> -->
                            <?php
                                }
                            } ?>
                            </ul>
                        </li>
                    </ul>
                </div><!--/.col-md-2-->

                <div class="col-lg-10 col-md-9 col-sm-12 col-xs-12 nopadding">
                    <h4><?=isset($category_name)?$category_name:'All Courses'; ?></h4>
                    <?php if ($commercial) { ?>
                    <div class="btn-group pull-right">
                        <a href="<?=base_url('index.php/course/index') ?>" class="btn btn-sm btn-default">All</a>
                        <a href="<?=base_url('index.php/course/courses_type/paid') ?>" class="btn btn-sm btn-default">Paid</a>
                        <a href="<?=base_url('index.php/course/courses_type/free') ?>" class="btn btn-sm btn-default">Free</a>
                    </div>
                    <?php } ?>
                    <div class="exam-list">
                        <?php
                        if (isset($courses) AND !empty($courses)) {  $i = 1; $my_courses = [];
                            if ($this->session->userdata('user_role_id') > 4) {
                                $my_courses = array_column($this->db->select('course_id')->where('user_id', $this->session->userdata('user_id'))->get('user_course')->result_array(), 'course_id');
                            }

                            foreach ($courses as $course) {
                                $permitted = in_array($course->course_id, $my_courses) ?: ($this->session->userdata('user_role_id') <= 4);
                                if (($course->course_public == 1 || $permitted) && $course->course_active == 1) {
                                ?>
                                    <div class="col-lg-3 col-md-4 col-xs-12 col-sm-6 exam-item">
                                        <div class="thumbnail">

                                            <span style="position: absolute; right: 20px; top: 20px; font-weight: lighter; font-size: 1.4em;">
                                                <?php if ($course->course_price) {
                                                    echo '<span class="label label-warning pull-right">'.$currency_symbol.$course->course_price.'</span>';
                                                }else{
                                                    echo '<span class="label label-primary pull-right">Free</span>';
                                                } ?>
                                            </span>

                                            <a href="<?php echo base_url('index.php/course/course_summary/'.$course->course_id); ?>">
                                                <?php if (file_exists("course-images/$course->course_id.png")) { ?>
                                                    <img class="exam-thumbnail" src="<?=base_url("course-images/$course->course_id.png"); ?>" data-src="holder.js/300x300" alt="...">
                                                <?php }else{ ?>
                                                    <img class="exam-thumbnail" src="<?=base_url('exam-images/placeholder.png'); ?>" data-src="holder.js/300x300" alt="...">
                                                <?php } ?>
                                                <div class="caption">
                                                    <span class="exam-category text-danger"><?=$course->category_name.'/'.$course->sub_cat_name;?></span>
                                                    <span class="exam-title"><?=$course->course_title;?></span>
                                                    <p>
                                                        <div class="exam-duration" >
                                                            <?=$this->db->where('course_id', $course->course_id)->from('course_videos')->count_all_results(); ?> lessons
                                                        </div>
                                                        <button class="btn btn-sm btn-primary">Start</button>

                                                        <div class="fb-share-button"
                                                            data-href="<?=base_url('index.php/course/course_summary/'.$course->course_id)?>"
                                                            data-layout="button" >
                                                        </div>

                                                    </p>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                    <?php $i++;
                                }
                            }
                        } else {
                            echo '<h4>No course found!</h4>';
                        }
                        ?>
                    </div> <!-- /exam-list -->
                </div><!--/.col-md-10-->
            </div><!--/.row-->
        </div><!--/.box-->
    </div><!--/.container-->
</section><!--/#emaxs-->