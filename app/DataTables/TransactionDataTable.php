<?php

namespace App\DataTables;

use App\Models\Transaction;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class TransactionDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        return datatables()
        ->eloquent($query)
        ->addColumn('amount', function($transacion) {
            return 'Rs.'.$transacion->amount;
        })

        ->addColumn('date',function($transaction){
            return $transaction->created_at->diffForHumans();
        })
        ->addColumn('user_name',function($transaction){
            if(auth()->user()->is_admin==1){
                return '<a href="'.route('admin.order.show', [$transaction->id]).'" class="nav-link mr-1">'.   '#'.$transaction->id. ' '.$transaction->user->name.'</a>';
            }else{
                return '<a href="'.route('order.show', [$transaction->id]).'" class="nav-link mr-1">'.   '#'.$transaction->id. ' '.$transaction->user->name.'</a>';
            }
           
        })
        ->rawColumns(['user_name']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Transaction $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Transaction $model)
    {
        if(auth()->user()->is_admin==1){
            $orders= Transaction::query()->with('user','orders')->OrderBy('created_at','desc')->select();

        }else{
            $orders = Transaction::query()->with('user','seller_orders')->whereJsoncontains('seller_id',auth()->user()->id)->OrderBy('created_at','desc')->select();
        }
        return $this->applyScopes($orders);

        return $model->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
                    ->setTableId('transaction-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->dom('Bfrtip')
                    ->orderBy(1);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
           'user_name',
            'payment_method',
            'amount',
            'date'
            
                       
                             
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Transaction_' . date('YmdHis');
    }
}
