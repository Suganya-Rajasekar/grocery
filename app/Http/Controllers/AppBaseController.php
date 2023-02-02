<?php

namespace App\Http\Controllers;

use InfyOm\Generator\Utils\ResponseUtil;
use App\Models\Module;
use Response;
use App\Models\Translate;
use App\Models\Testimonials;
use App\Models\Language;
use App\Models\TranslateFields;
/**
 * @SWG\Swagger(
 *   basePath="/api/v1",
 *   @SWG\Info(
 *     title="Laravel Generator APIs",
 *     version="1.0.0",
 *   )
 * )
 * This class should be parent class for other API controllers
 * Class AppBaseController
 */
class AppBaseController extends Controller
{
    public function sendResponse($result, $message)
    {
        return Response::json(ResponseUtil::makeResponse($message, $result));
    }

    public function sendError($error, $code = 404)
    {
        return Response::json(ResponseUtil::makeError($error), $code);
    }

    public function sendSuccess($message)
    {
        return Response::json([
            'success' => true,
            'message' => $message
        ], 200);
    }

    public function translate($id)
    {
        $tbl = request()->segment(1);
        $Lang = Language::get()->map(function($result) use($tbl,$id){
            $result->lang_datas = $result->getTranslateDataAttribute($tbl,$id);
            return $result;
        });
        $fields = TranslateFields::where('name',ucfirst($tbl))->pluck('fields')->first();
        $fields = json_decode($fields,TRUE);
        return view('testimonials.translate')->with('language', $Lang)->with('id',$id)->with('fields',$fields);
    }
}
