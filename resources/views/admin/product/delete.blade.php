@extends('layouts.admin')

<!-- Delete Warning Modal -->
<div class="modal modal-danger fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="Delete" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Delete Product</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            <form action="{{ route('product.destroy', [$product->id]) }}" class="mr-1" method="POST">
                @csrf
                @method('DELETE')
                <input id="id" name="id")>
                <h5 class="text-center">Are you sure you want to delete this product?</h5>
                <input id="id" name="{{$product->id}}"><input id="lastName" name="lastName">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-sm btn-danger">Yes, Delete Contact</button>
            </div>
            </form>
        </div>
    </div>
</div>
        