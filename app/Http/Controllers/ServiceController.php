<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateServiceRequest;
use App\Http\Requests\UpdateServiceRequest;
use App\Repositories\ServiceRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class ServiceController extends AppBaseController
{
    /** @var  ServiceRepository */
    private $ServiceRepository;

    public function __construct(ServiceRepository $ServiceRepository)
    {
        $this->middleware('CheckAdmin');
        $this->ServiceRepository = $ServiceRepository;
    }

    /**
     * Display a listing of the service.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $service = $this->ServiceRepository->all();

        return view('service.index')
            ->with('services', $service);
    }

    /**
     * Show the form for creating a new service.
     *
     * @return Response
     */
    public function create()
    {
        return view('service.create');
    }

    /**
     * Store a newly created service in storage.
     *
     * @param CreateServiceRequest $request
     *
     * @return Response
     */
    public function store(CreateServiceRequest $request)
    {
        $input = $request->all();
        $service = $this->ServiceRepository->create($input);

        Flash::success('Service saved successfully.');

        return redirect(route('service.index'));
    }

    /**
     * Display the specified service.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $service = $this->ServiceRepository->find($id);

        if (empty($service)) {
            Flash::error('Service not found');

            return redirect(route('service.index'));
        }

        return view('service.show')->with('service', $service);
    }

    /**
     * Show the form for editing the specified service.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $service = $this->ServiceRepository->find($id);

        if (empty($service)) {
            Flash::error('Service not found');

            return redirect(route('service.index'));
        }

        return view('service.edit')->with('service', $service);
    }

    /**
     * Update the specified service in storage.
     *
     * @param int $id
     * @param UpdateServiceRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateServiceRequest $request)
    {
        $formRequest = $request->all();
        $service = $this->ServiceRepository->find($id);
        if (empty($service)) {
            Flash::error('Service not found');
            return redirect(route('service.index'));
        }

        $service = $this->ServiceRepository->update($formRequest, $id);

        Flash::success('Service updated successfully.');

        return redirect(route('service.index'));
    }

    /**
     * Remove the specified service from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $service = $this->ServiceRepository->find($id);

        if (empty($service)) {
            Flash::error('Service not found');

            return redirect(route('service.index'));
        }

        $this->ServiceRepository->delete($id);

        Flash::success('Service deleted successfully.');

        return redirect(route('service.index'));
    }
}
