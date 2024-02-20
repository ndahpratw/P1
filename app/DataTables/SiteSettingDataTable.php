<?php

namespace App\DataTables;

use App\Models\SiteSettingModel;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Livewire\Livewire;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class SiteSettingDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', 'sitesetting.action')
            ->addIndexColumn()
            ->addColumn('action', function (SiteSettingModel $val) {
                return Livewire::mount('pages.admin.site-setting.site-table-action', ['site' => $val]);
            })
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(SiteSettingModel $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('sitesetting-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax(script: "
                        data._token = '" . csrf_token() . "';
                        data._p = 'POST';")
                    ->dom('rt' . "<'row'<'col-sm-12 col-md-5'l><'col-sm-12 col-md-7'p>>",)
                    ->addTableClass('table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer text-gray-600 fw-semibold')
                    ->setTableHeadClass('text-start text-muted fw-bold fs-7 text-uppercase gs-0')
                    ->orderBy(2)
                    ->drawCallbackWithLivewire(file_get_contents(public_path('assets/js/custom/table/_init.js')))
                    ->select(false)
                    ->buttons([]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::computed("DT_RowIndex")
                ->title("No.")
                ->width(20),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-center'),
            Column::make("name"),
            Column::make("value"),
            Column::make("description"),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'SiteSetting_' . date('YmdHis');
    }
}
