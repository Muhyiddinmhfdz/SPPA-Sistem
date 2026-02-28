<?php

namespace App\DataTables;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class UserDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->addColumn('avatar', function ($user) {
                $initials = $user->initials;

                return '<div class="symbol symbol-circle symbol-40px"><div class="symbol-label bg-light-primary text-primary fw-bold">'.$initials.'</div></div>';
            })
            ->addColumn('name', function ($user) {
                return '<span class="fw-bold">'.e($user->name).'</span>';
            })
            ->addColumn('role', function ($user) {
                return $user->roles->map(function ($role) {
                    return '<span class="badge badge-light-primary">'.e($role->name).'</span>';
                })->join(' ');
            })
            ->addColumn('status', function ($user) {
                if ($user->is_active) {
                    return '<span class="badge badge-success">Aktif</span>';
                }

                return '<span class="badge badge-danger">Tidak Aktif</span>';
            })
            ->addColumn('action', function ($user) {
                return view('master.user.action', compact('user'))->render();
            })
            ->rawColumns(['avatar', 'name', 'role', 'status', 'action'])
            ->setRowId('id');
    }

    public function query(User $model): QueryBuilder
    {
        return $model->newQuery()->with('roles');
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('user-table')
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
            Column::make('avatar')->title('')->width(50)->orderable(false)->searchable(false),
            Column::make('name')->title('Nama'),
            Column::make('username')->title('Username'),
            Column::make('email')->title('Email'),
            Column::computed('role')->title('Role'),
            Column::computed('status')->title('Status')->width(100),
            Column::make('created_at')->title('Dibuat')->width(120),
            Column::computed('action')->title('Aksi')->width(120)->addClass('text-center'),
        ];
    }

    protected function filename(): string
    {
        return 'User_'.date('YmdHis');
    }
}
