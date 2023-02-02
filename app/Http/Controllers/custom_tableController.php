<?php

namespace App\Http\Controllers;

use App\Http\Requests\Createcustom_tableRequest;
use App\Http\Requests\Updatecustom_tableRequest;
use App\Repositories\custom_tableRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class custom_tableController extends AppBaseController
{
    /** @var  custom_tableRepository */
    private $customTableRepository;

    public function __construct(custom_tableRepository $customTableRepo)
    {
        $this->customTableRepository = $customTableRepo;
    }

    /**
     * Display a listing of the custom_table.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $customTables = $this->customTableRepository->all();

        return view('custom_tables.index')
            ->with('customTables', $customTables);
    }

    /**
     * Show the form for creating a new custom_table.
     *
     * @return Response
     */
    public function create()
    {
        return view('custom_tables.create');
    }

    /**
     * Store a newly created custom_table in storage.
     *
     * @param Createcustom_tableRequest $request
     *
     * @return Response
     */
    public function store(Createcustom_tableRequest $request)
    {
        $input = $request->all();

        $customTable = $this->customTableRepository->create($input);

        Flash::success('Custom Table saved successfully.');

        return redirect(route('customTables.index'));
    }

    /**
     * Display the specified custom_table.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $customTable = $this->customTableRepository->find($id);

        if (empty($customTable)) {
            Flash::error('Custom Table not found');

            return redirect(route('customTables.index'));
        }

        return view('custom_tables.show')->with('customTable', $customTable);
    }

    /**
     * Show the form for editing the specified custom_table.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $customTable = $this->customTableRepository->find($id);

        if (empty($customTable)) {
            Flash::error('Custom Table not found');

            return redirect(route('customTables.index'));
        }

        return view('custom_tables.edit')->with('customTable', $customTable);
    }

    /**
     * Update the specified custom_table in storage.
     *
     * @param int $id
     * @param Updatecustom_tableRequest $request
     *
     * @return Response
     */
    public function update($id, Updatecustom_tableRequest $request)
    {
        $customTable = $this->customTableRepository->find($id);

        if (empty($customTable)) {
            Flash::error('Custom Table not found');

            return redirect(route('customTables.index'));
        }

        $customTable = $this->customTableRepository->update($request->all(), $id);

        Flash::success('Custom Table updated successfully.');

        return redirect(route('customTables.index'));
    }

    /**
     * Remove the specified custom_table from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $customTable = $this->customTableRepository->find($id);

        if (empty($customTable)) {
            Flash::error('Custom Table not found');

            return redirect(route('customTables.index'));
        }

        $this->customTableRepository->delete($id);

        Flash::success('Custom Table deleted successfully.');

        return redirect(route('customTables.index'));
    }
}
