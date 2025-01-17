<?php

class Course_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_all_courses()
    {
        $result = $this->db->select('*')
                ->select("courses.active AS course_active")
                ->select("courses.public AS course_public")
                ->from('courses')
                ->join('sub_categories', 'sub_categories.id = courses.category_id','left')
                ->join('categories', 'sub_categories.cat_id = categories.category_id','left')
                ->join('users', 'users.user_id = courses.created_by')
                ->get()
                ->result();
        return $result;
    }

    public function get_my_courses()
    {
        $result = $this->db->select('user_course.course_id,user_course.last_download,courses.course_title,courses.active,courses.course_price,courses.course_id,sub_categories.sub_cat_name,categories.category_name')
                ->where('user_id', $this->session->userdata('user_id'))
                ->or_where('courses.public', 1)
                ->from('user_course')
                ->join('courses', 'courses.course_id = user_course.course_id','left')
                ->join('sub_categories', 'sub_categories.id = courses.category_id','left')
                ->join('categories', 'sub_categories.cat_id = categories.category_id','left')
                ->get()
                ->result();

        return $result;
    }

    // public function get_my_courses()
    // {
    //     $result = $this->db->select('user_course.course_id,user_course.last_download,courses.course_title,courses.active,courses.course_price,courses.course_id,sub_categories.sub_cat_name,categories.category_name')
    //             ->where('user_id', $this->session->userdata('user_id'))
    //             ->from('user_course')
    //             ->join('courses', 'courses.course_id = user_course.course_id','left')
    //             ->join('sub_categories', 'sub_categories.id = courses.category_id','left')
    //             ->join('categories', 'sub_categories.cat_id = categories.category_id','left')
    //             ->get()
    //             ->result();

    //     return $result;
    // }

    public function get_user_courses($userId)
    {
        $result = $this->db->select('*')
                ->from('courses')
                ->where('courses.created_by', $userId)
                ->join('sub_categories', 'sub_categories.id = courses.category_id','left')
                ->join('categories', 'sub_categories.cat_id = categories.category_id','left')
                ->join('users', 'users.user_id = courses.created_by')
                ->get()
                ->result();
        return $result;
    }

    public function get_course_by_id($id)
    {
        $result = $this->db->select('*')
                ->where('course_id', $id)
//                ->order_by('section_id', 'asc')
                ->from('courses')
                ->join('sub_categories', 'sub_categories.id = courses.category_id','left')
                ->join('categories', 'sub_categories.cat_id = categories.category_id','left')
                ->join('users', 'users.user_id = courses.created_by')
                ->get()
                ->row();
        return $result;
    }
