<?php

namespace App\Http\Controllers;

use App\Http\Requests\Createsub_adminRequest;
use App\Http\Requests\Updatesub_adminRequest;
use App\Repositories\sub_adminRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class sub_adminController extends AppBaseController
{
    /** @var  sub_adminRepository */
    private $subAdminRepository;

    public function __construct(sub_adminRepository $subAdminRepo)
    {
        $this->subAdminRepository = $subAdminRepo;
    }

    /**
     * Display a listing of the sub_admin.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $subAdmins = $this->subAdminRepository->all();

        return view('sub_admins.index')
            ->with('subAdmins', $subAdmins);
    }

    /**
     * Show the form for creating a new sub_admin.
     *
     * @return Response
     */
    public function create()
    {
        return view('sub_admins.create');
    }

    /**
     * Store a newly created sub_admin in storage.
     *
     * @param Createsub_adminRequest $request
     *
     * @return Response
     */
    public function store(Createsub_adminRequest $request)
    {
        $input = $request->all();

        $subAdmin = $this->subAdminRepository->create($input);

        Flash::success('Sub Admin saved successfully.');

        return redirect(route('subAdmins.index'));
    }

    /**
     * Display the specified sub_admin.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $subAdmin = $this->subAdminRepository->find($id);

        if (empty($subAdmin)) {
            Flash::error('Sub Admin not found');

            return redirect(route('subAdmins.index'));
        }

        return view('sub_admins.show')->with('subAdmin', $subAdmin);
    }

    /**
     * Show the form for editing the specified sub_admin.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $subAdmin = $this->subAdminRepository->find($id);

        if (empty($subAdmin)) {
            Flash::error('Sub Admin not found');

            return redirect(route('subAdmins.index'));
        }

        return view('sub_admins.edit')->with('subAdmin', $subAdmin);
    }

    /**
     * Update the specified sub_admin in storage.
     *
     * @param int $id
     * @param Updatesub_adminRequest $request
     *
     * @return Response
     */
    public function update($id, Updatesub_adminRequest $request)
    {
        $subAdmin = $this->subAdminRepository->find($id);

        if (empty($subAdmin)) {
            Flash::error('Sub Admin not found');

            return redirect(route('subAdmins.index'));
        }

        $subAdmin = $this->subAdminRepository->update($request->all(), $id);

        Flash::success('Sub Admin updated successfully.');

        return redirect(route('subAdmins.index'));
    }

    /**
     * Remove the specified sub_admin from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $subAdmin = $this->subAdminRepository->find($id);

        if (empty($subAdmin)) {
            Flash::error('Sub Admin not found');

            return redirect(route('subAdmins.index'));
        }

        $this->subAdminRepository->delete($id);

        Flash::success('Sub Admin deleted successfully.');

        return redirect(route('subAdmins.index'));
    }
}
