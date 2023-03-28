<?php

namespace App\DataTables;

use App\Models\Order;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class OrderDataTable extends DataTable
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
            ->addColumn('total', function($order) {
                return 'Rs.'.$order->total;
            })

            ->addColumn('date',function($order){
                return $order->created_at->diffForHumans();
            })
            

            ->addColumn('user_name',function($order){
                if(auth()->user()->is_admin==1){
                    return '<a href="'.route('admin.order.show', [$order->id]).'" class="nav-link mr-1">'.   '#'.$order->id. ' '.$order->user->name.'</a>';
                }else{
                    return '<a href="'.route('order.show', [$order->id]).'" class="nav-link mr-1">'.   '#'.$order->id. ' '.$order->user->name.'</a>';
                }
               
            })
            ->rawColumns(['user_name']);
            
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Order $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Order $model)
    {
        if(auth()->user()->is_admin==1){
            $orders= Transaction::query()->with('user','orders')->OrderBy('created_at','desc')->select();

        }else{
            $orders = Transaction::query()->with('user','sellerorders')->whereJsoncontains('seller_id',auth()->user()->id)->OrderBy('created_at','desc')->select();
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
                    ->setTableId('order-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->orderBy(0);
   
                    
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
            'product_name' => new \Yajra\DataTables\Html\Column(['title' => 'Product Name', 'data' => 'product.name']),
           'order_status',
           'date',
           'total'                     
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Order_' . date('YmdHis');
    }
}
