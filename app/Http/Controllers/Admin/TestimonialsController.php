<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\Admin\Testimonial;
use App\Models\Admin\Employer;
use App\Rules\MinString;
use App\Rules\MaxString;

use SimpleExcel\SimpleExcel;

class TestimonialsController extends Controller
{
    /**
     * View Function to display testimonials list view page
     *
     * @return html/string
     */
    public function testimonialsListView()
    {
        $data['page'] = __('message.testimonials');
        $data['menu'] = 'testimonials';
        return view('admin.testimonials.list', $data);
    }

    /**
     * Function to get data for testimonials jquery datatable
     *
     * @return json
     */
    public function testimonialsList(Request $request)
    {
        echo json_encode(Testimonial::testimonialsList($request->all()));
    }    

    /**
     * View Function (for ajax) to display create or edit view page via modal
     *
     * @param integer $testimonial_id
     * @return html/string
     */
    public function createOrEdit($testimonial_id = NULL)
    {
        $data['testimonial'] = objToArr(Testimonial::getTestimonial('testimonial_id', $testimonial_id));
        $data['employers'] = objToArr(Employer::getAll());
        echo view('admin.testimonials.create-or-edit', $data)->render();
    }

    /**
     * Function (for ajax) to process testimonial create or edit form request
     *
     * @return redirect
     */
    public function saveTestimonial(Request $request)
    {
        $this->checkIfDemo();

        $edit = $request->input('testimonial_id') ? $request->input('testimonial_id') : false;

        $rules['description'] = [new MaxString(5000)];

        $validator = Validator::make($request->all(), $rules, [
            'description.required' => __('validation.required'),
            'description.min' => __('validation.min_string'),
            'description.max' => __('validation.max_string'),
        ]);
        if ($validator->fails()) {
            die(json_encode(array(
                'success' => 'false',
                'messages' => $this->ajaxErrorMessage(array('validation_errors' => $validator->messages()->toArray()))
            )));
        }

        Testimonial::storeTestimonial($request->all(), $edit);
        die(json_encode(array(
            'success' => 'true',
            'messages' => $this->ajaxErrorMessage(array(
                'success' => __('message.testimonial').' ' . ($edit ? __('message.updated') : __('message.created'))
        )))));
    }

    /**
     * Function (for ajax) to process testimonial change status request
     *
     * @param integer $testimonial_id
     * @param string $status
     * @return void
     */
    public function changeStatus($testimonial_id = null, $status = null)
    {
        $this->checkIfDemo();
        Testimonial::changeStatus($testimonial_id, $status);
    }

    /**
     * Function (for ajax) to process testimonial bulk action request
     *
     * @return void
     */
    public function bulkAction(Request $request)
    {
        $this->checkIfDemo();
        Testimonial::bulkAction($request->input('data'));
    }

    /**
     * Function (for ajax) to process testimonial delete request
     *
     * @param integer $testimonial_id
     * @return void
     */
    public function delete($testimonial_id)
    {
        $this->checkIfDemo();
        Testimonial::remove($testimonial_id);
    }

    /**
     * Post Function to download testimonials data in excel
     *
     * @return void
     */
    public function testimonialsExcel(Request $request)
    {
        $data = Testimonial::getTestimonialsForCSV($request->input('ids'));
        $data = sortForCSV(objToArr($data));
        $excel = new SimpleExcel('csv');                    
        $excel->writer->setData($data);
        $excel->writer->saveFile('testimonials'); 
        exit;
    }
}