//----------------Sabbir adding get_course_order_by----------------------------------------

    public function get_courses_by_cat_id($cat_id)
    {
        return $this->db->select("course_title,course_id")
            ->where('category_id', $cat_id)
            ->get('courses')
            ->result();
    }

    public function get_courses_by_category($cat_id)
    {
        $result = $this->db->select('*')
                ->select("courses.public AS course_public")
                ->select("courses.active AS course_active")
                ->from('courses')
                ->where('courses.category_id', $cat_id)
                ->join('sub_categories', 'sub_categories.id = courses.category_id','left')
                ->join('categories', 'sub_categories.cat_id = categories.category_id','left')
                ->join('users', 'users.user_id = courses.created_by')
                ->get()->result();
        return $result;
    }

    public function get_courses_by_price($type)
    {
        if($type === 'free'){
            $result = $this->db->select('*')
                            ->select("courses.active AS course_active")
                            ->from('courses')
                            ->where('courses.course_price', 0)
                            ->join('sub_categories', 'sub_categories.id = courses.category_id','left')
                            ->join('categories', 'sub_categories.cat_id = categories.category_id','left')
                            ->join('users', 'users.user_id = courses.created_by')
                            ->get()->result();
        }else if($type === 'paid'){
            $result = $this->db->select('*')
                            ->select("courses.active AS course_active")
                            ->from('courses')
                            ->where('courses.course_price >', 0)
                            ->join('sub_categories', 'sub_categories.id = courses.category_id','left')
                            ->join('categories', 'sub_categories.cat_id = categories.category_id','left')
                            ->join('users', 'users.user_id = courses.created_by')
                            ->get()->result();
        }else{
            redirect(base_url('course_control/view_all_mocks'));
        }
        return $result;
    }
    public function get_course_order_by($id)
    {
        $result = $this->db->select('*')
                ->where('course_id', $id)
                ->from('course_sections')
                ->order_by('orderList', 'asc')
                ->get()
                ->result();
        return $result;
    }

    //----------------------------------

    public function get_videos_order_by($id)
    {
        $result = $this->db->select('*')
                ->where('course_id', $id)
                ->from('course_videos')
                ->order_by('orderList', 'asc')
//                ->join('course_sections', 'course_sections.course_id = course_videos.course_id')
                ->get()
                ->row();
        return $result;
    }
    public function get_course_detail($id)
    {
        $result = $this->db->select('*')
                ->where('course_id', $id)
                ->from('courses')
                ->join('sub_categories', 'sub_categories.id = courses.category_id','left')
                ->join('categories', 'sub_categories.cat_id = categories.category_id','left')
                ->join('users', 'users.user_id = courses.created_by')
                ->get()
                ->row();
        return $result;
    }

    public function get_section_detail($id)
    {
        $result = $this->db->select('*')
                ->where('section_id', $id)
                ->from('course_sections')
                ->order_by('section_id', 'asc')
                ->join('courses', 'courses.course_id = course_sections.course_id')
                ->get()
                ->row();
        return $result;
    }

    public function get_section_videos($sec_id, $course_id)
    {
        $result = $this->db->where('section_id', $sec_id)
                ->where('course_id', $course_id)
                ->order_by('orderList', 'asc')
                ->get('course_videos')
                ->result();
        return $result;
    }

    public function add_course_title($upload_data = '')
    {
        $info = array();
        $info['course_title'] = $this->input->post('course_title', TRUE);
        $info['course_intro'] = $this->input->post('course_intro', TRUE);
        $info['course_description'] = $this->input->post('course_description', TRUE);
        $info['course_requirement'] = $this->input->post('course_requirement', TRUE);
        $info['target_audience'] = $this->input->post('target_audience', TRUE);
        $info['what_i_get'] = $this->input->post('what_i_get', TRUE);
        $info['course_price'] = $this->input->post('price', TRUE);
        $info['public'] = $this->input->post('public', TRUE);
        $info['category_id'] = $this->input->post('category', TRUE);
        $info['created_by'] = $this->session->userdata['user_id'];
        $if_exist = $this->db->get_where('courses', array('course_title' => $info['course_title']), 1)->result();
        if ($if_exist) {
            return FALSE;
        } else {
            $this->db->insert('courses', $info);
            if ($this->db->affected_rows() == 1) {
                return $this->db->insert_id();
            } else {
                return FALSE;
            }
        }
    }

    public function update_course_title($id, $upload_data = '')
    {
        $info = array();
        $info['course_title'] = $this->input->post('course_title', TRUE);
        $info['course_intro'] = $this->input->post('course_intro', TRUE);
        $info['course_description'] = $this->input->post('course_description', TRUE);
        $info['course_requirement'] = $this->input->post('course_requirement', TRUE);
        $info['target_audience'] = $this->input->post('target_audience', TRUE);
        $info['what_i_get'] = $this->input->post('what_i_get', TRUE);
        $info['course_price'] = $this->input->post('price', TRUE);
        $info['public'] = $this->input->post('public', TRUE);
        $info['category_id'] = $this->input->post('category', TRUE);
        $info['created_by'] = $this->session->userdata['user_id'];
        $this->db->where('course_id', $id);
        $this->db->update('courses', $info);
        if ($this->db->affected_rows() == 1) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function save_course_sections()
    {
        $sections = $this->input->post('section', TRUE);
        $i=1;
        foreach ($sections as $value) {
            if ($value != '') {
                $info = array();
                $info['section_name'] = 'SECTION '.$i;
                $info['section_title'] = $value;
                $info['course_id'] = $this->input->post('course_id', TRUE);
                $this->db->insert('course_sections', $info);
                $i++;
            }
        }
        return TRUE;
    }

    public function add_section()
    {
        $course_id = $this->input->post('course_id', TRUE);

        $orderList = $this->db->select_max('orderList')->where('course_id', $course_id)->get('course_sections')->row()->orderList;

        $info = array();
        $info['section_name'] = $this->input->post('section_name', TRUE);
        $info['section_title'] = $this->input->post('section_title', TRUE);
        $info['course_id'] = $course_id;
        $info['orderList'] = $orderList+1;
        $this->db->insert('course_sections', $info);
        if ($this->db->affected_rows() == 1) {
            return $this->db->insert_id();
        } else {
            return FALSE;
        }
    }

    public function update_section()
    {
        $info = array();
        $info['section_name'] = $this->input->post('section_name', TRUE);
        $info['section_title'] = $this->input->post('section_title', TRUE);
        $info['course_id'] = $this->input->post('course_id', TRUE);
        $this->db->where('section_id', $this->input->post('section_id', TRUE));
        $this->db->update('course_sections', $info);
        if ($this->db->affected_rows() == 1) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function update_video()
    {
        $info = array();
        $info['video_title'] = $this->input->post('video_title', TRUE);
        $info['preview_type'] = $this->input->post('free', TRUE);
        $this->db->where('video_id', $this->input->post('video_id', TRUE));
        $this->db->update('course_videos', $info);
        if ($this->db->affected_rows() == 1) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function get_courses($id)
    {
        $result = $this->db->select('*')
                ->where('section_id', $id)
                ->order_by('orderList', 'asc')
                ->from('course_sections')
                ->get()
                ->result();
        return $result;
    }

    public function get_sections($id)
    {
        $result = $this->db->order_by('orderList', 'asc')
                ->where('course_id', $id)
                ->get('course_sections')
                ->result();
        return $result;
    }

    public function get_course_videos($id)
    {
        $result = $this->db->select('*')
                ->where('course_id', $id)
                ->from('course_videos')
                ->get()
                ->result();
        return $result;
    }

    public function add_course_video($link = '', $size = '')
    {
        $info = array();
        $info['course_id'] = $this->input->post('course_id', TRUE);
        $info['section_id'] = $this->input->post('section', TRUE);
        $info['video_title'] = $this->input->post('video_title', TRUE);
        $info['preview_type'] = $this->input->post('free', TRUE);
        $info['content_type'] = $this->input->post('media_type', TRUE);
        $info['youtube_link'] = $this->input->post('media', TRUE);
        $info['video_link'] = $link;
        $info['file_size'] = $size;
        $info['uploaded_by'] = $this->session->userdata['user_id'];
        // $info['created_at'] = time();

        $this->db->insert('course_videos', $info);
        if ($this->db->affected_rows() == 1) {
            $video_id = $this->db->insert_id();
            $section_video_ids = $this->db->get_where('course_sections', array('section_id' => $info['section_id']))->row()->video_ids;
            if ($section_video_ids) {
                $data['video_ids'] = $section_video_ids.','.$video_id;
            }else{
               $data['video_ids'] = $video_id;
            }
            $this->db->where('section_id', $info['section_id'])->update('course_sections', $data);
            return $video_id;
        } else {
            return FALSE;
        }
    }

    public function add_course_video_youtube()
    {
        $info = array();
        $info['course_id'] = $this->input->post('course_id', TRUE);
        $info['section_id'] = $this->input->post('section', TRUE);
        $info['video_title'] = $this->input->post('video_title', TRUE);
        $info['youtube_link'] = $this->input->post('youtube', TRUE);

        $this->db->insert('course_videos', $info);
        if ($this->db->affected_rows() == 1) {
            $video_id = $this->db->insert_id();
            $section_video_ids = $this->db->get_where('course_sections', array('section_id' => $info['section_id']))->row()->video_ids;
            if ($section_video_ids) {
                $data['video_ids'] = $section_video_ids.','.$video_id;
            }else{
               $data['video_ids'] = $video_id;
            }
            $this->db->where('section_id', $info['section_id'])->update('course_sections', $data);
            return $video_id;
        } else {
            return FALSE;
        }
    }

    public function get_mocks_by_category($cat_id)
    {
        $result = $this->db->select('*')
                        ->select("exam_title.active AS exam_active")
                        ->from('exam_title')
                        ->where('exam_title.category_id', $cat_id)
                        ->join('categories', 'categories.category_id = exam_title.category_id','left')
                        ->join('users', 'users.user_id = exam_title.user_id')
                        ->get()->result();
        return $result;
    }

    public function get_latest_exams($count){
        $result = $this->db->select('*')
                        ->select("exam_title.active AS exam_active")
                        ->order_by('exam_title.title_id', 'desc')
                        ->from('exam_title')
                        ->limit($count)
                        ->join('sub_categories', 'sub_categories.id = exam_title.category_id','left')
                        ->join('users', 'users.user_id = exam_title.user_id')
                        ->get()->result();
        return $result;
    }

    public function get_mocks_by_price($type)
    {
        if($type === 'free'){
            $result = $this->db->select('*')
                            ->select("exam_title.active AS exam_active")
                            ->from('exam_title')
                            ->where('exam_title.exam_price', 0)
                            ->join('categories', 'categories.category_id = exam_title.category_id','left')
                            ->join('users', 'users.user_id = exam_title.user_id')
                            ->get()->result();
        }else if($type === 'paid'){
            $result = $this->db->select('*')
                            ->select("exam_title.active AS exam_active")
                            ->from('exam_title')
                            ->where('exam_title.exam_price >', 0)
                            ->join('categories', 'categories.category_id = exam_title.category_id','left')
                            ->join('users', 'users.user_id = exam_title.user_id')
                            ->get()->result();
        }else{
            redirect(base_url('exam_control/view_all_mocks'));
        }
        return $result;
    }

    public function get_mock_title($id)
    {
        return $this->db->where('title_id', $id)->get('exam_title')->row();
    }

    public function get_mock_detail($id)
    {
        if (!is_numeric($id)) {
            return FALSE;
        }
        $this->db->where('exam_id', $id);
        $result = $this->db->get('questions')->result();
        return $result;
    }

    public function get_mock_answers($info)
    {
        $data = array();
        foreach ($info as $value) {
            $data[$value->ques_id][] = $this->db->where('ques_id', $value->ques_id)
                    ->from('answers')
                    ->get()
                    ->result();
        }
        return $data;
    }

    public function mock_count($info)
    {
        $data = array();
        foreach ($info as $value) {
            $data[$value->id] = $this->db->where('category_id', $value->id)
                    ->where("active", 1)
                    ->from('exam_title')
                    ->count_all_results();
        }
        return $data;
    }

    public function course_count($info)
    {
        $data = array();
        foreach ($info as $value) {
            $data[$value->id] = $this->db->where('category_id', $value->id)
                    ->from('courses')
                    ->count_all_results();
        }
        return $data;
    }

    public function question_count_by_id($id)
    {
        $total = $this->db->where('quiz_id', $id)
                ->from('quiz_questions')
                ->count_all_results();
        return $total;
    }

    public function get_item_detail($id)
    {
        $result = $this->db->select('title_name,exam_price')
                ->where('title_id', $id)
                ->from('exam_title')
                ->get()
                ->row();
        return $result;
    }

    /**
     *
     * @param type $id
     * @return object
     */
    public function get_quiz_by_id($id)
    {
        $result = $this->db->select('*')
                ->select("TIME_TO_SEC(quiz_title.time_duration) AS duration")
                ->from('quiz_title')
                ->where('quiz_title.title_id', $id)
                // ->join('sub_categories', 'sub_categories.id = quiz_title.category_id', 'left')
                ->get()
                ->row();
        return $result;
    }

    public function get_question_by_id($id)
    {
        $result = $this->db->select('*')
                ->from('questions')
                ->where('questions.ques_id', $id)
                ->join('exam_title', 'exam_title.title_id = questions.exam_id', 'left')
                ->get()
                ->row();
        return $result;
    }

    public function get_answer_by_id($id)
    {
        $result = $this->db->select('*')
                ->from('answers')
                ->where('answers.ans_id', $id)
                ->join('questions', 'questions.ques_id = answers.ques_id', 'left')
                ->join('exam_title', 'exam_title.title_id = questions.exam_id', 'left')
                ->get()
                ->row();
        return $result;
    }

}
