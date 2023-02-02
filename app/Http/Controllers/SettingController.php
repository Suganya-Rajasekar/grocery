<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateSettingRequest;
use App\Http\Requests\UpdateSettingRequest;
use App\Repositories\SettingRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Flash;
use Response;
use App\Models\Chefs;
use App\Models\SettingsBoyApi;
use App\Models\SiteSetting;
use App\Models\SettingsApiKey;

class SettingController extends Controller
{
    /** @var  SettingRepository */
    private $SettingRepository;

    public function __construct(SettingRepository $settingRepo)
    {
        $this->SettingRepository = $settingRepo;
    }

    /**
     * Display a listing of the setting.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        /*$setting = $this->SettingRepository->find(1);
        return view('setting.index')
            ->with('setting', $setting);*/
         $pageCount  = 10;
        $page   = (($request->query('page')) ? $request->query('page')-1 : 0)*$pageCount;
        $settingboyapi=SettingsBoyApi::paginate(10);
        return view('setting.index_setting',compact('settingboyapi','page'));
    }

    /**
     * Show the form for creating a new setting.
     *
     * @return Response
     */
    public function create()
    {
        return view('setting.create');
    }

    /**
     * Store a newly created setting in storage.
     *
     * @param CreateSettingRequest $request
     *
     * @return Response
     */
    public function store(CreateSettingRequest $request)
    {   
        $input = $request->all();

        $setting = $this->SettingRepository->create($input);

        Flash::success('Setting saved successfully.');

        return redirect(route('setting.index'));
    }

    /**
     * Display the specified setting.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $setting = $this->SettingRepository->find($id);

        if (empty($setting)) {
            Flash::error('Setting not found');

            return redirect(route('setting.index'));
        }

        return view('setting.show')->with('setting', $setting);
    }

    /**
     * Show the form for editing the specified setting.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $setting = $this->SettingRepository->find($id);

        if (empty($setting)) {
            Flash::error('Setting not found');

            return redirect(route('setting.index'));
        }

        return view('setting.edit')->with('setting', $setting);
    }

    /**
     * Update the specified setting in storage.
     *
     * @param int $id
     * @param UpdateSettingRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateSettingRequest $request)
    {
        $setting = $this->SettingRepository->find($id);

        if (empty($setting)) {
            Flash::error('Setting not found');

            return redirect(route('setting.index'));
        }

        $setting = $this->SettingRepository->update($request->all(), $id);

        Flash::success('Setting updated successfully.');
        return redirect(route('setting.index'));
    }

    /**
     * Remove the specified setting from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $setting = $this->SettingRepository->find($id);
        if (empty($setting)) {
            Flash::error('Setting not found');
            return redirect(route('setting.index'));
        }
        $this->SettingRepository->delete($id);
        Flash::success('Setting deleted successfully.');
        return redirect(route('setting.index'));
    }
    public function Settingsboyapi(Request $request)
    {
        $settingboyapi=SettingsBoyApi::find(1);
        $site_setting = SiteSetting::find(1);
        return view('settingboyapi.form',compact('settingboyapi','site_setting'));
    }
    public function Settingsapikey(Request $request)
    {
        $settingsapikey=SettingsApiKey::find(1);
        return view('settingapikey.form',compact('settingsapikey'));
    }
    public function updateapikey(Request $request){
        $settingapikey = SettingsApiKey::where("id", $request->input('id'))->first();
        $settingapikey->map_key =  $request->get('mapkey');  
        $settingapikey->facebook_client_id = $request->get('facebookclientid');  
        $settingapikey->facebook_client_secret = $request->get('facebookclientsecret');
        $settingapikey->facebook_redirect = $request->get('facebookredirect');  
        $settingapikey->google_client_id = $request->get('googleclientid');  
        $settingapikey->google_client_secret = $request->get('googleclientsecret'); 
        $settingapikey->google_redirect = $request->get('googleredirect'); 
        $settingapikey->save();  
        Flash::success('Setting Api Key details saved successfully.');
        return redirect(getRoleName().'/settings/settingsapikey');
    }
}
