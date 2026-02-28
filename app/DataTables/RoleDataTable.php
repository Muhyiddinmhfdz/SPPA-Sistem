<?php

namespace App\DataTables;

use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class RoleDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->addColumn('name', function ($role) {
                return '<span class="fw-bold">'.e($role->name).'</span>';
            })
            ->addColumn('permissions', function ($role) {
                $permissions = $role->permissions->take(3);
                $html = $permissions->map(function ($perm) {
                    return '<span class="badge badge-light-info">'.e($perm->name).'</span>';
                })->join(' ');

                if ($role->permissions->count() > 3) {
                    $html .= ' <span class="badge badge-light-dark">+'.($role->permissions->count() - 3).'</span>';
                }

                return $html;
            })
            ->addColumn('users_count', function ($role) {
                $count = $role->users()->count();

                return '<span class="badge badge-light-primary">'.$count.' user</span>';
            })
            ->addColumn('action', function ($role) {
                return view('master.role.action', compact('role'))->render();
            })
            ->rawColumns(['name', 'permissions', 'users_count', 'action'])
            ->setRowId('id');
    }

    public function query(Role $model): QueryBuilder
    {
        return $model->newQuery()->with('permissions');
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('role-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('rt<"d-flex justify-content-between"ipl>B')
            ->orderBy(1)
            ->selectStyleSingle()
            ->buttons([
                Button::make('excel'),
                Button::make('csv'),
                Button::make('print'),
            ])
            ->parameters([
                'language' => [
                    'url' => url('/assets/plugins/custom/datatables/Indonesian.json'),
                ],
                'responsive' => true,
            ]);
    }

    public function getColumns(): array
    {
        return [
            Column::computed('DT_RowIndex')->title('No')->width(30)->addClass('text-center'),
            Column::make('name')->title('Nama Role'),
            Column::make('guard_name')->title('Guard')->width(80),
            Column::computed('permissions')->title('Permissions'),
            Column::computed('users_count')->title('Users')->width(100),
            Column::make('created_at')->title('Dibuat')->width(120),
            Column::computed('action')->title('Aksi')->width(120)->addClass('text-center'),
        ];
    }

    protected function filename(): string
    {
        return 'Role_'.date('YmdHis');
    }
}
